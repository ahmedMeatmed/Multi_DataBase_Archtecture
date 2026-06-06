<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login using email or phone and return a personal access token.
     * Assumes User model uses HasApiTokens (createToken) and a `phone` column exists.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // email or phone
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');

        $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => ['The provided credentials are incorrect.'],
            ]);
        }

        // create token (requires HasApiTokens on User)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout by deleting the current access token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $request->bearerToken()) {
            $current = $request->user()->currentAccessToken();
            if ($current) {
                $current->delete();
            }
        }

        return response()->json(['message' => 'Logged out.']);
    }
}
