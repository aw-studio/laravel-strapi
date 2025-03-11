<?php

namespace AwStudio\LaravelStrapi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeStrapiComponent extends Command
{
    protected $signature = 'make:strapi-component {name?}';

    protected $description = 'Create a new Strapi component';

    public function handle()
    {
        $componentsPath = app_path('Strapi/Components');

        // Ensure the Components directory exists
        if (! File::exists($componentsPath)) {
            File::makeDirectory($componentsPath, 0755, true, true);
        }

        // Get existing collections
        $collections = array_values(array_filter(scandir($componentsPath), function ($item) use ($componentsPath) {
            return is_dir($componentsPath.'/'.$item) && ! in_array($item, ['.', '..']);
        }));

        // Ask for the collection
        $collections[] = 'Create New Collection';
        $collection = $this->choice('Select the collection for the component:', $collections, 0);

        if ($collection === 'Create New Collection') {
            $collection = $this->ask('Enter the name of the new collection');
        }

        $collectionKebab = Str::of($collection)->kebab()->toString();
        $collectionPascal = Str::of($collection)->studly()->toString();

        $collectionPath = "{$componentsPath}/{$collectionPascal}";

        // Ensure the collection directory exists
        if (! File::exists($collectionPath)) {
            File::makeDirectory($collectionPath, 0755, true, true);
        }

        // Ask for the component name if not provided
        $name = $this->argument('name') ?? $this->ask('Enter the component name');
        $componentFileName = Str::of($name)->kebab()->toString();

        // Define file paths
        $classFilePath = "{$collectionPath}/{$name}.php";
        $viewPath = resource_path('views/components/'.$collectionKebab.'/'.$componentFileName.'.blade.php');

        // Check if the class file already exists
        if (File::exists($classFilePath)) {
            $this->error("Component {$name} already exists in the {$collection} collection!");

            return;
        }

        // Generate the component class
        $stub = <<<PHP
<?php

namespace App\Strapi\Components\\{$collection};

use AwStudio\LaravelStrapi\StrapiComponent;
use Illuminate\Contracts\View\View;

class {$name} extends StrapiComponent
{
    public string|array \$populate = '*';

    public function render(): View
    {
        return view('components.{$collectionKebab}.{$componentFileName}');
    }
}
PHP;

        // Write the class file
        File::put($classFilePath, $stub);

        // Ensure the Blade component directory exists
        $bladeDirectory = dirname($viewPath);
        if (! File::exists($bladeDirectory)) {
            File::makeDirectory($bladeDirectory, 0755, true, true);
        }

        // Create an empty Blade component file
        File::put($viewPath, "<!-- Blade component for {$name} in {$collection} -->");

        // Update the config file
        $this->addComponentToConfig($collection, "App\\Strapi\\Components\\{$collection}\\{$name}");

        $this->info("Strapi component {$name} created successfully in the {$collection} collection!");
    }

    protected function addComponentToConfig(string $collection, string $componentClass)
    {
        $configPath = config_path('laravel-strapi.php');
        $config = file_get_contents($configPath);

        // Get existing components array
        $components = config('laravel-strapi.components', []);

        // Ensure collection exists in the config
        if (! isset($components[$collection])) {
            // Use regex to insert new collection
            $pattern = "/'components' => \[\s*/";
            $replacement = "'components' => [\n        '{$collection}' => [\n            {$componentClass}::class,\n        ],\n";
            $config = preg_replace($pattern, $replacement, $config);
        } else {
            // Check if the component already exists
            if (! in_array($componentClass.'::class', $components[$collection])) {
                $pattern = "/('{$collection}' => \[\s*)(.*?)(\n\s*\],)/s";
                $replacement = "$1$2\n            {$componentClass}::class,$3";
                $config = preg_replace($pattern, $replacement, $config);
            }
        }

        file_put_contents($configPath, $config);
    }
}
