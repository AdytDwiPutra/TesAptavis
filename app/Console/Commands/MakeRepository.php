<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    /**
     * php artisan make:repository UserRepository
     * php artisan make:repository UserRepository --model=User
     * php artisan make:repository UserRepository --model=User --no-interface
     */
    protected $signature = 'make:repository
                            {name : Nama repository, contoh: UserRepository}
                            {--model= : Nama model yang digunakan, contoh: User}
                            {--no-interface : Jangan buat Interface}';

    protected $description = 'Buat Repository class (beserta Interface) sesuai pola Repository Pattern';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');

        // Pastikan nama diakhiri "Repository"
        if (!str_ends_with($name, 'Repository')) {
            $name .= 'Repository';
        }

        $model     = $this->option('model') ?: str_replace('Repository', '', $name);
        $interface = $name . 'Interface';

        // 1. Buat Interface (kecuali --no-interface)
        if (!$this->option('no-interface')) {
            $this->generateInterface($interface);
        }

        // 2. Buat concrete Repository class
        $this->generateRepository($name, $interface, $model);

        // 3. Ingatkan user untuk binding di AppServiceProvider
        $this->newLine();
        $this->components->info('Jangan lupa tambahkan binding di AppServiceProvider:');
        $this->line("  <fg=cyan>\$this->app->bind(</>");
        $this->line("      <fg=yellow>\\App\\Repositories\\Contracts\\{$interface}::class,</>");
        $this->line("      <fg=yellow>\\App\\Repositories\\{$name}::class,</>");
        $this->line("  <fg=cyan>);</>");
        $this->newLine();

        return self::SUCCESS;
    }

    private function generateInterface(string $interface): void
    {
        $path = app_path("Repositories/Contracts/{$interface}.php");

        if ($this->files->exists($path)) {
            $this->components->warn("Interface sudah ada: {$path}");
            return;
        }

        $this->files->ensureDirectoryExists(app_path('Repositories/Contracts'));

        $stub = $this->getStub('repository.interface');
        $content = $this->replaceStub($stub, [
            '{{ namespace }}' => 'App\\Repositories\\Contracts',
            '{{ class }}'     => $interface,
        ]);

        $this->files->put($path, $content);
        $this->components->info("Interface dibuat: app/Repositories/Contracts/{$interface}.php");
    }

    private function generateRepository(string $name, string $interface, string $model): void
    {
        $path = app_path("Repositories/{$name}.php");

        if ($this->files->exists($path)) {
            $this->components->warn("Repository sudah ada: {$path}");
            return;
        }

        $this->files->ensureDirectoryExists(app_path('Repositories'));

        $stub = $this->getStub('repository');
        $content = $this->replaceStub($stub, [
            '{{ namespace }}'            => 'App\\Repositories',
            '{{ class }}'                => $name,
            '{{ interface }}'            => $interface,
            '{{ model }}'                => $model,
            '{{ model_namespace }}'      => 'App\\Models',
            '{{ interface_namespace }}'  => 'App\\Repositories\\Contracts',
        ]);

        $this->files->put($path, $content);
        $this->components->info("Repository dibuat: app/Repositories/{$name}.php");
    }

    private function getStub(string $stubName): string
    {
        // Cari stub di /stubs folder project dulu, fallback ke package default
        $customPath = base_path("stubs/{$stubName}.stub");

        if ($this->files->exists($customPath)) {
            return $this->files->get($customPath);
        }

        // Fallback: inline stub
        return $this->getInlineStub($stubName);
    }

    private function getInlineStub(string $stubName): string
    {
        return match ($stubName) {
            'repository.interface' => <<<'STUB'
<?php

namespace {{ namespace }};

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface {{ class }}
{
    public function getAll(): Collection;

    public function findById(int $id): Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;
}
STUB,
            'repository' => <<<'STUB'
<?php

namespace {{ namespace }};

use {{ model_namespace }}\{{ model }};
use {{ interface_namespace }}\{{ interface }};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class {{ class }} implements {{ interface }}
{
    public function __construct(
        protected {{ model }} $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        $record = $this->model->findOrFail($id);

        return $record->delete();
    }
}
STUB,
            default => throw new \InvalidArgumentException("Stub '{$stubName}' tidak ditemukan."),
        };
    }

    private function replaceStub(string $stub, array $replacements): string
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );
    }
}