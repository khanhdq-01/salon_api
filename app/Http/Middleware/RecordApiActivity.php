<?php

namespace App\Http\Middleware;

use App\Support\ApiActivityRecorder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordApiActivity
{
    public function __construct(
        protected ApiActivityRecorder $recorder,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        try {
            $this->recorder->record($request, $response);
        } catch (\Throwable) {
            // Activity logging must not break API responses.
        }

        return $response;
    }
}
