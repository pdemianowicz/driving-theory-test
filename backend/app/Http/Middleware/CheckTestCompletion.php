<?php
namespace App\Http\Middleware;

use App\Models\TestSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uuid        = $request->route('uuid'); // Pobierz UUID z routingu
        $testSession = TestSession::where('uuid', $uuid)->first();

        if ($testSession->is_completed) {
            return response()->json([
                'message'  => 'Test został zakończony',
                'redirect' => '/',
            ], 302);
        }

        return $next($request); // Kontynuuj, jeśli test nie został zakończony
    }
}
