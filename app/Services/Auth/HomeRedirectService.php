<?php

namespace App\Services\Auth;

use App\Models\User;

class HomeRedirectService
{
    public function resolveHomePath(?User $user): string
    {
        $roles = $user ? ',' . str_replace(' ', '', strtolower((string) $user->idroles)) . ',' : '';

        if (str_contains($roles, ',headxx,') || str_contains($roles, ',head,')) {
            return 'pengajuan';
        }

        if (str_contains($roles, ',hrdxxx,') || str_contains($roles, ',hrd,')) {
            return 'dashboard-hrd';
        }

        return 'dashboard';
    }
}