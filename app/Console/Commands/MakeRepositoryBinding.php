<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryBinding extends Command
{
    /**
     * php artisan make:repository-binding UserRepository
     *
     * Command ini otomatis mendaftarkan binding
     * Interface → Repository ke AppServiceProvider.
     */
    protected $signature = 'make:repository-binding
                            {name : Nama repository, contoh: UserRepository}';

    protected $description = 'Daftarkan binding Repository Interface ke AppServiceProvider secara otomatis';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');

        if (!str_ends_with($name, 'Repository')) {
            $name .= 'Repository';
        }

        $interface  = $name . 'Interface';
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (!$this->files->exists($providerPath)) {
            $this->components->error('AppServiceProvider.php tidak ditemukan!');
            return self::FAILURE;
        }

        $content = $this->files->get($providerPath);

        // Cek apakah binding sudah ada
        if (str_contains($content, $interface)) {
            $this->components->warn("Binding untuk {$interface} sudah ada di AppServiceProvider.");
            return self::SUCCESS;
        }

        // Baris binding yang akan ditambahkan
        $binding = $this->buildBindingLine($name, $interface);

        // Cari posisi register() method untuk menyisipkan binding
        $content = $this->insertBinding($content, $binding);

        if ($content === false) {
            $this->components->error('Gagal menemukan method register() di AppServiceProvider.');
            $this->newLine();
            $this->components->info('Tambahkan manual ke AppServiceProvider.php:');
            $this->line($binding);
            return self::FAILURE;
        }

        $this->files->put($providerPath, $content);
        $this->components->info("Binding ditambahkan ke AppServiceProvider:");
        $this->line("  <fg=cyan>{$interface}::class</> → <fg=green>{$name}::class</>");

        return self::SUCCESS;
    }

    private function buildBindingLine(string $name, string $interface): string
    {
        return "\n        \$this->app->bind(\n"
            . "            \\App\\Repositories\\Contracts\\{$interface}::class,\n"
            . "            \\App\\Repositories\\{$name}::class\n"
            . "        );";
    }

    private function insertBinding(string $content, string $binding): string|false
    {
        // Cari posisi setelah "public function register(): void\n    {"
        // atau "public function register()\n    {"
        $pattern = '/(public function register\(\)[^{]*\{)/';

        if (!preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            return false;
        }

        $insertPos = $matches[0][1] + strlen($matches[0][0]);

        return substr($content, 0, $insertPos)
            . $binding
            . substr($content, $insertPos);
    }
}