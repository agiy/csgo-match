<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;

class GOTVDemoController extends Controller
{
    public function index()
    {
        $files = \Storage::files(env('GOTV_DIRECTORY'));
        $demoFiles = [];

        foreach ($files as $file) {
            $ext = substr($file, strrpos($file, '.') + 1);
            if ($ext !== 'dem') {
                continue;
            }
            
            $arr = [];
            $arr['name'] = basename($file);

            $timestamp = \Storage::lastModified($file);
            $arr['datetime'] = Carbon::createFromTimestamp($timestamp)->toDateTimeString();

            $demoFiles[] = $arr;
        }

        return response(json_encode($demoFiles), 200, ['Content-Type' => 'application/json']);
    }

    public function download(Request $request)
    {
        \Validator::make($request->all(), [
            'filename' => 'required|string|max:500',
        ])->validate();

        $ext = substr($request->filename, strrpos($request->filename, '.') + 1);
        if ($ext !== 'dem') {
            abort(400);
        }

        if ( ! \Storage::exists(env('GOTV_DIRECTORY').'/'.$request->filename)) {
            abort(404);
        }

        return response()->download(env('GOTV_DIRECTORY').'/'.$request->filename);
    }
}
