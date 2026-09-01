<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $referralCode = $request->query('ref');
        return view('auth.register', compact('referralCode'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'referral_code.exists' => 'El código de referido ingresado no es válido.',
        ]);

        $sponsorId = null;
        if ($request->filled('referral_code')) {
            $sponsor = User::where('referral_code', $request->referral_code)->first();
            if ($sponsor) {
                $sponsorId = $sponsor->id;
            }
        }

        // Generar código de referido único para el nuevo usuario
        do {
            $newReferralCode = 'VIP' . strtoupper(Str::random(6));
        } while (User::where('referral_code', $newReferralCode)->exists());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'cliente',
            'balance' => 0.00,
            'referral_code' => $newReferralCode,
            'referred_by' => $sponsorId,
            'status' => 'active',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
