<?php

namespace App\Classes;

use Schema;
use Log;

class Helpers
{

    public static function lsi () {
        return substr(\Session::getId(), 0, 12);
    }

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

    public static function parseString($string) {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = preg_replace("/[^a-zA-Z0-9]/", "-", $clean);
        $newString = mb_strtolower($clean, 'UTF-8');
        Log::debug(__METHOD__ . ' ' . self::lsi() . ' ' . $string . ' -> ' . $newString);
        return $newString;
    }

}
