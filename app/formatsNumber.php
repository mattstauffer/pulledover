<?php namespace App;

trait formatsNumber
{
    public function getFormattedNumberAttribute()
    {
        return sprintf(
            '(%s) %s-%s',
            substr($this->number, 0, 3),
            substr($this->number, 3, 3),
            substr($this->number, 6)
        );
    }
}
