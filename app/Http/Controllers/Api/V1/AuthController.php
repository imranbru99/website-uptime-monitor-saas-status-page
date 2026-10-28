<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function token(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'token_name' => 'nullable|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'error' => 'invalid_credentials',
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'error' => 'account_inactive',
                'message' => 'Your user account has been disabled.',
            ], 403);
        }

        $tokenName = $validated['token_name'] ?? 'api_token';
        $token = $user->createToken($tokenName, ['*']);

        return response()->json([
            'status' => 'success',
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'tenants' => $user->tenants->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'slug' => $t->slug,
                    'role' => $t->pivot->role,
                ]),
            ],
        ]);
    }
}
