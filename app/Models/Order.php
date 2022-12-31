<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'amount',
        'currency',
        'transaction_id',
        'payment_type',
        'payment_status',
        'receipt',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
    ];

    /**
     * Define Relation with User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Define Relation with Plan Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class)->withDefault();
    }

    /**
     * Get subscription of the user searched.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchUser($builder, $search)
    {
        return $builder->whereHas('user', function($query) use ($search){
            $query->search($search);
        });
    }
}
