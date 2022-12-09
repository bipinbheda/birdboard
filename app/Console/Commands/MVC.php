<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\BufferedOutput;

class MVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mvc {Name} {--html}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Model View Controller files';

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
        $name = $this->argument('Name');
        $this->call('make:view',['Name'=> $name,'--html' => $this->option('html')]);
        $this->call('make:model',['name' => $name,'--controller' => true]);
    }
}
