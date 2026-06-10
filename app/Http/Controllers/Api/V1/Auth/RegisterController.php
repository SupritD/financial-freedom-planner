<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Actions\RegisterUserAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterUserAction $registerUserAction
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tenant_id' => 'nullable|uuid|exists:tenants,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->registerUserAction->execute($validator->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
