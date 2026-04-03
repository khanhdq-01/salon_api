<?php



namespace App\Http\Resources\Api\V1\Admin;



use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\JsonResource;



class AdminDashboardResource extends JsonResource

{

    public function toArray(Request $request): array

    {

        $data = is_array($this->resource) ? $this->resource : [];



        return [

            'system_overview' => $data['system_overview'] ?? [],

            'business_performance' => $data['business_performance'] ?? [],

            'operational_monitoring' => $data['operational_monitoring'] ?? [],

            // Backward compatibility.

            'stats' => $data['stats'] ?? [],

            'chart' => $data['chart'] ?? ['labels' => [], 'bookings' => [], 'revenue' => []],

            'alerts' => $data['alerts'] ?? [

                'pending_salons' => [],

                'locked_salons' => [],

                'suspended_users' => [],

                'failed_payments' => [],

                'owner_violations' => [],

            ],

            'recent_activity' => $data['recent_activity'] ?? [],

            'recent_activity_meta' => $data['recent_activity_meta'] ?? [

                'current_page' => 1,

                'last_page' => 1,

                'per_page' => 5,

                'total' => 0,

            ],

            'range' => $data['range'] ?? ['start_date' => '', 'end_date' => ''],

        ];

    }

}


