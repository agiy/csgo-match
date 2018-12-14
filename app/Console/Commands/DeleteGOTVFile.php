<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteGOTVFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete:gotv:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '7日前のgotvファイル削除';

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
        $now = Carbon::now();
        $deleteLine = $now->subDays(7);

        $files = \Storage::files(env('GOTV_DIRECTORY'));
        $deleteFiles = [];

        foreach ($files as $file) {
            $ext = substr($file, strrpos($file, '.') + 1);
            if ($ext !== 'dem') {
                continue;
            }

            $timestamp = \Storage::lastModified($file);
            $date      = Carbon::createFromTimestamp($timestamp);

            //$deleteLineを過ぎていたら削除対象
            if ($date->lte($deleteLine)) {
                $deleteFiles[] = $file;
            }
        }

        \Storage::delete($deleteFiles);
    }
}
