<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait CalendarTrait{
    public function betweenDates($startDate, $endDate, $events = null, $eventName = null){
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        
        $dates = [];

        if($events){
            foreach ($dateRange->toArray() as $key => $value) {
                $simpleDay = Carbon::parse($value)->format('l');
                $day = Carbon::parse($value)->format('j D');
                array_push($dates, ["text" => $day, "event" => in_array(strtolower($simpleDay), $events) ? $eventName : ""]);
            }
        }else{
            foreach ($dateRange->toArray() as $key => $value) {
                array_push($dates, ["text" => Carbon::parse($value)->format('j D')]);
            }
        }

        return $dates;
    }
}

?>