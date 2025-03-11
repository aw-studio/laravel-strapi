<?php

namespace AwStudio\LaravelStrapi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeStrapiModel extends Command
{
    protected $signature = 'make:strapi-model {name}';

    protected $description = 'Create a new Strapi model';

    public function handle()
    {
        $name = $this->argument('name');

        // Ask the user if it's a Collection or Single Type
        $type = $this->choice('Is this a Collection Type or a Single Type?', ['Collection', 'Single'], 0);

        $namespace = 'App\\Strapi\\Models';
        $directory = app_path('Strapi/Models');
        $classType = $type === 'Collection' ? 'StrapiCollectionType' : 'StrapiSingleType';

        // Ensure the directory exists
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Define the file path
        $filePath = "{$directory}/{$name}.php";

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("Model {$name} already exists!");

            return;
        }

        // Generate the model file content
        $stub = <<<PHP
<?php

namespace {$namespace};

use AwStudio\LaravelStrapi\Models\\{$classType};

class {$name} extends {$classType}
{
    //
}
PHP;

        // Write the file
        File::put($filePath, $stub);

        $this->info("Strapi model {$name} created successfully!");
    }
}
