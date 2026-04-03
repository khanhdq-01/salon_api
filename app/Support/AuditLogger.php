<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuditLogger
{
    public static function log(
        string $action,
        string $targetType,
        ?string $targetId = null,
        string $status = 'success',
        array $details = [],
        ?string $userId = null,
    ): AuditLog {
        $request = request();
        $classified = AuditLogClassifier::classifyManualLog($action, $targetType, $details);

        $log = new AuditLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'status' => $status,
            'ip_address' => $request instanceof Request ? $request->ip() : null,
            'created_at' => now(),
        ]);

        $mergedDetails = array_merge($details, [
            'module' => $classified['module'],
            'action_category' => $classified['action_category'],
        ]);

        if ($mergedDetails !== []) {
            $log->details = SensitiveDataRedactor::redact($mergedDetails);
        }

        $log->save();

        self::markRecorded($request);

        return $log;
    }

    public static function markRecorded(?Request $request = null): void
    {
        $request ??= request();

        if ($request instanceof Request) {
            $request->attributes->set('audit_log_recorded', true);
        }
    }
}
