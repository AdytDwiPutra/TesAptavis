<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * php artisan make:service UserService
     * php artisan make:service UserService --repository=UserRepository
     * php artisan make:service UserService --plain  (tanpa inject repository)
     */
    protected $signature = 'make:service
                            {name : Nama service, contoh: UserService}
                            {--repository= : Nama Repository Interface yang di-inject, contoh: UserRepository}
                            {--plain : Buat service kosong tanpa inject repository}';

    protected $description = 'Buat Service class sesuai pola Service Layer Pattern';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');

        // Pastikan nama diakhiri "Service"
        if (!str_ends_with($name, 'Service')) {
            $name .= 'Service';
        }

        $path = app_path("Services/{$name}.php");

        if ($this->files->exists($path)) {
            $this->components->warn("Service sudah ada: {$path}");
            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists(app_path('Services'));

        $content = $this->option('plain')
            ? $this->buildPlainService($name)
            : $this->buildServiceWithRepository($name);

        $this->files->put($path, $content);
        $this->components->info("Service dibuat: app/Services/{$name}.php");

        return self::SUCCESS;
    }

    private function buildServiceWithRepository(string $name): string
    {
        // Inferensikan nama repository dari nama service jika tidak disediakan
        $repoName = $this->option('repository')
            ?: str_replace('Service', 'Repository', $name);

        // Pastikan diakhiri "Repository"
        if (!str_ends_with($repoName, 'Repository')) {
            $repoName .= 'Repository';
        }

        $interface = $repoName . 'Interface';

        $stub = $this->getStub();

        return $this->replaceStub($stub, [
            '{{ namespace }}'              => 'App\\Services',
            '{{ class }}'                  => $name,
            '{{ repository_interface }}'   => $interface,
            '{{ repository_namespace }}'   => 'App\\Repositories',
        ]);
    }

    private function buildPlainService(string $name): string
    {
        return <<<PHP
<?php

namespace App\Services;

class {$name}
{
    public function __construct()
    {
        //
    }
}
PHP;
    }

    private function getStub(): string
    {
        $customPath = base_path('stubs/service.stub');

        if ($this->files->exists($customPath)) {
            return $this->files->get($customPath);
        }

        // Inline fallback
        return <<<'STUB'
<?php

namespace {{ namespace }};

use {{ repository_namespace }}\Contracts\{{ repository_interface }};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class {{ class }}
{
    public function __construct(
        protected {{ repository_interface }} $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function findById(int $id): Model
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
STUB;
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