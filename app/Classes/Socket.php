<?php

namespace App\Classes;

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version2X;
use App\Classes\Helpers;
use Log;


class Socket
{

    public static function send($channel, $data)
    {
        
        // $ip = 'http://3.20.162.44:9201/';
        $ip = 'http://localhost:9201/';
        try {
            
            $ipOpra = 'http://200.110.137.84:1113';
            // $dataOpra = [
            //     'log' => "<div class=\"log-line-container\"><span class=\"timestamp\">[".date('Y-m-d')."]</span> - <span class=\"debug\">debug</span> - aaaaaa - meaksjdhasjkdh</div>",
            //     'target' => 'eks1'
            //  ];
            // $elephant = new Elephant(new Version2X($ipOpra));
            // $elephant->initialize();
            // dd($elephant);
            // $elephant->emit('sender', $dataOpra);
            // $elephant->close();


            // $elephant = new Elephant(new Version2X($ipOpra));
            $elephant = new Elephant(new Version2X($ip));
            $elephant->initialize();
            $elephant->emit($channel, $data);
            $elephant->close();

            Log::debug(__METHOD__ . ' ' . Helpers::lsi() . ' SUCCESS ');

        } catch (\Throwable $th) {
            
            Log::error(__METHOD__ . ' ' . Helpers::lsi() . ' channel: ' . $channel . ' ' . json_encode($data) . ' -> ' . $th->getMessage() . ' ip ' . $ip);

        }
    }


}