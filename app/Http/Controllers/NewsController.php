<?php

// watch/write new events to the news log to be displayed elsewhere
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function addnews(Request $request)
    {
        $news = $request->input('news');
        $timestamp = date('Y-m-d H:i:s');
        DB::connection('chat')->table('news')->insert(['news' => $news, 'timestamp' => $timestamp]);
    }
    public function getnews()
    {
        $news = DB::connection('chat')->table('news')->get();
        return response()->json(['news' => $news]);
    }
    public function deletenews(Request $request)
    {
        $id = $request->input('id');
        DB::connection('chat')->table('news')->where('id', $id)->delete();
    }
}