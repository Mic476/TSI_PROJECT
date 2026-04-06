<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Route;

class HomeRedirectService
{
    public function resolveHomePath(?User $user): string
    {
        $roles = $user ? ',' . str_replace(' ', '', strtolower((string) $user->idroles)) . ',' : '';

        if ((str_contains($roles, ',headxx,') || str_contains($roles, ',head,')) && $this->routeUriExists('pengajuan')) {
            return 'pengajuan';
        }

        if ((str_contains($roles, ',hrdxxx,') || str_contains($roles, ',hrd,')) && $this->routeUriExists('dashboard-hrd')) {
            return 'dashboard-hrd';
        }

        return 'dashboard';
    }

    private function routeUriExists(string $uri): bool
    {
        foreach (Route::getRoutes() as $route) {
            if ($route->uri() === $uri) {
                return true;
            }
        }

        return false;
    }
}