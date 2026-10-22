<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Inicio y cierre de sesión (parte de seguridad / roles).
 */
class LoginController extends Controller
{
    /** Intentos permitidos antes de bloquear el acceso. */
    private const MAX_INTENTOS = 5;

    /** Segundos de bloqueo tras superar los intentos permitidos. */
    private const SEGUNDOS_BLOQUEO = 60;

    /**
     * Formulario de acceso.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function store(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $clave = 'login|'.$request->ip().'|'.$credenciales['email'];

        // Protección contra ataques de fuerza bruta.
        if (RateLimiter::tooManyAttempts($clave, self::MAX_INTENTOS)) {
            $segundos = RateLimiter::availableIn($clave);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos de acceso. Vuelve a intentarlo en {$segundos} segundos.",
            ]);
        }

        try {
            $accredited = Auth::attempt($credenciales, $request->boolean('remember'));

            if (! $accredited) {
                RateLimiter::hit($clave, self::SEGUNDOS_BLOQUEO);

                // Mensaje genérico: no revela si el correo existe o no.
                throw ValidationException::withMessages([
                    'email' => 'Las credenciales ingresadas no son válidas.',
                ]);
            }
        } catch (ValidationException $e) {
            // Los errores de validación se muestran en el formulario.
            throw $e;
        } catch (\Throwable $e) {
            // Fallo inesperado (base de datos caída, etc.): se registra la
            // traza en el log y se responde con un mensajeentendible.
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'No se pudo iniciar sesión en este momento. Inténtalo nuevamente.');
        }

        RateLimiter::clear($clave);

        // Se regenera la sesión para evitar el secuestro de sesión (fixation).
        $request->session()->regenerate();

        return redirect()
            ->intended(route('panel'))
            ->with('success', 'Sesión iniciada correctamente.');
    }

    /**
     * Cierra la sesión e invalida el token CSRF.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
