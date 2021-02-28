<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_type')->insert([
            ["name" => "Evento", "color" => "#c2c2c2", "google_color_id" => "19", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Reunion", "color" => "#9fe1e7", "google_color_id" => "14", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Clase", "color" => "#fad165", "google_color_id" => "12", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Taller", "color" => "#f691b2", "google_color_id" => "22", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Examen", "color" => "#7bd148", "google_color_id" => "9", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Recuparatorio", "color" => "#ffad46", "google_color_id" => "6", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Final", "color" => "#f83a22", "google_color_id" => "3", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "Tarea", "color" => "#f83a22", "google_color_id" => "3", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ["name" => "TP", "color" => "#ffad46", "google_color_id" => "3", "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")]
        ]);
    }
}

/*
<h3>Calendar</h3>
<div style="padding: 5px;background-color: #ac725e;">"1" color #ac725e</div>
<div style="padding: 5px;background-color: #d06b64;">"2" color #d06b64</div>
<div style="padding: 5px;background-color: #f83a22;">"3" color #f83a22</div>
<div style="padding: 5px;background-color: #fa573c;">"4" color #fa573c</div>
<div style="padding: 5px;background-color: #ff7537;">"5" color #ff7537</div>
<div style="padding: 5px;background-color: #ffad46;">"6" color #ffad46</div>
<div style="padding: 5px;background-color: #42d692;">"7" color #42d692</div>
<div style="padding: 5px;background-color: #16a765;">"8" color #16a765</div>
<div style="padding: 5px;background-color: #7bd148;">"9" color #7bd148</div>
<div style="padding: 5px;background-color: #b3dc6c;">"10" color #b3dc6c</div>
<div style="padding: 5px;background-color: #fbe983;">"11" color #fbe983</div>
<div style="padding: 5px;background-color: #fad165;">"12" color #fad165</div>
<div style="padding: 5px;background-color: #92e1c0;">"13" color #92e1c0</div>
<div style="padding: 5px;background-color: #9fe1e7;">"14" color #9fe1e7</div>
<div style="padding: 5px;background-color: #9fc6e7;">"15" color #9fc6e7</div>
<div style="padding: 5px;background-color: #4986e7;">"16" color #4986e7</div>
<div style="padding: 5px;background-color: #9a9cff;">"17" color #9a9cff</div>
<div style="padding: 5px;background-color: #b99aff;">"18" color #b99aff</div>
<div style="padding: 5px;background-color: #c2c2c2;">"19" color #c2c2c2</div>
<div style="padding: 5px;background-color: #cabdbf;">"20" color #cabdbf</div>
<div style="padding: 5px;background-color: #cca6ac;">"21" color #cca6ac</div>
<div style="padding: 5px;background-color: #f691b2;">"22" color #f691b2</div>
<div style="padding: 5px;background-color: #cd74e6;">"23" color #cd74e6</div>
<div style="padding: 5px;background-color: #a47ae2;">"24" color #a47ae2</div>
<h3>Events</h3>
<div style="padding: 5px;background-color: #a4bdfc">"1" color #a4bdfc</div>
<div style="padding: 5px;background-color: #7ae7bf">"2" color #7ae7bf</div>
<div style="padding: 5px;background-color: #dbadff">"3" color #dbadff</div>
<div style="padding: 5px;background-color: #ff887c">"4" color #ff887c</div>
<div style="padding: 5px;background-color: #fbd75b">"5" color #fbd75b</div>
<div style="padding: 5px;background-color: #ffb878">"6" color #ffb878</div>
<div style="padding: 5px;background-color: #46d6db">"7" color #46d6db</div>
<div style="padding: 5px;background-color: #e1e1e1">"8" color #e1e1e1</div>
<div style="padding: 5px;background-color: #5484ed">"9" color #5484ed</div>
<div style="padding: 5px;background-color: #51b749">"10" color #51b749</div>
<div style="padding: 5px;background-color: #dc2127">"11" color #dc2127</div>
*/
