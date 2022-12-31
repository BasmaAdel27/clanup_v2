<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'price',
        'trial_period',
        'trial_interval',
        'invoice_period',
        'invoice_interval',
        'order',
        'paypal_plan_id',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'price' => 'double',
        'trial_period' => 'integer',
        'trial_interval' => 'string',
        'invoice_period' => 'integer',
        'invoice_interval' => 'string',
        'deleted_at' => 'datetime',
        'order' => 'integer',
    ];

    /**
     * The plan may have many features.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'plan_id', 'id');
    }

    /**
     * The plan may have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PlanSubscription::class, 'plan_id', 'id');
    }

    /**
     * Create Plan Features
     *
     * @param array $features
     * 
     * @return void
     */
    public function addPlanFeatures($features): void
    {
        // Create new Plan Features
        foreach ($features as $feature => $value) {
            $this->features()->create(['slug' => $feature, 'value' => $value]);
        }
    }

    /**
     * Update Plan Features
     * 
     * @param array $features
     *
     * @return void
     */
    public function updatePlanFeatures($features): void
    {
        // Update new Plan Features
        foreach ($features as $feature => $value) {
            PlanFeature::updateOrCreate(
                ['plan_id' => $this->id, 'slug' => $feature],
                ['value' => $value]
            );
        }
    }

    /**
     * Get Currency Attribute
     *
     * @return string
     */
    public function getCurrencyAttribute(): string
    {
        return get_system_setting('application_currency');
    }

    /**
     * Get Yearly Plan Attribute
     *
     * @return \App\Models\Plan
     */
    public function getYearlyAttribute(): ?Plan
    {
        $yearly = Plan::whereSlug($this->slug.'-yearly')->first();

        return $yearly ?? $this;
    }

    /**
     * Check if plan is free.
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return (float) $this->price <= 0.00;
    }

    /**
     * Check if plan has trial.
     *
     * @return bool
     */
    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_interval;
    }

    /**
     * Get plan feature by the given slug.
     *
     * @param string $featureSlug
     *
     * @return \App\Models\PlanFeature|null
     */
    public function getFeatureBySlug(string $featureSlug)
    {
        return $this->features()->where('slug', $featureSlug)->first();
    }

    /**
     * Scope active plans
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('is_active', 1);
    }
}
