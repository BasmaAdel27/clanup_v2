<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'integration_id',
        'group_id',
        'option',
        'value'
    ];

    /**
     * Define Relation with Integration Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Define Relation with Group Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Set new or update existing Integration Settings.
     *
     * @param string $key
     * @param string $setting
     * @param string $group_id
     *
     * @return void
     */
    public static function setSetting($key, $setting, $integration_id, $group_id = null): void
    {
        $old = static::whereOption($key)->findByIntegration($integration_id);
        if ($group_id)
            $old = $old->findByGroup($group_id);
        $old = $old->first();

        if ($old) {
            $old->value = $setting;
            $old->save();
            return;
        }

        $set = new IntegrationSetting();
        $set->option = $key;
        $set->value = $setting;
        $set->integration_id = $integration_id;
        $set->group_id = $group_id;
        $set->save();
    }

    /**
     * Get Integration Setting.
     *
     * @param string $key
     * @param string $group_id
     *
     * @return string|null
     */
    public static function getSetting($key, $integration_id, $group_id = null)
    {
        $setting = static::whereOption($key)->findByIntegration($integration_id);
        if ($group_id)
            $setting = $setting->findByGroup($group_id);
        $setting = $setting->first();

        if ($setting) {
            return $setting->value;
        } else {
            return null;
        }
    }

    /**
     * Scope a query to only include settings of a given group.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int $group_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByGroup($query, $group_id)
    {
        $query->where('group_id', $group_id);
    }

    /**
     * Scope a query to only include settings of a given integration.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int $integration_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByIntegration($query, $integration_id)
    {
        $query->where('integration_id', $integration_id);
    }
}
