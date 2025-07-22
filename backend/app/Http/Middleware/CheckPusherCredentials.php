<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPusherCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $pusherConfigured = filled(config('broadcasting.connections.pusher.key')) &&
            filled(config('broadcasting.connections.pusher.secret')) &&
            filled(config('broadcasting.connections.pusher.app_id'));
        if (!$pusherConfigured) {
            config(['broadcasting.default' => 'reverb']);
            Log::info('Broadcast driver switched to Reverb (Pusher credentials missing).');
        } else {
            Log::info('Broadcast driver set to Pusher.');
        }

        return $next($request);
    }
}
