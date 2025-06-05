<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\DocumentationEntry;
use OpenAI\Laravel\Facades\OpenAI;
use ReflectionClass;
use ReflectionMethod;

class GenerateDocumentation extends Command
{
    protected $signature = 'generate:docs';
    protected $description = 'Generate system documentation using OpenAI and save to database';

    public function handle()
    {
        $controllers = File::allFiles(app_path('Http/Controllers'));

        foreach ($controllers as $file) {
            $path = $file->getRealPath();
            $classContent = File::get($path);
            $namespace = $this->getNamespace($classContent);
            $className = $this->getClassName($classContent);

            $fqcn = $namespace . '\\' . $className;

            if (!class_exists($fqcn)) {
                require_once $path;
            }

            if (class_exists($fqcn)) {
                $reflection = new ReflectionClass($fqcn);
                $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))
                    ->filter(fn($m) => $m->class === $fqcn)
                    ->map(fn($m) => $m->getName());

                $views = $this->detectViewFiles($classContent);
                $models = $this->detectModelFiles($classContent);
                $tests = $this->detectTestFiles($className);
                $factories = $this->detectFactoryFiles($models);

                $summary = [
                    'class' => $fqcn,
                    'methods' => $methods,
                    'views' => $views,
                    'models' => $models,
                    'tests' => $tests,
                    'factories' => $factories,
                ];
                
                $jsonSummary = json_encode($summary, JSON_PRETTY_PRINT);

                $prompt = "You are a Laravel expert and technical writer. Your task is to generate a clear and professional technical documentation in Markdown format for the given Laravel Controller class based on its class name, file path, methods, associated views, models, test files, and factory files.\n\n";
                $prompt .= "Please follow this structure:\n";
                $prompt .= "1. **Class Name** – Full class name with namespace.\n";
                $prompt .= "2. **File Path** – Controller file location.\n";
                $prompt .= "3. **Description** – A summary of the controller purpose in 2-3 sentences.\n";
                $prompt .= "4. **Methods Overview** – List each method in the class with:\n";
                $prompt .= "   - Method name\n";
                $prompt .= "   - Purpose\n";
                $prompt .= "   - Parameters (if any)\n";
                $prompt .= "   - Return value\n";
                $prompt .= "5. **Used Models** – List and describe each Eloquent model used in the controller.\n";
                $prompt .= "6. **Used Views** – List the Blade view files rendered, and briefly explain what they display.\n";
                $prompt .= "7. **Associated Test Files** – Mention if this controller is covered by tests.\n";
                $prompt .= "8. **Factory Files** – Mention related factory files and their purpose.\n\n";
                $prompt .= "Please keep the explanation clear and helpful for junior developers or new team members. Format the output cleanly in Markdown.\n\n";
                $prompt .= "Here is the JSON summary:\n\n";
                $prompt .= "```json\n" . $jsonSummary . "\n```";

                $openaiResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a software documentation generator.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                ]);

                $documentation = $openaiResponse->choices[0]->message->content;

                DocumentationEntry::updateOrCreate(
                    ['class_name' => $fqcn],
                    [
                        'source_path' => $path,
                        'metadata' => $summary,
                        'documentation' => $documentation,
                    ]
                );

                $this->info("Documented: $fqcn");
            }
        }
    }

    protected function getNamespace($content)
    {
        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            return $matches[1];
        }
        return '';
    }

    protected function getClassName($content)
    {
        if (preg_match('/class\s+(\w+)/', $content, $matches)) {
            return $matches[1];
        }
        return '';
    }

    protected function detectViewFiles($content)
    {
        preg_match_all('/view\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        return $matches[1] ?? [];
    }

    protected function detectModelFiles($content)
    {
        preg_match_all('/use\s+App\\\Models\\\([A-Za-z0-9_]+)/', $content, $matches);
        return array_unique($matches[1] ?? []);
    }

    protected function detectTestFiles($className)
    {
        $testFiles = File::allFiles(base_path('tests/Feature'));
        return collect($testFiles)
            ->filter(fn($f) => str_contains($f->getFilename(), $className))
            ->map(fn($f) => $f->getRealPath())
            ->values()
            ->toArray();
    }

    protected function detectFactoryFiles($models)
    {
        return collect($models)
            ->map(fn($model) => base_path("database/factories/{$model}Factory.php"))
            ->filter(fn($path) => File::exists($path))
            ->values()
            ->toArray();
    }
}
