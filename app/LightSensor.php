<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LightSensor extends Model
{
    protected $table = 'sensores_luz';

    public function getLightStateNow()
    {
        return LightSensor::orderBy('dia_hora', 'desc')
            ->limit(1)
            ->get();
    }

    public function getLightState($limit)
    {
        return LightSensor::orderBy('dia_hora', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getLightStatePerDays($days)
    {
        return LightSensor::where('dia_hora', '>', Carbon::now()->subDays($days))
            ->get();
    }
}
