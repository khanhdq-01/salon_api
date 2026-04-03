<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Canonical classification for audit log entries (module, action, target, portal).
 */
final class AuditLogClassifier
{
    /** @var list<string> */
    private const PORTAL_PREFIXES = ['admin', 'owner', 'customer', 'staff'];

    /** @var list<string> Compound path prefixes — longest first */
    private const COMPOUND_RESOURCES = [
        'owner/work-schedules',
        'owner/notifications',
        'customer/notifications',
        'customer/favorites/hairstyles',
        'customer/favorites/salons',
        'customer/search-history',
        'email-templates',
        'payment-instructions',
        'review-reports',
        'style-options',
        'revenue-analytics',
        'audit-logs',
        'work-schedules',
        'search-history',
        'favorites/hairstyles',
        'favorites/salons',
    ];

    /** @var list<string> */
    private const LIST_RESOURCES = [
        'users', 'owners', 'salons', 'bookings', 'services', 'staff', 'packages',
        'subscriptions', 'reviews', 'review-reports', 'notifications', 'audit-logs',
        'email-templates', 'payment-instructions', 'style-options', 'owner/notifications',
        'owner/work-schedules', 'customer/favorites/salons', 'customer/favorites/hairstyles',
        'customer/search-history', 'trending/hairstyles',
    ];

    /** @var list<string> */
    private const VIEW_RESOURCES = [
        'dashboard', 'reports', 'report', 'profile', 'settings', 'subscription',
        'payment-instructions', 'salon', 'owner/salon', 'revenue-analytics',
    ];

    /** @var list<string> Verb segments treated as actions on PATCH/POST/DELETE */
    private const ACTION_VERBS = [
        'login', 'logout', 'register', 'approve', 'reject', 'lock', 'unlock',
        'cancel', 'reschedule', 'confirm', 'complete', 'activate', 'deactivate',
        'restore', 'hide', 'show', 'resolve', 'broadcast', 'transfer', 'upgrade',
        'refund', 'export', 'read', 'approve-all', 'approve_all',
    ];

    /** @var array<string, array{target: string, module: string}> */
    private const SUB_RESOURCE_MAP = [
        'reviews' => ['target' => 'review', 'module' => 'notification'],
        'hairstyles' => ['target' => 'style_option', 'module' => 'service'],
        'images' => ['target' => 'salon', 'module' => 'salon'],
        'payment' => ['target' => 'payment', 'module' => 'payment'],
        'history' => ['target' => 'notification', 'module' => 'notification'],
        'calendar' => ['target' => 'report', 'module' => 'staff'],
        'pending' => ['target' => 'staff', 'module' => 'staff'],
        'plans' => ['target' => 'subscription', 'module' => 'subscription'],
        'schedules' => ['target' => 'staff', 'module' => 'staff'],
        'work-schedules' => ['target' => 'staff', 'module' => 'staff'],
        'available-slots' => ['target' => 'booking', 'module' => 'booking'],
        'shop' => ['target' => 'notification', 'module' => 'notification'],
        'popular' => ['target' => 'service', 'module' => 'service'],
        'search' => ['target' => 'salon', 'module' => 'salon'],
    ];

    /** @var array<string, string> */
    private const RESOURCE_TARGET_MAP = [
        'users' => 'user',
        'user' => 'user',
        'owners' => 'owner',
        'owner' => 'owner',
        'salons' => 'salon',
        'salon' => 'salon',
        'bookings' => 'booking',
        'booking' => 'booking',
        'services' => 'service',
        'service' => 'service',
        'staff' => 'staff',
        'packages' => 'package',
        'package' => 'package',
        'subscriptions' => 'subscription',
        'subscription' => 'subscription',
        'reviews' => 'review',
        'review' => 'review',
        'review-reports' => 'review',
        'review_reports' => 'review',
        'notifications' => 'notification',
        'notification' => 'notification',
        'settings' => 'settings',
        'audit-logs' => 'audit_log',
        'audit_logs' => 'audit_log',
        'email-templates' => 'email_template',
        'email_templates' => 'email_template',
        'payment-instructions' => 'payment_instruction',
        'payment_instructions' => 'payment_instruction',
        'style-options' => 'style_option',
        'style_options' => 'style_option',
        'dashboard' => 'dashboard',
        'reports' => 'report',
        'report' => 'report',
        'revenue-analytics' => 'report',
        'revenue_analytics' => 'report',
        'payments' => 'payment',
        'payment' => 'payment',
        'uploads' => 'upload',
        'upload' => 'upload',
        'profile' => 'profile',
        'trending' => 'report',
        'trending/hairstyles' => 'style_option',
        'favorites' => 'favorite',
        'favorites/salons' => 'favorite',
        'favorites/hairstyles' => 'favorite',
        'customer/favorites/salons' => 'favorite',
        'customer/favorites/hairstyles' => 'favorite',
        'customer/search-history' => 'customer',
        'search-history' => 'customer',
        'owner/notifications' => 'notification',
        'owner/work-schedules' => 'staff',
        'customer/notifications' => 'notification',
        'work-schedules' => 'staff',
        'auth' => 'auth',
    ];

    /** @var array<string, string> */
    private const RESOURCE_MODULE_MAP = [
        'users' => 'user',
        'user' => 'user',
        'owners' => 'user',
        'owner' => 'user',
        'salons' => 'salon',
        'salon' => 'salon',
        'bookings' => 'booking',
        'booking' => 'booking',
        'services' => 'service',
        'service' => 'service',
        'staff' => 'staff',
        'packages' => 'subscription',
        'package' => 'subscription',
        'subscriptions' => 'subscription',
        'subscription' => 'subscription',
        'reviews' => 'notification',
        'review' => 'notification',
        'review-reports' => 'notification',
        'review_reports' => 'notification',
        'notifications' => 'notification',
        'notification' => 'notification',
        'settings' => 'settings',
        'audit-logs' => 'settings',
        'audit_logs' => 'settings',
        'email-templates' => 'settings',
        'email_templates' => 'settings',
        'payment-instructions' => 'payment',
        'payment_instructions' => 'payment',
        'payments' => 'payment',
        'payment' => 'payment',
        'dashboard' => 'settings',
        'reports' => 'settings',
        'report' => 'settings',
        'revenue-analytics' => 'settings',
        'revenue_analytics' => 'settings',
        'style-options' => 'service',
        'style_options' => 'service',
        'uploads' => 'settings',
        'upload' => 'settings',
        'profile' => 'user',
        'trending' => 'customer',
        'trending/hairstyles' => 'customer',
        'favorites' => 'customer',
        'favorites/salons' => 'customer',
        'favorites/hairstyles' => 'customer',
        'customer/favorites/salons' => 'customer',
        'customer/favorites/hairstyles' => 'customer',
        'customer/search-history' => 'customer',
        'search-history' => 'customer',
        'owner/notifications' => 'notification',
        'owner/work-schedules' => 'staff',
        'customer/notifications' => 'notification',
        'work-schedules' => 'staff',
        'auth' => 'user',
    ];

    /**
     * @return array{
     *     action: string,
     *     target_type: string,
     *     target_id: ?string,
     *     module: string,
     *     portal: ?string,
     *     resource: string,
     *     sub_action: ?string,
     *     action_category: string,
     * }
     */
    public static function classifyRequest(Request $request): array
    {
        $path = self::normalizePath($request->path());
        $method = strtoupper($request->method());

        if (str_starts_with($path, 'auth/')) {
            return self::classifyAuth($request, $path, $method);
        }

        if ($path === 'profile' || str_starts_with($path, 'profile/')) {
            return self::build(
                action: self::httpAction($method, false, true),
                targetType: 'profile',
                targetId: $request->user()?->id,
                module: self::moduleForPortal($request->user()?->role?->name ?? 'customer'),
                portal: $request->user()?->role?->name,
                resource: 'profile',
            );
        }

        [$portal, $remainder] = self::extractPortal($path, $request);
        [$resource, $tail] = self::extractResource($remainder);

        $targetType = self::targetTypeForResource($resource);
        $module = self::moduleForResource($resource, $portal, $request->user()?->role?->name);
        [$targetId, $subAction, $nestedId, $nestedResource] = self::parseTail($tail);

        if ($nestedResource !== null && isset(self::SUB_RESOURCE_MAP[$nestedResource])) {
            $subMeta = self::SUB_RESOURCE_MAP[$nestedResource];
            $targetType = $subMeta['target'];
            $module = $subMeta['module'];
            $targetId = $nestedId ?? $targetId;
            $action = self::httpAction($method, $nestedId === null && $method === 'GET', false, $subAction);

            return self::build(
                action: $action,
                targetType: $targetType,
                targetId: $targetId,
                module: $module,
                portal: $portal,
                resource: $resource.'/'.$nestedResource,
                subAction: $subAction,
            );
        }

        if ($subAction !== null && isset(self::SUB_RESOURCE_MAP[$subAction])) {
            $subMeta = self::SUB_RESOURCE_MAP[$subAction];
            $targetType = $subMeta['target'];
            $module = $portal === 'customer' && $subAction === 'hairstyles'
                ? 'customer'
                : $subMeta['module'];
            $isList = $method === 'GET' && $nestedId === null && $targetId !== null;

            return self::build(
                action: self::httpAction($method, $isList, false, $subAction),
                targetType: $targetType,
                targetId: $nestedId ?? $targetId,
                module: $module,
                portal: $portal,
                resource: $resource,
                subAction: $subAction,
            );
        }

        if ($subAction !== null && self::isActionVerb($subAction)) {
            return self::build(
                action: self::normalizeVerb($subAction),
                targetType: $targetType,
                targetId: $targetId,
                module: $module,
                portal: $portal,
                resource: $resource,
                subAction: $subAction,
            );
        }

        $isList = self::isListRequest($method, $resource, $targetId, $subAction);

        return self::build(
            action: self::httpAction($method, $isList, self::isViewResource($resource), $subAction),
            targetType: $targetType,
            targetId: $targetId,
            module: $module,
            portal: $portal,
            resource: $resource,
            subAction: $subAction,
        );
    }

    public static function classifyManualLog(string $action, string $targetType, array $details = []): array
    {
        $resource = (string) ($details['resource'] ?? '');
        $portal = (string) ($details['portal'] ?? '');
        $module = $resource !== ''
            ? self::moduleForResource($resource, $portal ?: null, $portal ?: null)
            : self::moduleForTargetType($targetType, $portal);

        return [
            'action' => $action,
            'action_category' => AuditLogPresenter::normalizeActionCategory($action),
            'module' => $module,
        ];
    }

    /**
     * @return array{action: string, target_type: string, target_id: ?string, module: string, portal: ?string, resource: string, sub_action: ?string, action_category: string}
     */
    private static function build(
        string $action,
        string $targetType,
        ?string $targetId,
        string $module,
        ?string $portal,
        string $resource,
        ?string $subAction = null,
    ): array {
        return [
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'module' => $module,
            'portal' => $portal,
            'resource' => $resource,
            'sub_action' => $subAction,
            'action_category' => AuditLogPresenter::normalizeActionCategory($action),
        ];
    }

    /** @return array{0: ?string, 1: string} */
    private static function extractPortal(string $path, Request $request): array
    {
        foreach (self::PORTAL_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                $remainder = $path === $prefix ? '' : substr($path, strlen($prefix) + 1);

                return [$prefix, $remainder];
            }
        }

        $role = $request->user()?->role?->name;

        return [$role, $path];
    }

    /** @return array{0: string, 1: string} */
    private static function extractResource(string $path): array
    {
        if ($path === '') {
            return ['system', ''];
        }

        foreach (self::sortedCompoundResources() as $compound) {
            if ($path === $compound || str_starts_with($path, $compound.'/')) {
                $tail = $path === $compound ? '' : substr($path, strlen($compound) + 1);

                return [$compound, $tail];
            }
        }

        $slash = strpos($path, '/');
        if ($slash === false) {
            return [$path, ''];
        }

        return [substr($path, 0, $slash), substr($path, $slash + 1)];
    }

    /**
     * @return array{0: ?string, 1: ?string, 2: ?string, 3: ?string}
     */
    private static function parseTail(string $tail): array
    {
        if ($tail === '') {
            return [null, null, null, null];
        }

        $segments = array_values(array_filter(explode('/', $tail), fn ($s) => $s !== ''));

        $targetId = null;
        $subAction = null;
        $nestedId = null;
        $nestedResource = null;

        if (isset($segments[0]) && self::looksLikeId($segments[0])) {
            $targetId = array_shift($segments);
        }

        if (isset($segments[0]) && ! self::looksLikeId($segments[0])) {
            $subAction = self::normalizeSegment(array_shift($segments));
        }

        if (isset($segments[0]) && ! self::looksLikeId($segments[0]) && isset(self::SUB_RESOURCE_MAP[$segments[0]])) {
            $nestedResource = self::normalizeSegment(array_shift($segments));
        }

        if (isset($segments[0]) && self::looksLikeId($segments[0])) {
            $nestedId = array_shift($segments);
        }

        if (isset($segments[0]) && ! self::looksLikeId($segments[0]) && self::isActionVerb($segments[0])) {
            $subAction = self::normalizeSegment(array_shift($segments));
        }

        return [$targetId, $subAction, $nestedId, $nestedResource];
    }

    private static function classifyAuth(Request $request, string $path, string $method): array
    {
        $route = trim(str_replace('auth/', '', $path), '/');
        $portal = $request->input('portal') ?? $request->user()?->role?->name;

        $action = match ($route) {
            'login' => 'login',
            'register' => 'register',
            'logout', 'logout-all' => 'logout',
            'change-password' => 'change_password',
            'reset-password' => 'reset_password',
            'forgot-password' => 'forgot_password',
            'email/verify' => 'verify_email',
            'email/resend' => 'resend_verification_email',
            default => strtoupper($method) === 'POST' ? 'create' : 'view',
        };

        return self::build(
            action: $action,
            targetType: 'auth',
            targetId: $request->user()?->id,
            module: self::moduleForPortal((string) $portal),
            portal: is_string($portal) ? $portal : null,
            resource: 'auth',
        );
    }

    private static function httpAction(string $method, bool $isList, bool $isView, ?string $subAction): string
    {
        if ($subAction !== null && self::isActionVerb($subAction)) {
            return self::normalizeVerb($subAction);
        }

        return match ($method) {
            'GET' => $isList ? 'list' : 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => strtolower($method),
        };
    }

    private static function isListRequest(string $method, string $resource, ?string $targetId, ?string $subAction): bool
    {
        if ($method !== 'GET' || $targetId !== null || $subAction !== null) {
            return false;
        }

        if (self::isViewResource($resource)) {
            return false;
        }

        $normalized = str_replace('_', '-', strtolower($resource));

        return in_array($normalized, self::LIST_RESOURCES, true)
            || in_array(str_replace('-', '_', $normalized), self::LIST_RESOURCES, true);
    }

    private static function isViewResource(string $resource): bool
    {
        $normalized = str_replace('_', '-', strtolower($resource));

        return in_array($normalized, self::VIEW_RESOURCES, true)
            || in_array(str_replace('-', '_', $normalized), self::VIEW_RESOURCES, true);
    }

    private static function targetTypeForResource(string $resource): string
    {
        $key = str_replace('_', '-', strtolower($resource));

        return self::RESOURCE_TARGET_MAP[$key]
            ?? self::RESOURCE_TARGET_MAP[str_replace('-', '_', $key)]
            ?? str_replace('-', '_', $key);
    }

    private static function moduleForResource(string $resource, ?string $portal, ?string $role): string
    {
        $key = str_replace('_', '-', strtolower($resource));

        if (isset(self::RESOURCE_MODULE_MAP[$key])) {
            $module = self::RESOURCE_MODULE_MAP[$key];

            if ($portal === 'customer' || $role === 'customer') {
                return self::customerModuleOverride($key, $module);
            }

            return $module;
        }

        $underscore = str_replace('-', '_', $key);
        if (isset(self::RESOURCE_MODULE_MAP[$underscore])) {
            return self::RESOURCE_MODULE_MAP[$underscore];
        }

        return self::moduleForPortal($portal ?? $role);
    }

    private static function customerModuleOverride(string $resource, string $defaultModule): string
    {
        return match ($resource) {
            'salons', 'salon', 'trending', 'trending/hairstyles', 'favorites', 'favorites/salons',
            'favorites/hairstyles', 'customer/favorites/salons', 'customer/favorites/hairstyles',
            'customer/search-history', 'search-history', 'bookings', 'booking', 'profile' => 'customer',
            default => $defaultModule,
        };
    }

    private static function moduleForTargetType(string $targetType, string $portal = ''): string
    {
        foreach (AuditLogPresenter::moduleTargetsMap() as $module => $types) {
            if (in_array($targetType, $types, true)) {
                return $module;
            }
        }

        return self::moduleForPortal($portal);
    }

    private static function moduleForPortal(?string $portal): string
    {
        return match ($portal) {
            'customer' => 'customer',
            'staff' => 'staff',
            'owner', 'admin' => 'user',
            default => 'other',
        };
    }

    /** @return list<string> */
    private static function sortedCompoundResources(): array
    {
        $resources = self::COMPOUND_RESOURCES;
        usort($resources, fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        return $resources;
    }

    private static function normalizePath(string $path): string
    {
        $path = trim(strtolower($path), '/');
        $segments = array_values(array_filter(explode('/', $path)));

        if (($segments[0] ?? '') === 'api') {
            array_shift($segments);
        }

        if (($segments[0] ?? '') === 'v1') {
            array_shift($segments);
        }

        return implode('/', $segments);
    }

    private static function normalizeSegment(string $segment): string
    {
        return strtolower(str_replace('-', '_', $segment));
    }

    private static function normalizeVerb(string $verb): string
    {
        return strtolower(str_replace('-', '_', $verb));
    }

    private static function isActionVerb(string $segment): bool
    {
        $normalized = self::normalizeVerb($segment);

        return in_array($normalized, self::ACTION_VERBS, true)
            || in_array(str_replace('_', '-', $normalized), self::ACTION_VERBS, true);
    }

    private static function looksLikeId(string $value): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value)
            || ctype_digit($value);
    }
}
