<?php


namespace App\Models;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

trait Common
{
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Jalalian::fromCarbon(Carbon::createFromTimeString($value))->toString();
        } else {
            return $value;
        }

    }
}
