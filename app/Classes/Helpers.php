<?php

namespace App\Classes;

use Schema;

class Helpers
{

    public static function paramBuilder($model, $data)
    {
        $nameSpace = '\\App\\Models\\'; // assuming you're using the default Laravel 5.8 folder structure\
        $model = $nameSpace . $model;
        $newInstance = new $model;
        $params = Schema::getColumnListing($newInstance->getTable());
        $body = [];
        foreach($params as $param){
            if(isset($data[$param])){
                $body[$param] = $data[$param];
            }
        }
        return $body;
    }

}
