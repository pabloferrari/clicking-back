<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\News;
use Illuminate\Support\Carbon;
use DB;

class NewsService
{

    public static function getNewsAll()
    {
        return News::where('institution_id', Auth::user()->institution_id)->with(['institution'])->get();
    }

    public static function getNews($id)
    {

        return News::where('id', $id)->with('institution')->first();
    }

    public static function createNews($data)
    {
        $newNews = new News();
        $newNews->title          = $data['title'];
        $newNews->description    = $data['description'];
        $newNews->date           = Carbon::parse($data['date'])->format('Y-m-d H:i:s');
        $newNews->institution_id = Auth::user()->institution_id;
        $newNews->public         = $data['public'];
        $newNews->save();
        return self::getNews($newNews->id);
    }

    public static function updateNews($id, $data)
    {
        News::where('id', $id)->update($data);
        return self::getNews($id);
    }

    public static function deleteNews($id)
    {
        return News::where('id', $id)->delete();
    }
}
