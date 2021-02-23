<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\News;
use Illuminate\Support\Carbon;
use DB;
use Log;

class NewsService
{

    public static function getNewsAll()
    {
        return News::where('institution_id', Auth::user()->institution_id)
            ->with(['institution', 'user', 'fileNews'])
            ->order_by('date', 'desc')->get();
    }

    public static function getNews($id)
    {
        //return News::where('id', $id)->with('institution', 'user')->first();
        return News::where('id', $id)
            ->with(['institution', 'user', 'fileNews'])
            ->get();
    }

    public static function createNews($data, $request)
    {
        DB::beginTransaction();
        $newNews = new News();
        $newNews->title          = $data['title'];
        $newNews->description    = $data['description'];
        $newNews->date           = Carbon::parse($data['date'])->format('Y-m-d H:i:s');
        $newNews->institution_id = Auth::user()->institution_id;
        $newNews->user_id = Auth::user()->id;
        $newNews->public = $data['public'] ? true : false;
        $newNews->save();

        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $dataFile = array(
            'model_name' => 'News',
            'model_id'   => $newNews->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $resultFile = $handleFilesUploadService->createFile($dataFile);

        //return self::getNews($newNews->id);

        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE NEWS ' . json_encode($resultFile));
            return self::getNews($newNews->id);
        } else {
            DB::rollback();
            return false;
        }
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
