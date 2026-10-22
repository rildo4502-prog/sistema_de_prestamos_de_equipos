<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de seguridad: sólo el Encargado (o Administrador) puede entrar
 * a las secciones de administración (equipos, préstamos y usuarios).
 *
 * Uso en las rutas:
 *     Route::middleware(['auth', 'rol.encargado'])->group(...)
 */
class RolEncargado
{
    /**
     * Roles con permisos de administración.
     *
     * 'administrador' queda preparado para cuando el equipo amplíe el enum
     * de la columna users.rol (ver database/migrations/..._add_rol_to_users_table.php).
     *
     * @var list<string>
     */
    private const ROLES_AUTORIZADOS = [
        User::ROL_ENCARGADO,
        'administrador',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $usuario = $request->user();

            // 1) Sin sesión: se manda al login.
            if (! $usuario) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
            }

            // 2) Con sesión pero sin el rol: se devuelve al panel con un aviso.
            if (! $this->esEncargado($usuario)) {
                return redirect()
                    ->route('panel')
                    ->with('error', 'No tienes permisos de Encargado para acceder a esta sección.');
            }
        } catch (\Throwable $e) {
            // Si falla la verificación de permisos no se muestra la traza al
            // usuario: se registra en el log y se responde con un aviso.
            report($e);

            return redirect()
                ->route('panel')
                ->with('error', 'No se pudo verificar tus permisos en este momento. Inténtalo nuevamente.');
        }

        // 3) Permisos correctos: la petición sigue su curso normal.
        return $next($request);
    }

    /**
     * Determina si el usuario tiene permisos de administración.
     */
    private function esEncargado(User $usuario): bool
    {
        return in_array($usuario->rol, self::ROLES_AUTORIZADOS, true);
    }
}
