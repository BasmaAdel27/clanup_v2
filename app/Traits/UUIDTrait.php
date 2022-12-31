<?php 

namespace App\Traits;

trait UUIDTrait
{
    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating transaction.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
            if (get_class($item) == 'App\Models\User') {
                $item->username = 'user'.$uid;
            }
        });
    }

    /**
     * Find by uid
     */
    public static function findByUid($uid)
    {
        return self::where('uid', $uid)->firstOrFail();
    }
}
