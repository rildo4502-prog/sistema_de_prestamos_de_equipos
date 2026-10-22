<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    private const MAX_INTENTOS = 5;

    private const SEGUNDOS_BLOQUEO = 60;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $clave = 'login|'.$request->ip().'|'.$credenciales['email'];

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

                throw ValidationException::withMessages([
                    'email' => 'Las credenciales ingresadas no son válidas.',
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'No se pudo iniciar sesión en este momento. Inténtalo nuevamente.');
        }

        RateLimiter::clear($clave);

        $request->session()->regenerate();

        return redirect()
            ->intended(route('panel'))
            ->with('success', 'Sesión iniciada correctamente.');
    }

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
