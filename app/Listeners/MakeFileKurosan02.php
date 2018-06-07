<?php

namespace App\Listeners;

use App\Events\CheckSignalKurosan02;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use App\Meigara;
use Illuminate\Support\Facades\Storage;

class MakeFileKurosan02
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckSignalKurosan02  $event
     * @return void
     */
    public function handle(CheckSignalKurosan02 $event)
    {
        $dt = Carbon::now();
        //基準日(当日)の算出
        //時間チェック
        if($dt->hour < 18) {    
            //18時より前は当日分のファイルがないので前日を基準に設定する
            $dt->subDay();
        }
        //週末チェック
        if($dt->dayOfWeek == 6){    //土曜
            $dt->subDay();
        } elseif ($dt->dayOfWeek == 0) {    //日曜
            $dt->subDay(2);
        }
            $basedayfile = $dt->year.'-'.sprintf('%02d', $dt->month).'-'.sprintf('%02d', $dt->day).".txt";

        //1日前を算出
            $dt->subDay();
        //週末チェック
        if($dt->dayOfWeek == 6){    //土曜
            $dt->subDay();
        } elseif ($dt->dayOfWeek == 0) {    //日曜
            $dt->subDay(2);
        }
            $onedayagofile = $dt->year.'-'.sprintf('%02d', $dt->month).'-'.sprintf('%02d', $dt->day).".txt";

        //2日前を算出
            $dt->subDay();
        //週末チェック
        if($dt->dayOfWeek == 6){    //土曜
            $dt->subDay();
        } elseif ($dt->dayOfWeek == 0) {    //日曜
            $dt->subDay(2);
        }
            $twodaysagofile = $dt->year.'-'.sprintf('%02d', $dt->month).'-'.sprintf('%02d', $dt->day).".txt";

        //3日前を算出
            $dt->subDay();
        //週末チェック
        if($dt->dayOfWeek == 6){    //土曜
            $dt->subDay();
        } elseif ($dt->dayOfWeek == 0) {    //日曜
            $dt->subDay(2);
        }
        $threedaysagofile = $dt->year.'-'.sprintf('%02d', $dt->month).'-'.sprintf('%02d', $dt->day).".txt";

        $filepath = storage_path('app/kabus/daily');
        $filepath_signal = storage_path('app/kabus/signal');
        //$filepath = '../storage/app/kabus/daily';

        $contents = '';
        //$Dailys = [];
        //$Files = [ $basedayfile, $onedayagofile, $twodaysagofile, $threedaysagofile ];
        //dd($Files);
        var_dump($filepath .'/'.$twodaysagofile);
        //ファイルあり
        $contents = \File::get($filepath .'/'.$twodaysagofile);
        $contents = str_replace(',', '', $contents);    //カンマ削除
        $today = Carbon::now()->toDateString();
        if (\File::exists($filepath_signal .'/'. $today .'_kurosan01.txt')) {
            var_dump($filepath_signal .'/'. $today .'_kurosan01.txt');
            $contents_compare = \File::get($filepath_signal .'/'. $today .'_kurosan01.txt');  //比較ファイル
            //var_dump($contents_compare);
           //データ抽出
            $startpos = 0;
            while(mb_strpos($contents, '\n', $startpos)){
                $endpos = mb_strpos($contents, '\n', $startpos);
                $rowstring = mb_substr($contents, $startpos, $endpos - $startpos);

                $dailysArray = mb_split('/', $rowstring);
                //var_dump($dailysArray);
                //改行コードの調整
                $code = str_replace(array("\n", "n"), '', $dailysArray[0]);
                if ($dailysArray[1] != "---") { //終値が---でないもの
                    if (intval($dailysArray[1]) < intval($dailysArray[3])) {  //終値 < 始値 ?
                        //比較ファイル中に、該当コードがあるか?
                        $compare_startpos = mb_strpos($contents_compare, 'C'. $code.'/');
                        var_dump($compare_startpos);
                        if ($compare_startpos) {
                            //比較ファイル中に、該当コードがある場合
                            //前日終値を取得
                            $compare_startpos += 6;
                            $compare_endpos = mb_strpos($contents_compare, '\n', $compare_startpos);
                            $compare_endvalue = mb_substr($contents_compare, $compare_startpos, $compare_endpos - $compare_startpos);
                            //var_dump($compare_endvalue);
                            //終値 < 前日終値 ?
                            if (intval($dailysArray[1]) < intval($compare_endvalue)) {
                                //終値 < 前日終値 ?
                                $fileOutputString = ('C'. $code .'/'. $compare_endvalue .'/'. $dailysArray[1] .'\n');
                                //ファイル出力
                                Storage::append(('kabus/signal/'. $today .'_kurosan02.txt'), $fileOutputString);
                            }
                        }
                    }
                }
                //var_dump($fileloop);
                $startpos = $endpos+1;
            }
        } 
        //比較用ファイルが存在しない、該当データが一行もない場合
        if (!(\File::exists($filepath_signal .'/'. $today .'_kurosan02.txt'))) {
            $fileOutputString = ('---' .'/'. '---' .'/'. '---' .'\n');
            //ファイル出力
            Storage::append(('kabus/signal/'. $today .'_kurosan02.txt'), $fileOutputString);
        }

    }
}
