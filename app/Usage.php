<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Usage extends Model
{
    protected $table = 'app_usage';
    protected $fillable = ['day','useTime','location','user_id','application_id'];
    
    public function register($day,$useTime,$location,$user_id,$application_id)
    {
        $usage = new usage;
        $usage->day = $day;
        $usage->useTime = $useTime;
        $usage->location = $location;
        $usage->user_id = $user_id;
        $usage->application_id = $application_id;
        $usage->save();
    }

    public function getUsage ($user_id)
    {
        $usages = DB::table('app_usage')->select('user_id','application_id','day',DB::raw("SUM(useTime) as totalTime"))
                                        ->from('app_usage')
                                        ->where('user_id', $user_id)
                                        ->groupBy('user_id','application_id','day')
                                        ->get();
        return $usages;
    }
}
