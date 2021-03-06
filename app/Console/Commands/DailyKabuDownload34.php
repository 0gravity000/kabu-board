<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CheckDailyValue34;    //イベントクラス名とかぶるとダメ

class DailyKabuDownload34 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kabu:dailydownload34';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Daily Kabu Value';

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
        event(new CheckDailyValue34());
    }
}
