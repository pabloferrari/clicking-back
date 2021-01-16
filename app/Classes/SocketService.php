<?php

namespace App\Classes;

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version2X;
use App\Classes\Helpers;
use Log;


class SocketService
{

    public static function send($channel, $data)
    {
        $ip = env('SOCKET_IP');
        try {
            
            $elephant = new Elephant(new Version2X($ip));
            $elephant->initialize();
            $elephant->emit($channel, $data);
            $elephant->close();

            Log::channel('socket')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' SUCCESS ' . $channel . ' -> ' . json_encode($data));

        } catch (\Throwable $th) {
            
            Log::channel('socket')->error(__METHOD__ . ' ' . Helpers::lsi() . ' channel: ' . $channel . ' ' . json_encode($data) . ' -> ' . $th->getMessage() . ' ip ' . $ip);

        }
    }


}