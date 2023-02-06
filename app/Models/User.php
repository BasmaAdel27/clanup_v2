<?php

namespace App\Models;

use App\Services\Notification\Notifiable;
use App\Traits\HasAddresses;
use App\Traits\HasMembership;
use App\Traits\HasSubscription;
use App\Traits\HasTopics;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail , HasMedia
{
    use Notifiable;
    use HasAddresses;
    use HasTopics;
    use Impersonate;
    use HasMediaTrait;
    use HasSubscription;
    use HasMembership;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'role',
        'timezone',
        'username',
        'provider_id',
        'provider',
        'stripe_customer_id',
        'stripe_pm_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return bool
     */

    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function canBeImpersonated()
    {
        return !$this->isAdmin();
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    /**
     * Returns all the saved events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saves()
    {
        return $this->hasMany(EventSaved::class);
    }

    /**
     * Returns all the RSVP of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rsvp()
    {
        return $this->hasMany(EventRSVP::class);
    }

    /**
     * Returns all the Discussions of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Define Relation with UserSetting Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    /**
     * Get User Specified setting
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getSetting($key)
    {
        return Cache::remember('user_'.$this->id.'_'.$key, 3600, function () use ($key) {
            return UserSetting::getSetting($key, $this->id);
        });
    }

    /**
     * Set User Specified setting
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setSetting($key, $value)
    {
        Cache::forget('user_'.$this->id.'_'.$key);
        return UserSetting::setSetting($key, $value, $this->id);
    }

    /**
     * Set Multiple User Specified setting
     *
     * @param array $array
     *
     * @return void
     */
    public function setSettings($array)
    {
        foreach ($array as $key => $value) {
            if (empty($value)) $value = null;
            $this->setSetting($key, $value);
        }
    }

    /**
     * Returns true if the user is attending this event
     *
     * @return boolean
     */
    public function isAttending($event)
    {
        return $this->rsvp()->forEvent($event)->attending()->exists();
    }

    /**
     * Returns true if the user has saved the event already
     *
     * @return boolean
     */
    public function isSaved($event)
    {
        return $this->saves()->forEvent($event)->exists();
    }

    /**
     * Delete the saved event from saved list
     *
     * @return boolean
     */
    public function removeSave($event)
    {
        return $this->saves()->forEvent($event)->delete();
    }

    /**
     * Returns true if the user is admin
     */
    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    /**
     * Get Full Name Attribute
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Return Default User Avatar Url
     *
     * @return string (url)
     */
    public function getDefaultAvatar()
    {
        return asset('assets/images/default-avatar.png');
    }

    /**
     * Get the avatar attribute
     *
     * @return url
     */
    public function getAvatarAttribute()
    {
        $last_media = $this->getMedia()->last();
        return  $last_media ? $last_media->getFullUrl() : $this->getDefaultAvatar();
    }

    /**
     * Get Birthdate Attribute
     *
     * @return string
     */
    public function getBirthdateAttribute()
    {
        $birthdate = $this->getSetting('birthdate');
        return $birthdate ? Carbon::createFromFormat('Y-m-d', $birthdate)->format('F j, Y') : '-';
    }

    /**
     * Get User's Locale
     *
     * @return string (url)
     */
    public function getLocaleAttribute()
    {
        $locale = $this->getSetting('locale');
        return $locale ? $locale : config('app.locale');
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Scope query with all the given search param.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $param
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $builder, $param): Builder
    {
        if (!empty($param)) {
            $builder->Where('email', 'like', '%'.$param.'%')
                ->orWhere(DB::raw('CONCAT_WS(" ", `first_name`, `last_name`)'), 'like', '%' . $param . '%');
        }

        return $builder;
    }
}
