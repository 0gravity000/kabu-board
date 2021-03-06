<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\ResetChangeRateEvent;    //イベントクラス名とかぶるとダメ

class ResetChangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kabu:resechangetrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset changerate';

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
        event(new ResetChangeRateEvent());
    }
}
