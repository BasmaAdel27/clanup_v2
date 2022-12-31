<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'option',
        'value'
    ];

    /**
     * Default Company Settings
     *
     * @var array
     */
    public static $defaultSettings = [
        'version' => '1.0.0',

        // Application Settings
        'application_name' => 'Network',
        'application_logo' => '/assets/images/logo.svg',
        'application_favicon' => '/assets/images/favicon.png',
        'meta_description' => 'Find events so you can do more of what matters to you. Or create your own group and meet people near you who share your interests.',
        'meta_keywords' => 'event, community, event management, community management',
        'google_recapthca_key' => '',
        'google_recapthca_secret_key' => '',
        'google_places_api_key' => '',

        // Default Location Settings
        'default_location_city_name' => 'New York',
        'default_location_country_name' => 'US',
        'default_location_latitude' => '40.7128',
        'default_location_longitude' => '-74.0060',

        // Payment Settings
        'application_currency' => 'USD',
        'order_prefix' => 'ORD-',
        'active_payment_gateway' => 'dummy',
        'grace_period' => 7,
        'stripe_publishable_key' => null,
        'stripe_secret_key' => null,
        'stripe_webhook_secret' => null,
        'paypal_client_id' => null,
        'paypal_client_secret' => null,
        'paypal_app_id' => null,
        'paypal_mode' => 'live',
        'paypal_webhook_token' => null,

        // Company Settings
        'company_address' => null,

        // Company Social Accounts
        'facebook_link' => '',
        'twitter_link' => '',
        'instagram_link' => '',
        'pinterest_link' => '',
        'linkedin_link' => '',
        'youtube_link' => '',
        'vimeo_link' => '',
    ];

    /**
     * Set new or update existing System Settings.
     *
     * @param string $key
     * @param string $setting
     *
     * @return void
     */
    public static function setSetting($key, $setting)
    {
        Cache::forget('system_'.$key);
        $old = self::whereOption($key)->first();

        if ($old) {
            $old->value = $setting;
            $old->save();
            return;
        }

        $set = new SystemSetting();
        $set->option = $key;
        $set->value = $setting;
        $set->save();
    }

    /**
     * Set new or update existing System Settings.
     *
     * @param array $array
     *
     * @return void
     */
    public static function setSettings($array)
    {
        foreach ($array as $key => $value) {
            self::setSetting($key, $value);
        }
    }
 
    /**
     * Get Default Company Setting.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getDefaultSetting($key)
    {
        $setting = self::$defaultSettings[$key];

        if ($setting) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Get System Setting.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getSetting($key)
    {
        $setting = static::whereOption($key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return self::getDefaultSetting($key);
        }
    }

    /**
     * Check if Google reCaptcha is active
     * 
     * @return boolean
     */
    public static function isRecaptchaActive()
    {
        return self::getSetting('google_recapthca_key') && self::getSetting('google_recapthca_secret_key');
    }

    /**
     * Check if Facebook Login is active
     * 
     * @return boolean
     */
    public static function isFacebookLoginActive()
    {
        return env('FACEBOOK_CLIENT_ID') && env('FACEBOOK_CLIENT_SECRET');
    }

    /**
     * Check if Google Login is active
     * 
     * @return boolean
     */
    public static function isGoogleLoginActive()
    {
        return env('GOOGLE_CLIENT_ID') && env('GOOGLE_CLIENT_SECRET');
    }

    /**
     * Check if Twitter Login is active
     * 
     * @return boolean
     */
    public static function isTwitterLoginActive()
    {
        return env('TWITTER_CLIENT_ID') && env('TWITTER_CLIENT_SECRET');
    }

    /**
     * Check if Linkedin Login is active
     * 
     * @return boolean
     */
    public static function isLinkedinLoginActive()
    {
        return env('LINKEDIN_CLIENT_ID') && env('LINKEDIN_CLIENT_SECRET');
    }

    /**
     * Check if Any Social Login is active
     * 
     * @return boolean
     */
    public static function isAnySocialLoginActive()
    {
        return self::isFacebookLoginActive() || 
            self::isGoogleLoginActive() || 
            self::isTwitterLoginActive() || 
            self::isLinkedinLoginActive();
    }

    /**
     * Check if Stripe payment is active
     * 
     * @return boolean
     */
    public static function isStripeActive()
    {
        return self::getSetting('active_payment_gateway') == 'stripe' && 
            self::getSetting('stripe_publishable_key') && 
            self::getSetting('stripe_secret_key');
    }

    /**
     * Check if Paypal payment is active
     * 
     * @return boolean
     */
    public static function isPaypalActive()
    {
        return self::getSetting('active_payment_gateway') == 'paypal' && 
            self::getSetting('paypal_client_id') && 
            self::getSetting('paypal_client_secret');
    }

    /**
     * Check if Dummy payment is active
     * 
     * @return boolean
     */
    public static function isDummyPaymentActive()
    {
        return self::getSetting('active_payment_gateway') == 'dummy';
    }

    /**
     * Check if Mollie Gateway is Active
     * 
     * @return boolean
     */
    public static function isTermsActive()
    {
        $terms = Page::findBySlug('terms');
        $privacy = Page::findBySlug('privacy');

        if (!$terms || !$privacy)
            return false;

        if ($terms->is_active && $privacy->is_active) 
            return true;
        else 
            return false;
    }

    // Save Settings on .env file
    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        $str .= "\n";

        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}
