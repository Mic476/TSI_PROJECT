<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $publicStoragePath = public_path('storage');
        $appStoragePublicPath = storage_path('app/public');

        if (!is_dir($appStoragePublicPath)) {
            return;
        }

        if (!is_dir($publicStoragePath)) {
            @mkdir($publicStoragePath, 0755, true);
        }

        // Fallback for environments where public/storage is not a symlink.
        if (is_dir($publicStoragePath) && !is_link($publicStoragePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($appStoragePublicPath, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                $sourcePath = $item->getPathname();
                $relativePath = substr($sourcePath, strlen($appStoragePublicPath) + 1);
                $targetPath = $publicStoragePath . DIRECTORY_SEPARATOR . $relativePath;

                if ($item->isDir()) {
                    if (!is_dir($targetPath)) {
                        @mkdir($targetPath, 0755, true);
                    }
                    continue;
                }

                if (!file_exists($targetPath)) {
                    @copy($sourcePath, $targetPath);
                }
            }
        }
    }
}
