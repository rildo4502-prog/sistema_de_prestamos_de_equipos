<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolEncargado
{
    private const ROLES_AUTORIZADOS = [
        User::ROL_ENCARGADO,
        'administrador',
    ];

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $usuario = $request->user();

            if (! $usuario) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
            }

            if (! $this->esEncargado($usuario)) {
                return redirect()
                    ->route('panel')
                    ->with('error', 'No tienes permisos de Encargado para acceder a esta sección.');
            }
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('panel')
                ->with('error', 'No se pudo verificar tus permisos en este momento. Inténtalo nuevamente.');
        }

        return $next($request);
    }

    private function esEncargado(User $usuario): bool
    {
        return in_array($usuario->rol, self::ROLES_AUTORIZADOS, true);
    }
}
