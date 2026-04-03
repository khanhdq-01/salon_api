<?php

namespace App\Support;

use App\Exceptions\BusinessException;
use App\Models\Service;
use App\Models\ServiceStyleOption;
use Illuminate\Support\Collection;

final class ServiceStyleOptionResolver
{
    /**
     * @param  Collection<int, Service>  $services
     * @param  array<string, string|null>  $styleOptionMap
     * @return array{lines: list<array<string, mixed>>, total_price: int, total_duration: int}
     */
    public static function resolveLines(Collection $services, array $styleOptionMap = []): array
    {
        $optionIds = array_values(array_filter($styleOptionMap));
        $options = $optionIds === []
            ? collect()
            : ServiceStyleOption::query()
                ->active()
                ->whereIn('id', $optionIds)
                ->get()
                ->keyBy('id');

        $lines = [];
        $totalPrice = 0;
        $totalDuration = 0;

        foreach ($services->values() as $index => $service) {
            $price = (int) $service->price;
            $duration = (int) $service->duration_minutes;
            $styleOptionId = null;

            $selectedOptionId = $styleOptionMap[$service->id] ?? null;

            if ($selectedOptionId) {
                /** @var ServiceStyleOption|null $option */
                $option = $options->get($selectedOptionId);

                if (! $option || $option->service_id !== $service->id) {
                    throw new BusinessException('Kiểu tóc không hợp lệ hoặc không thuộc dịch vụ.', 'INVALID_STYLE_OPTION');
                }

                $price += (int) $option->extra_price;
                $duration += (int) $option->extra_duration;
                $styleOptionId = $option->id;
            }

            $lines[] = [
                'service_id' => $service->id,
                'service_style_option_id' => $styleOptionId,
                'price' => $price,
                'duration_minutes' => $duration,
                'sort_order' => $index,
            ];

            $totalPrice += $price;
            $totalDuration += $duration;
        }

        return [
            'lines' => $lines,
            'total_price' => $totalPrice,
            'total_duration' => $totalDuration,
        ];
    }
}
