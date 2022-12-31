<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'available_as_plan_feature',
        'data', 
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'available_as_plan_feature' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Get route key name for binding routes
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Returns all the settings of the group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(IntegrationSetting::class);
    }

    /**
     * Get specified setting of group
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function getSetting($key, $group_id = null)
    {
        return IntegrationSetting::getSetting($key, $this->id, $group_id);
    }

    /**
     * Set/Update specified setting of group
     *
     * @param string $key
     * @param string $value
     * 
     * @return void
     */
    public function setSetting($key, $value, $group_id = null)
    {
        return IntegrationSetting::setSetting($key, $value, $this->id, $group_id);
    }
}
