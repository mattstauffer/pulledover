<?php namespace App;

use Illuminate\Support\Str;

trait formatsNumber
{
    public function getFormattedNumberAttribute()
    {
        return Str::formatNumber($this->number);
    }
}
