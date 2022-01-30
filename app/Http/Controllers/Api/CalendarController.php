<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Calendar;
use App\Http\Requests\CalendarRequest;
use App\Http\Resources\CalendarResource;
use Carbon\Carbon;
use App\Http\Traits\CalendarTrait;

class CalendarController extends ApiController
{
    use CalendarTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {
        $calendar = Calendar::create($request->all());
        $startDate = Carbon::createFromFormat('Y-m-d', $calendar->from_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $calendar->to_date);

        $dates = $this->betweenDates($startDate, $endDate, json_decode($calendar->days), $calendar->event);
        return $this->success($dates);
    }

    public function inBetweenDates(Request $request){
        $data = $request->only('from_date', 'to_date');
        $startDate = Carbon::createFromFormat('Y-m-d', $data['from_date']);
        $endDate = Carbon::createFromFormat('Y-m-d', $data['to_date']);
        
        $dates = $this->betweenDates($startDate, $endDate);

        return $this->success($dates);
    }
}
