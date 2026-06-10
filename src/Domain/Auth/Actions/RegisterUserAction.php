<?php

namespace Domain\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'tenant_id' => $data['tenant_id'] ?? null,
                'data_residency_region' => 'IN'
            ]);

            // Typically dispatch a Domain Event here: UserRegistered
            // event(new UserRegistered($user));

            return $user;
        });
    }
}
