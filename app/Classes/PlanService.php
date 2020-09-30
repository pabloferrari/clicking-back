<?php

namespace App\Classes;

use App\Models\Plan;
use Log;

class PlanService
{

    public static function getPlans()
    {
        return Plan::get();
    }

    public static function getPlan($id)
    {
        return Plan::where('id', $id)->first();
    }

    public static function createPlan($data)
    {
        $newPlan = new Plan();
        $newPlan->name = $data['name'];
        $newPlan->save();
        return $newPlan;
    }

    public static function updatePlan($id, $data)
    {
        Plan::where('id', $id)->update($data);
        return Plan::where('id', $id)->first();
    }
    
    public static function deletePlan($id)
    {
        return Plan::where('id', $id)->delete();
    }
    
}