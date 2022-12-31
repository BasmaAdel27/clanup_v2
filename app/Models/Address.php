<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 
        'role', 
        'name',
        'address_1',
        'address_2',
        'country_id',
        'country',
        'city',
        'state',
        'zip',
        'lat',
        'lng'
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $hidden = [
        'model_type',
        'model_id',
        'role',
    ];

    /**
     * Define Relation with Addressable Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Define Relation with Country Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Change the role of the current address model.
     *
     * @param string $role
     *
     * @return bool
     */
    public function role(string $role)
    {
        return $this->update(compact('role'));
    }

    /**
     * Scope a query to only include nearby addresses.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsWithinMaxDistance($query, $latitude, $longitude, $radius = 5) {

        $haversine = "(6371 * acos(cos(radians(" . $latitude . ")) 
                        * cos(radians(`lat`)) 
                        * cos(radians(`lng`) 
                        - radians(" . $longitude . ")) 
                        + sin(radians(" . $latitude . ")) 
                        * sin(radians(`lat`))))";
    
        return $query->select('id', 'model_type', 'model_id')
                     ->selectRaw("{$haversine} AS distance")
                     ->whereRaw("{$haversine} < ?", [$radius]);
    }
}
