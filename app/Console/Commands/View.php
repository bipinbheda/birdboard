<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class View extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {Name} {--html|html : Create view with default HTML content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new View File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createFile();
    }

    protected function createFile() {
        $name = $this->argument('Name');
        $path = base_path('resources/views/').$name.'.blade.php';
        $content = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.pathinfo($name,PATHINFO_FILENAME).'</title>
</head>
<body>

</body>
</html>';
        $filePath = pathinfo($path);
        if($filePath && !file_exists($path)) {
            if (!file_exists($filePath['dirname'])) {
                mkdir($filePath['dirname'], 0777, true);
            }
            $isCreated = touch($path);
            if ($isCreated) {
                if($this->option('html')) {
                    $viewFile = fopen($path, "w");
                    fwrite($viewFile, $content);
                    fclose($viewFile);
                }
                $this->info($name.'.blade.php View created successful!');
            } else {
                $this->error('View not created!');
            }
        } else {
            $this->error('View already exists!');
        }
    }
}
