<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiActivityRecorder
{
    private const SKIP_PATH_SUFFIXES = [
        'available-slots',
    ];

    public function record(Request $request, Response $response): void
    {
        if ($request->attributes->get('audit_log_recorded')) {
            return;
        }

        if ($this->shouldSkip($request)) {
            return;
        }

        if ($response->getStatusCode() >= 500) {
            return;
        }

        $user = $request->user();
        $classified = AuditLogClassifier::classifyRequest($request);
        $isAuthRoute = $classified['target_type'] === 'auth';

        if (! $user && ! $isAuthRoute) {
            return;
        }

        if ($isAuthRoute && ! $this->shouldLogAuthResponse($response)) {
            return;
        }

        $details = [
            'portal' => $classified['portal'] ?? $user?->role?->name,
            'resource' => $classified['resource'],
            'sub_action' => $classified['sub_action'],
            'module' => $classified['module'],
            'action_category' => $classified['action_category'],
            'target_label' => $this->resolveTargetLabel($request, $response, $classified),
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'status_code' => $response->getStatusCode(),
        ];

        AuditLogger::log(
            action: $classified['action'],
            targetType: $classified['target_type'],
            targetId: $classified['target_id'] ?? $user?->id,
            status: $response->isSuccessful() ? 'success' : 'failed',
            details: array_filter($details, fn ($value) => $value !== null && $value !== ''),
            userId: $user?->id,
        );
    }

    protected function shouldSkip(Request $request): bool
    {
        if (in_array($request->method(), ['OPTIONS', 'HEAD'], true)) {
            return true;
        }

        $path = strtolower($request->path());

        foreach (self::SKIP_PATH_SUFFIXES as $suffix) {
            if (str_contains($path, $suffix)) {
                return true;
            }
        }

        if ($request->isMethod('DELETE') && preg_match('#audit-logs/?$#', $path)) {
            return true;
        }

        // Dropdown/filter prefetch on Audit Logs page — không ghi log (tránh nhầm với thao tác thật)
        if ($request->boolean('filter_options') || $request->query('filter_options') === '1') {
            return true;
        }

        return false;
    }

    protected function shouldLogAuthResponse(Response $response): bool
    {
        return $response->isSuccessful()
            || in_array($response->getStatusCode(), [401, 403, 422], true);
    }

    /** @param array{target_type: string, target_id: ?string} $classified */
    protected function resolveTargetLabel(Request $request, Response $response, array $classified): ?string
    {
        if ($classified['target_type'] === 'auth' && $request->filled('email')) {
            return (string) $request->input('email');
        }

        return $this->extractLabelFromResponse($response)
            ?? $this->lookupTargetLabel($classified['target_type'], $classified['target_id'])
            ?? $this->extractTargetLabelFromRequest($request);
    }

    protected function extractLabelFromResponse(Response $response): ?string
    {
        $contentType = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'json')) {
            return null;
        }

        $body = json_decode((string) $response->getContent(), true);
        if (! is_array($body)) {
            return null;
        }

        $data = $body['data'] ?? null;
        if (! is_array($data)) {
            return null;
        }

        foreach (['name', 'title', 'template_name', 'salon_name', 'subject'] as $key) {
            if (! empty($data[$key]) && is_string($data[$key])) {
                return $this->truncate($data[$key]);
            }
        }

        if (! empty($data['salon']['name']) && is_string($data['salon']['name'])) {
            return $this->truncate($data['salon']['name']);
        }

        return null;
    }

    protected function lookupTargetLabel(string $targetType, ?string $targetId): ?string
    {
        if (! $targetId) {
            return null;
        }

        try {
            $label = match ($targetType) {
                'salon' => \App\Models\Salon::query()->whereKey($targetId)->value('name'),
                'user', 'owner', 'customer' => \App\Models\User::query()->whereKey($targetId)->value('name'),
                'service' => \App\Models\Service::query()->whereKey($targetId)->value('name'),
                'staff' => \App\Models\Staff::query()->whereKey($targetId)->value('name'),
                'package' => \App\Models\Package::query()->whereKey($targetId)->value('name'),
                'email_template' => \App\Models\EmailTemplate::query()->whereKey($targetId)->value('template_name'),
                'payment_instruction' => \App\Models\PaymentInstruction::query()->whereKey($targetId)->value('title'),
                'booking' => \App\Models\Booking::query()->whereKey($targetId)
                    ->with('salon:id,name')->first()?->salon?->name,
                default => null,
            };
        } catch (\Throwable) {
            return null;
        }

        return is_string($label) && $label !== '' ? $this->truncate($label) : null;
    }

    protected function extractTargetLabelFromRequest(Request $request): ?string
    {
        foreach (['name', 'title', 'template_name', 'salon_name', 'email'] as $key) {
            $value = $request->input($key);
            if (is_string($value) && trim($value) !== '') {
                return $this->truncate($value);
            }
        }

        return null;
    }

    protected function truncate(string $value, int $limit = 120): string
    {
        $value = trim($value);

        return strlen($value) > $limit ? substr($value, 0, $limit).'…' : $value;
    }
}
