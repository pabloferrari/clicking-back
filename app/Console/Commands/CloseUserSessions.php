<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\OauthAccessToken;

class CloseUserSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:closeUserSessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $sessions = OauthAccessToken::where('revoked', 0)->get();
        Log::debug('RUNNING -> command:closeUserSessions');
        foreach($sessions as $session) {
            $session->revoked = 1;
            $session->save();
            Log::debug('SESSION -> userId: ' . $session->user_id . ' closed');
        }
        return 0;
    }
}
