<?php

namespace App\Logging;

use App\Support\SensitiveDataRedactor;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

final class RedactSensitiveDataProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        return $record->with(
            context: SensitiveDataRedactor::redact($record->context),
            extra: SensitiveDataRedactor::redact($record->extra),
        );
    }
}
