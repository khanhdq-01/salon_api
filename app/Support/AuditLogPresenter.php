<?php

namespace App\Support;

use App\Models\AuditLog;

final class AuditLogPresenter
{
    private const MODULE_LABELS = [
        'booking' => 'Booking',
        'salon' => 'Salon',
        'service' => 'Service',
        'staff' => 'Staff',
        'customer' => 'Customer',
        'subscription' => 'Subscription',
        'payment' => 'Payment',
        'user' => 'User',
        'notification' => 'Notification',
        'settings' => 'Settings',
        'other' => 'Khác',
    ];

    /** @var array<string, list<string>> */
    private const MODULE_TARGET_TYPES = [
        'booking' => ['booking'],
        'salon' => ['salon'],
        'service' => ['service'],
        'staff' => ['staff'],
        'subscription' => ['subscription', 'package'],
        'payment' => ['payment', 'payment_instruction'],
        'user' => ['user', 'owner'],
        'notification' => ['notification', 'review', 'review_report'],
        'settings' => ['settings', 'email_template', 'audit_log', 'dashboard', 'report'],
    ];

    /** @var array<string, string> URL resource segment => module key */
    private const RESOURCE_MODULE_MAP = [
        'email-templates' => 'settings',
        'email_templates' => 'settings',
        'audit-logs' => 'settings',
        'audit_logs' => 'settings',
        'settings' => 'settings',
        'dashboard' => 'settings',
        'revenue-analytics' => 'settings',
        'revenue_analytics' => 'settings',
        'payment-instructions' => 'payment',
        'payment_instructions' => 'payment',
        'payments' => 'payment',
        'payment' => 'payment',
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
        'users' => 'user',
        'user' => 'user',
        'owners' => 'user',
        'owner' => 'user',
        'notifications' => 'notification',
        'notification' => 'notification',
        'reviews' => 'notification',
        'review' => 'notification',
        'review-reports' => 'notification',
        'review_reports' => 'notification',
    ];

    /** @var array<string, list<string>> */
    private const ACTION_FILTER_PATTERNS = [
        'create' => ['create', 'register', 'created %', '%created%', 'provisioned %'],
        'update' => ['update', 'updated %', '%updated%', 'change_password', 'reset_password'],
        'delete' => ['delete', 'deleted %', '%deleted%'],
        'view' => ['view'],
        'list' => ['list'],
        'login' => ['login'],
        'logout' => ['logout'],
        'cancel' => ['cancel', '%cancel%'],
        'reschedule' => ['reschedule', '%reschedule%'],
        'approve' => ['approve', '%approved%', 'approve_all', 'approve-all'],
        'reject' => ['reject', '%rejected%', '%reject%'],
        'lock' => ['lock', 'locked %', '% lock%'],
        'unlock' => ['unlock', '%unlock%'],
        'activate' => ['activate', '%activated%', 'activate %'],
        'deactivate' => ['deactivate', '%deactivated%'],
        'confirm' => ['confirm', '%confirmed%'],
        'complete' => ['complete', '%completed%'],
        'upload' => ['upload', '%upload%'],
        'broadcast' => ['broadcast', '%broadcast%'],
        'transfer' => ['transfer', '%transfer%'],
        'resolve' => ['resolve', '%resolved%'],
    ];

    private const ACTION_LABELS = [
        'view' => 'Xem',
        'list' => 'Xem danh sách',
        'create' => 'Tạo mới',
        'update' => 'Cập nhật',
        'delete' => 'Xóa',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'register' => 'Đăng ký',
        'approve' => 'Duyệt',
        'reject' => 'Từ chối',
        'lock' => 'Khóa',
        'unlock' => 'Mở khóa',
        'activate' => 'Kích hoạt',
        'deactivate' => 'Vô hiệu hóa',
        'confirm' => 'Xác nhận',
        'complete' => 'Hoàn thành',
        'cancel' => 'Hủy',
        'reschedule' => 'Đổi lịch',
        'reset_password' => 'Đặt lại mật khẩu',
        'change_password' => 'Đổi mật khẩu',
        'verify_email' => 'Xác thực email',
        'send_verification_email' => 'Gửi email xác thực',
        'resend_verification_email' => 'Gửi lại email xác thực',
        'forgot_password' => 'Yêu cầu quên mật khẩu',
        'transfer' => 'Chuyển quyền',
        'broadcast' => 'Gửi thông báo',
        'hide' => 'Ẩn',
        'show' => 'Hiển thị',
        'resolve' => 'Xử lý',
        'upload' => 'Tải lên',
    ];

    private const TARGET_LABELS = [
        'user' => 'người dùng',
        'owner' => 'chủ salon',
        'customer' => 'khách hàng',
        'salon' => 'salon',
        'booking' => 'lịch đặt',
        'service' => 'dịch vụ',
        'staff' => 'nhân viên',
        'package' => 'gói dịch vụ',
        'subscription' => 'đăng ký gói',
        'review' => 'đánh giá',
        'notification' => 'thông báo',
        'settings' => 'cài đặt hệ thống',
        'email_template' => 'mẫu email',
        'payment_instruction' => 'hướng dẫn thanh toán',
        'profile' => 'hồ sơ cá nhân',
        'style_option' => 'kiểu tóc',
        'payment' => 'thanh toán',
        'audit_log' => 'nhật ký hệ thống',
        'auth' => 'hệ thống',
        'report' => 'báo cáo',
        'dashboard' => 'tổng quan',
        'favorite' => 'mục yêu thích',
        'upload' => 'tệp tin',
    ];

    public static function actionLabel(string $action, ?string $category = null): string
    {
        $category = strtolower(trim((string) ($category ?? '')));
        if ($category !== '' && isset(self::ACTION_LABELS[$category])) {
            return self::ACTION_LABELS[$category];
        }

        $normalized = strtolower(trim(str_replace(' ', '_', $action)));

        if (isset(self::ACTION_LABELS[$normalized])) {
            return self::ACTION_LABELS[$normalized];
        }

        foreach (self::ACTION_LABELS as $key => $label) {
            if (str_contains($normalized, $key)) {
                return $label;
            }
        }

        return ucfirst(str_replace('_', ' ', $normalized));
    }

    public static function targetLabel(string $targetType): string
    {
        $normalized = strtolower(trim($targetType));

        return self::TARGET_LABELS[$normalized]
            ?? str_replace('_', ' ', $normalized);
    }

    public static function roleLabel(?string $roleName, ?string $displayName = null): string
    {
        if ($displayName) {
            return $displayName;
        }

        return match ($roleName) {
            'admin' => 'Quản trị viên',
            'owner' => 'Chủ salon',
            'staff' => 'Nhân viên',
            'customer' => 'Khách hàng',
            'system' => 'System',
            default => $roleName ? ucfirst($roleName) : 'System',
        };
    }

    public static function resolveActorName(AuditLog $log): string
    {
        $details = is_array($log->details) ? $log->details : [];

        return $log->user?->name
            ?? ($details['target_label'] ?? null)
            ?? 'System';
    }

    public static function moduleKey(AuditLog $log): string
    {
        $details = is_array($log->details) ? $log->details : [];

        if (! empty($details['module']) && is_string($details['module'])) {
            return strtolower($details['module']);
        }

        $portal = strtolower((string) ($details['portal'] ?? ''));

        $resource = strtolower(str_replace('_', '-', trim((string) ($details['resource'] ?? ''))));
        if ($resource !== '') {
            $module = self::RESOURCE_MODULE_MAP[$resource]
                ?? self::RESOURCE_MODULE_MAP[str_replace('-', '_', $resource)]
                ?? null;

            if ($module !== null) {
                if ($resource === 'auth') {
                    return self::moduleFromPortal($portal);
                }

                return $module;
            }
        }

        $path = strtolower((string) ($details['path'] ?? ''));
        if ($path !== '') {
            foreach (self::sortedResourceModuleKeys() as $resourceKey) {
                if (self::pathMatchesResource($path, $resourceKey)) {
                    return self::RESOURCE_MODULE_MAP[$resourceKey];
                }
            }
        }

        $targetType = strtolower((string) $log->target_type);

        if ($portal === 'customer' && ! in_array($targetType, ['user', 'owner', 'admin'], true)) {
            return 'customer';
        }

        foreach (self::MODULE_TARGET_TYPES as $module => $types) {
            if (in_array($targetType, $types, true)) {
                return $module;
            }
        }

        if ($targetType === 'auth') {
            return self::moduleFromPortal($portal);
        }

        if (in_array($targetType, ['profile', 'favorite'], true)) {
            return $portal === 'customer' ? 'customer' : 'user';
        }

        return 'other';
    }

    /** @return list<string> */
    private static function sortedResourceModuleKeys(): array
    {
        $keys = array_keys(self::RESOURCE_MODULE_MAP);
        usort($keys, fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        return $keys;
    }

    private static function moduleFromPortal(string $portal): string
    {
        return match ($portal) {
            'customer' => 'customer',
            'staff' => 'staff',
            'owner', 'admin' => 'user',
            default => 'user',
        };
    }

    private static function pathMatchesResource(string $path, string $resourceKey): bool
    {
        $quoted = preg_quote($resourceKey, '#');

        return (bool) preg_match('#/'.$quoted.'(?:/|$)#', $path);
    }

    public static function moduleLabel(string $module): string
    {
        $normalized = strtolower(trim($module));

        return self::MODULE_LABELS[$normalized] ?? ucfirst($normalized);
    }

    public static function normalizeActionCategory(string $action, ?AuditLog $log = null): string
    {
        $details = is_array($log?->details) ? $log->details : [];
        if (! empty($details['action_category']) && is_string($details['action_category'])) {
            return strtolower($details['action_category']);
        }

        $normalized = strtolower(trim(str_replace(' ', '_', $action)));

        $canonical = [
            'list', 'view', 'create', 'update', 'delete', 'login', 'logout', 'register',
            'cancel', 'reschedule', 'approve', 'reject', 'lock', 'unlock', 'activate',
            'deactivate', 'confirm', 'complete', 'change_password', 'reset_password',
            'verify_email', 'send_verification_email', 'resend_verification_email', 'forgot_password',
            'upload', 'broadcast', 'transfer', 'resolve', 'hide', 'show', 'upgrade', 'refund',
        ];

        if (in_array($normalized, $canonical, true)) {
            return $normalized;
        }

        foreach (self::ACTION_FILTER_PATTERNS as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (! str_contains($pattern, '%')) {
                    if ($normalized === strtolower($pattern)) {
                        return $category;
                    }

                    continue;
                }

                $regex = '/^'.str_replace('%', '.*', preg_quote($pattern, '/')).'$/i';
                if (preg_match($regex, $action) || preg_match($regex, $normalized)) {
                    return $category;
                }
            }
        }

        if (isset(self::ACTION_LABELS[$normalized])) {
            return $normalized;
        }

        return $normalized;
    }

    /** @return list<string> */
    public static function actionFilterPatterns(string $category): array
    {
        return self::ACTION_FILTER_PATTERNS[strtolower($category)] ?? [strtolower($category)];
    }

    public static function applyModuleFilter(\Illuminate\Database\Eloquent\Builder $query, string $module): void
    {
        $module = strtolower($module);

        $query->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($module) {
            match ($module) {
                'booking' => $builder->where('target_type', 'booking'),
                'salon' => $builder->where('target_type', 'salon'),
                'service' => $builder->where('target_type', 'service'),
                'staff' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->where('target_type', 'staff')
                        ->orWhere('details->portal', 'staff')
                        ->orWhere('details->module', 'staff')
                        ->orWhere('details->resource', 'like', '%work-schedules%');
                }),
                'subscription' => $builder->whereIn('target_type', ['subscription', 'package']),
                'notification' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->whereIn('target_type', ['notification', 'review', 'review_report'])
                        ->orWhere('details->resource', 'like', '%review%');
                }),
                'payment' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->whereIn('target_type', ['payment', 'payment_instruction'])
                        ->orWhereIn('details->resource', ['payment-instructions', 'payment_instructions', 'payments', 'payment']);
                }),
                'settings' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->whereIn('target_type', ['settings', 'email_template', 'audit_log', 'dashboard', 'report'])
                        ->orWhere('details->module', 'settings')
                        ->orWhereIn('details->resource', [
                            'email-templates',
                            'audit-logs',
                            'settings',
                            'dashboard',
                            'revenue-analytics',
                        ]);
                }),
                'user' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->whereIn('target_type', ['user', 'owner'])
                        ->orWhere(function (\Illuminate\Database\Eloquent\Builder $auth) {
                            $auth->where('target_type', 'auth')
                                ->where(function (\Illuminate\Database\Eloquent\Builder $portal) {
                                    $portal->whereIn('details->portal', ['admin', 'owner'])
                                        ->orWhereNull('details->portal');
                                });
                        });
                }),
                'customer' => $builder->where(function (\Illuminate\Database\Eloquent\Builder $inner) {
                    $inner->where('details->portal', 'customer')
                        ->orWhereIn('target_type', ['favorite', 'profile']);
                }),
                default => $builder->whereRaw('1 = 0'),
            };
        });
    }

    public static function applyActionFilter(\Illuminate\Database\Eloquent\Builder $query, string $action): void
    {
        $patterns = self::actionFilterPatterns($action);

        $query->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($pattern, '%')) {
                    $builder->orWhere('action', 'like', $pattern);
                } else {
                    $builder->orWhere('action', $pattern)
                        ->orWhere('action', 'like', $pattern.' %')
                        ->orWhere('action', 'like', '% '.$pattern.'%');
                }
            }
        });
    }

    public static function resolveRole(AuditLog $log): string
    {
        if ($log->user?->role) {
            return self::roleLabel(
                $log->user->role->name,
                $log->user->role->display_name,
            );
        }

        $details = is_array($log->details) ? $log->details : [];
        $portal = $details['portal'] ?? null;

        if ($portal) {
            return self::roleLabel((string) $portal);
        }

        return self::roleLabel('system');
    }

    /** @return array<string, list<string>> */
    public static function moduleTargetsMap(): array
    {
        return self::MODULE_TARGET_TYPES;
    }

    public static function message(AuditLog $log): string
    {
        $details = is_array($log->details) ? $log->details : [];
        $actor = $log->user?->name ?? ($details['target_label'] ?? 'Khách');
        $role = $log->user ? self::resolveRole($log) : self::roleLabel($details['portal'] ?? null);
        $actionKey = strtolower(trim(str_replace(' ', '_', $log->action)));
        $objectLabel = self::resolveObjectLabel($log);
        $failedSuffix = $log->status === 'failed' ? ' — thất bại' : '';

        if ($log->target_type === 'auth') {
            return match ($actionKey) {
                'login' => "{$actor} ({$role}) đã đăng nhập{$failedSuffix}",
                'logout' => "{$actor} ({$role}) đã đăng xuất{$failedSuffix}",
                'register' => "{$actor} ({$role}) đã đăng ký tài khoản{$failedSuffix}",
                'verify_email' => "{$actor} ({$role}) đã xác thực email{$failedSuffix}",
                'send_verification_email' => "{$actor} ({$role}) đã gửi email xác thực{$failedSuffix}",
                'resend_verification_email' => "{$actor} ({$role}) đã gửi lại email xác thực{$failedSuffix}",
                'forgot_password' => "{$actor} ({$role}) đã yêu cầu quên mật khẩu{$failedSuffix}",
                'change_password', 'reset_password' => "{$actor} ({$role}) đã ".self::actionLabel($actionKey)."{$failedSuffix}",
                default => "{$actor} ({$role}) ".self::actionLabel($actionKey).' '.self::targetLabel($log->target_type).$failedSuffix,
            };
        }

        if ($actionKey === 'list') {
            return "{$actor} ({$role}) xem danh sách ".self::targetLabel($log->target_type).$failedSuffix;
        }

        if ($actionKey === 'view') {
            $message = "{$actor} ({$role}) xem ".self::targetLabel($log->target_type);
            if ($objectLabel && ! self::looksLikeUuid($objectLabel)) {
                $message .= " \"{$objectLabel}\"";
            }

            return $message.$failedSuffix;
        }

        $message = "{$actor} ({$role}) ".self::actionLabel($log->action).' '.self::targetLabel($log->target_type);
        if ($objectLabel && ! self::looksLikeUuid($objectLabel)) {
            $message .= " \"{$objectLabel}\"";
        }

        return $message.$failedSuffix;
    }

    public static function resolveObjectLabel(AuditLog $log): ?string
    {
        $details = is_array($log->details) ? $log->details : [];

        foreach (['target_label', 'name', 'title', 'salon_name', 'email'] as $key) {
            if (! empty($details[$key])) {
                return (string) $details[$key];
            }
        }

        return $log->target_id;
    }

    private static function looksLikeUuid(string $value): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
    }
}
