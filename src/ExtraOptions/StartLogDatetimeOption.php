<?php

namespace shamanzpua\LaravelProfiler\ExtraOptions;

use Carbon\Carbon;
use shamanzpua\LaravelProfiler\Contracts\IExtraOption;

class StartLogDatetimeOption implements IExtraOption
{

    public function get($data)
    {
        $timezone = config('code-profiler.timezone');

        $logDate = Carbon::createFromTimestamp($data);

        if ($timezone) {
            $logDate->timezone($timezone);
        }
        return $logDate->toDateTimeString();
    }
}