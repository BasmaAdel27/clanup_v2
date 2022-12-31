<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class GroupSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'option',
        'value'
    ];

    /**
     * Default Group Settings
     *
     * @var array
     */
    public static function getDefaultSettings() {
        return [
            'new_members_need_approved' => 0,
            'new_members_need_pp' => 0,
            'allow_members_create_discussion' => 0,
            'welcome_message' => null,
            'facebook_url' => null,
            'twitter_url' => null,
            'linkedin_url' => null,
            'instagram_url' => null,
            'website_url' => null,
        ];
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
     * Set new or update existing Group Settings.
     *
     * @param string $key
     * @param string $setting
     * @param string $group_id
     *
     * @return void
     */
    public static function setSetting($key, $setting, $group_id): void
    {
        $old = self::whereOption($key)->findByGroup($group_id)->first();

        if ($old) {
            $old->value = $setting;
            $old->save();
            return;
        }

        $set = new GroupSetting();
        $set->option = $key;
        $set->value = $setting;
        $set->group_id = $group_id;
        $set->save();
    }

    /**
     * Get Default Group Setting.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getDefaultSetting($key)
    {
        $defaultSettings = self::getDefaultSettings();
        $setting = $defaultSettings[$key];

        if ($setting) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Get Group Setting.
     *
     * @param string $key
     * @param string $group_id
     *
     * @return string|null
     */
    public static function getSetting($key, $group_id)
    {
        $setting = static::whereOption($key)->findByGroup($group_id)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return self::getDefaultSetting($key);
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
}
