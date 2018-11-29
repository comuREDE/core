<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WaterSensor extends Model
{
    protected $table = 'sensores_agua';

    public function getWaterStateNow()
    {
        return WaterSensor::orderBy('dia_hora', 'desc')
            ->limit(1)
            ->get();
    }

    public function getWaterState($limit)
    {
        return WaterSensor::orderBy('dia_hora', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getWaterStatePerDays($days)
    {
        return WaterSensor::where('dia_hora', '>', Carbon::now()->subDays($days))
            ->get();
    }
}
