<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WritingLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() === null) {
            return $next($request);
        }
        $user = $request->user();
        $get_log = [];
        if ($user !== null) {
            $get_log['user'] = $user->email;
            $get_log += ['request' => $request->getContent()];
            $get_log['host'] = $request->getMethod();
        }

        Log::channel('my_logging')->info($get_log);

        return $next($request);
    }
}
