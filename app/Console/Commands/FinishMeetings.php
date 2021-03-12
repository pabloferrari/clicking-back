<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\{Meeting,MeetingUser,Notification};
use \Carbon\Carbon;


class FinishMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:finishMeetings';

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
        $meetings = Meeting::where('finished', false)->get();
        echo "\nMeetings To finish -> " . count($meetings) . "\n\n";
        $this->output->progressStart(count($meetings));
        collect($meetings)->map(function ($meeting) {
            $expire = Carbon::parse($meeting->created_at)->addMinutes($meeting->minutes);
            $now = Carbon::now();
            if($now > $expire) {
                $meeting->finished = true;
                $meeting->save();
                $meetUsers = MeetingUser::where('meeting_id', $meeting->id)->get()->pluck('id');
                Notification::where('type', 'meeting')->whereIn('model_id', $meetUsers)->update(['finished' => true]);
            }
            $this->output->progressAdvance();
        });
        $this->output->progressFinish();
        return 0;
    }
}
