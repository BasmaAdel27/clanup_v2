<?php

namespace App\Helpers;

use Carbon\Carbon;

class Timezone
{
    /**
     * @param  Carbon\Carbon|null  $date
     * @param  null  $format
     * @param  bool  $format_timezone
     * @return string
     */
    public static function convertToLocal($date, $format = null, $format_timezone = false, $user = null) : string
    {
        if (is_null($date)) {
            return __('Empty');
        }

        $user = $user ?? auth()->user();
        $timezone = isset($user->timezone) && !empty($user->timezone) ? $user->timezone : config('app.timezone');
        $date->setTimezone($timezone);

        if (is_null($format)) {
            return substr($date->format('D, M j \@ g:i A \G\M\TO'), 0, -2);
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . self::formatTimezone($date);
        }

        return $formatted_date_time;
    }

    /**
     * @param $date
     * @return Carbon\Carbon
     */
    public static function convertFromLocal($date) : Carbon
    {
        $timezone = (auth()->user()->timezone) ?? config('app.timezone');
        return Carbon::parse($date, $timezone)->setTimezone('UTC');
    }

    /**
     * @param  Carbon\Carbon  $date
     * @return string
     */
    private static function formatTimezone($date) : string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}