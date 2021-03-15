<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalculateValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'em:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command recalculates pneumonia and diarrhoea data and stores them in pneumonia_calculated_values and diarrhoea_calculated_values';

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
     * @return mixed
     */
    public function handle()
    {
        \App\Jobs\RecalculateValues::dispatch();
    }
}
