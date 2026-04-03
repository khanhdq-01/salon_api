<?php

namespace App\Services\Owner;

use App\Models\Service;
use App\Repositories\Interfaces\Owner\ServiceStyleOptionRepositoryInterface;
use App\Support\HtmlSanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceStyleOptionSyncService
{
    public function __construct(
        protected ServiceStyleOptionRepositoryInterface $styleOptionRepository,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $options
     */
    public function sync(Service $service, array $options): void
    {
        DB::transaction(function () use ($service, $options) {
            $existingIds = $service->styleOptions()->pluck('id')->all();
            $keptIds = [];

            foreach (array_values($options) as $index => $option) {
                $payload = [
                    'name' => trim((string) ($option['name'] ?? '')),
                    'gender' => in_array($option['gender'] ?? 'unisex', ['male', 'female', 'unisex'], true)
                        ? ($option['gender'] ?? 'unisex')
                        : 'unisex',
                    'description' => HtmlSanitizer::plainText($option['description'] ?? null),
                    'article' => HtmlSanitizer::richHtml($option['article'] ?? null),
                    'extra_price' => max(0, (int) ($option['extra_price'] ?? 0)),
                    'extra_duration' => max(0, (int) ($option['extra_duration'] ?? 0)),
                    'image' => filled($option['image'] ?? null) ? (string) $option['image'] : null,
                    'sort_order' => (int) ($option['sort_order'] ?? $index),
                    'is_active' => array_key_exists('is_active', $option)
                        ? (bool) $option['is_active']
                        : true,
                    'is_featured' => (bool) ($option['is_featured'] ?? false),
                ];

                if (! empty($option['id']) && in_array($option['id'], $existingIds, true)) {
                    $this->styleOptionRepository->updateByServiceAndId($service->id, $option['id'], $payload);
                    $keptIds[] = $option['id'];
                    continue;
                }

                $created = $this->styleOptionRepository->create([
                    'id' => (string) Str::uuid(),
                    'service_id' => $service->id,
                    ...$payload,
                ]);
                $keptIds[] = $created->id;
            }

            if ($keptIds !== []) {
                $service->styleOptions()->whereNotIn('id', $keptIds)->delete();
            } else {
                $service->styleOptions()->delete();
            }
        });
    }
}
