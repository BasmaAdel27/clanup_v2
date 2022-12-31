<?php

use App\Helpers\Currency as HelpersCurrency;
use App\Helpers\Initials;
use App\Helpers\Money;
use App\Helpers\Timezone;
use App\Models\BlogCategory;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Plan;
use App\Models\SystemSetting;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Services\Language\Codes;
use App\Services\Purifier\Purifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

if (!function_exists('get_current_location_by_ip')) {
    /**
     * Returns the current location by ip address
     */
    function get_current_location_by_ip($ip)
    {
        if (in_array($ip, ['127.0.0.1', 'localhost', '::1']) || config('app.is_demo') || config('app.envato_review')) {
            return (object) [
                'cityName' => get_system_setting('default_location_city_name'), 
                'countryName' => get_system_setting('default_location_country_name'), 
                'latitude' => get_system_setting('default_location_latitude'), 
                'longitude' => get_system_setting('default_location_longitude'), 
            ];
        }

        return Location::get($ip);
    }
}

if (!function_exists('money')) {
    /**
     * Instance of money class.
     *
     * @param mixed  $amount
     * @param string $currency
     * @param bool   $convert
     *
     */
    function money($amount, $currency = null, $convert = false)
    {
        if (is_null($currency) || empty($currency)) {
            $currency = env('DEFAULT_CURRENCY', 'USD');
        }

        return new Money($amount, currency($currency), $convert);
    }
}

if (!function_exists('currency')) {
    /**
     * Instance of currency class.
     *
     * @param string $currency
     */
    function currency($currency)
    {
        return new HelpersCurrency($currency);
    }
}

if (! function_exists('convertToLocal')) {
    /**
     * Get the convertToLocal funtion
     *
     * @param string $string
     * @param string $format
     * @param boolean $format_timezone
     *
     * @return string
     */
    function convertToLocal($date, $format = null, $format_timezone = false, $user = null)
    {
        return Timezone::convertToLocal($date, $format, $format_timezone, $user);
    }
}

if (! function_exists('convertFromLocal')) {
    /**
     * Get the convertFromLocal funtion
     *
     * @param string $date
     *
     * @return string
     */
    function convertFromLocal($date)
    {
        return Timezone::convertFromLocal($date);
    }
}

if (! function_exists('initials')) {
    /**
     * Get the initials of given name
     *
     * @param string $string
     *
     * @return string
     */
    function initials($string)
    {
        return Initials::generate($string);
    }
}

if (! function_exists('get_all_topic_categories')) {
    /**
     * get_all_topic_categories
     *
     * @return collect
     */
    function get_all_topic_categories()
    {
        return TopicCategory::all();
    }
}

if (! function_exists('get_topic_category_name')) {
    /**
     * get_topic_category_name
     *
     * @return string
     */
    function get_topic_category_name($id)
    {
        return optional(TopicCategory::find($id))->name;
    }
}

if (! function_exists('get_topic_name')) {
    /**
     * get_topic_name
     *
     * @return string
     */
    function get_topic_name($id)
    {
        return optional(Topic::find($id))->name;
    }
}

if (! function_exists('get_all_blog_categories')) {
    /**
     * get_all_blog_categories
     *
     * @return string
     */
    function get_all_blog_categories()
    {
        return BlogCategory::all();
    }
}

if (! function_exists('get_all_plans_available')) {
    /**
     * get_all_plans_available
     *
     * @return collect
     */
    function get_all_plans_available()
    {
        return Plan::whereInvoiceInterval('month')->active()->get();
    }
}

if (! function_exists('get_system_setting')) {
    /**
     * get_system_setting
     *
     * @return string
     */
    function get_system_setting($key)
    {
        return Cache::remember('system_'.$key, 3600, function () use ($key) {
            return SystemSetting::getSetting($key);
        });
    }
}

if (! function_exists('get_application_currency')) {
    /**
     * get_application_currency
     *
     * @return string
     */
    function get_application_currency()
    {
        return Currency::where('code', get_system_setting('application_currency'))->first();
    }
}

if (! function_exists('get_countries_select2_array')) {
    /**
     * get_countries_select2_array
     *
     * @return collect
     */
    function get_countries_select2_array()
    {
        return Country::getSelect2Array();
    }
}

if (! function_exists('get_currencies_select2_array')) {
    /**
     * get_currencies_select2_array
     *
     * @return collect
     */
    function get_currencies_select2_array()
    {
        return Currency::getSelect2Array();
    }
}

if (! function_exists('get_language_name')) {
    /**
     * get_language_name
     *
     * @return string
     */
    function get_language_name($short_code)
    {
        return Codes::get_language_name($short_code);
    }
}

if (! function_exists('get_language_codes')) {
    /**
     * get_language_codes
     *
     * @return array
     */
    function get_language_codes()
    {
        return Codes::get_language_codes();
    }
}

if (! function_exists('set_active')) {
    /**
     * Determine if a route is the currently active route.
     *
     * @param  string  $path
     * @param  string  $class
     * @return string
     */
    function set_active($path, $class = 'active')
    {
        return Request::is(config('translation.ui_url').$path) ? $class : '';
    }
}

if (! function_exists('strs_contain')) {
    /**
     * Determine whether any of the provided strings in the haystack contain the needle.
     *
     * @param  array  $haystacks
     * @param  string  $needle
     * @return bool
     */
    function strs_contain($haystacks, $needle)
    {
        $haystacks = (array) $haystacks;

        foreach ($haystacks as $haystack) {
            if (is_array($haystack)) {
                return strs_contain($haystack, $needle);
            } elseif (Str::contains(strtolower($haystack), strtolower($needle))) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('array_diff_assoc_recursive')) {
    /**
     * Recursively diff two arrays.
     *
     * @param  array  $arrayOne
     * @param  array  $arrayTwo
     * @return array
     */
    function array_diff_assoc_recursive($arrayOne, $arrayTwo)
    {
        $difference = [];
        foreach ($arrayOne as $key => $value) {
            if (is_array($value) || $value instanceof Illuminate\Support\Collection) {
                if (! isset($arrayTwo[$key])) {
                    $difference[$key] = $value;
                } elseif (! (is_array($arrayTwo[$key]) || $arrayTwo[$key] instanceof Illuminate\Support\Collection)) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = array_diff_assoc_recursive($value, $arrayTwo[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (! isset($arrayTwo[$key])) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}

if (! function_exists('str_before')) {
    /**
     * Get the portion of a string before a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    function str_before($subject, $search)
    {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }
}

// Array undot
if (! function_exists('array_undot')) {
    /**
     * Expands a single level array with dot notation into a multi-dimensional array.
     *
     * @param array $dotNotationArray
     *
     * @return array
     */
    function array_undot(array $dotNotationArray)
    {
        $array = [];
        foreach ($dotNotationArray as $key => $value) {
            // if there is a space after the dot, this could legitimately be
            // a single key and not nested.
            if (count(explode('. ', $key)) > 1) {
                $array[$key] = $value;
            } else {
                Arr::set($array, $key, $value);
            }
        }

        return $array;
    }
}

if (! function_exists('multi_array_key_exists')) {
    /**
     * Check key exists in nested arrays
     */
    function multi_array_key_exists(array $path, array $array)
    {
        if (empty($path)) {
            return false;
        }
        foreach ($path as $key) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $array = $array[$key];
                continue;
            }
    
            return false;
        }
        return true;
    }
}

if (! function_exists('split_name')) {
    /**
     * Split full name to first and last
     */
    function split_name($name)
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
        return array($first_name, $last_name);
    }
}

if (! function_exists('is_installed')) {
    /**
     * is_installed
     */
    function is_installed()
    {
        $filename = storage_path("installed");
        if (!file_exists($filename)) {
            return false;
        } 

        return true;
    }
}

