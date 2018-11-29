<?php

namespace App\Http\Controllers;

use App\LightSensor;
use Illuminate\Http\Request;

class LightSensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LightSensor  $lightSensor
     * @return \Illuminate\Http\Response
     */
    public function show(LightSensor $lightSensor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LightSensor  $lightSensor
     * @return \Illuminate\Http\Response
     */
    public function edit(LightSensor $lightSensor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LightSensor  $lightSensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LightSensor $lightSensor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LightSensor  $lightSensor
     * @return \Illuminate\Http\Response
     */
    public function destroy(LightSensor $lightSensor)
    {
        //
    }

    public function getLightStateNow()
    {
        return response((new LightSensor)
            ->getWaterStateNow(), 200);
    }

    public function getLightState($limit)
    {
        return response((new LightSensor)
            ->getWaterState($limit), 200);
    }

    public function getLightStatePerDays($days)
    {
        return response((new LightSensor)
            ->getWaterStatePerDays($days), 200);
    }
}
