<?php

namespace App\Models;

use App\Notifications\User\SubscriptionActivated;
use App\Notifications\User\SubscriptionCancelled;
use App\Services\Period;
use App\Traits\BelongsToPlan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanSubscription extends Model
{
    use SoftDeletes;
    use BelongsToPlan;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
        'canceled_at',
        'data',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancels_at' => 'datetime',
        'canceled_at' => 'datetime',
        'deleted_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Get the owning user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get the plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class)->withDefault();
    }

    /**
     * The subscription may have many usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): hasMany
    {
        return $this->hasMany(PlanSubscriptionUsage::class, 'subscription_id', 'id');
    }

    /**
     * Check if subscription is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return ($this->isCancelled() || $this->isEnded()) ? false : true;
    }

    /**
     * Check if subscription is currently on trial.
     *
     * @return bool
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && now()->lt($this->trial_ends_at);
    }

    /**
     * Check if subscription is currently on grace period.
     *
     * @return bool
     */
    public function onGrace(): bool
    {
        return $this->ends_at && now()->lt($this->ends_at->addDays(get_system_setting('grace_period')));
    }

    /**
     * Check if subscription is canceled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->canceled_at && now()->lte($this->ends_at);
    }

    /**
     * Check if subscription period has ended.
     *
     * @return bool
     */
    public function isEnded(): bool
    {
        return $this->ends_at && now()->gte($this->ends_at);
    }

    /**
     * Get Status Attribute
     * 
     * @return string
     */
    public function getHtmlStatusAttribute()
    {
        if ($this->onTrial()) {
            return '<div class="badge bg-info">'.__('On trial').'</div>';
        } elseif ($this->isActive()) {
            return '<div class="badge bg-success">'.__('Active').'</div>';
        } else if ($this->isCancelled()) {
            return '<div class="badge bg-warning">'.__('Cancelled').'</div>';
        } else if ($this->onGrace()) {
            return '<div class="badge bg-dark">'.__('Grace Period').'</div>';
        } else if ($this->isEnded()) {
            return '<div class="badge bg-danger">'.__('Ended').'</div>';
        } else {
            return '';
        }
    }

    /**
     * Cancel subscription.
     *
     * @param bool $immediately
     *
     * @return $this
     */
    public function cancel($immediately = false)
    {
        $this->canceled_at = now();

        if ($immediately) {
            $this->ends_at = $this->canceled_at;
            $this->trial_ends_at = $this->canceled_at;
        }

        $this->save();

        // Send email notification to user
        $this->user->notify(new SubscriptionCancelled($this));

        // Cancel Paypal
        if (SystemSetting::isPaypalActive() && !empty($this->data) && array_key_exists('billing_agreement_id', $this->data) && $this->data['billing_agreement_id']) {
            $paypal = new \App\Services\Gateways\Paypal();
            $paypal->cancelSubscription($this->user, $this->data['billing_agreement_id']);
        }

        return $this;
    }

    /**
     * Activate subscription.
     *
     * @return $this
     */
    public function activate()
    {
        $this->canceled_at = null;
        $this->save();

        // Send email notification to user
        $this->user->notify(new SubscriptionActivated($this));

        // Activate Paypal
        if (SystemSetting::isPaypalActive() && !empty($this->data) && array_key_exists('billing_agreement_id', $this->data) && $this->data['billing_agreement_id']) {
            $paypal = new \App\Services\Gateways\Paypal();
            $paypal->activateSubscription($this->user, $this->data['billing_agreement_id']);
        }

        return $this;
    }

    /**
     * Set new subscription period.
     *
     * @param string $invoice_interval
     * @param int    $invoice_period
     * @param string $start
     * 
     * @return $this
     */
    protected function setNewPeriod($invoice_interval = '', $invoice_period = '', $start = '')
    {
        if (empty($invoice_interval)) {
            $invoice_interval = $this->plan->invoice_interval;
        }

        if (empty($invoice_period)) {
            $invoice_period = $this->plan->invoice_period;
        }

        $period = new Period($invoice_interval, $invoice_period, $start);

        $this->starts_at = $period->getStartDate();
        $this->ends_at = $period->getEndDate();

        return $this;
    }

    /**
     * Change subscription plan.
     *
     * @param \App\Models\Plan $plan
     *
     * @return $this
     */
    public function changePlan(Plan $plan)
    {
        // Set new period starting from now
        $this->setNewPeriod($plan->invoice_interval, $plan->invoice_period, now());

        // If cancelled before set it active again
        $this->canceled_at = null;

        // Attach new plan to subscription
        $this->plan_id = $plan->getKey();
        $this->save();

        return $this;
    }

    /**
     * Renew subscription period.
     * 
     * @return $this
     */ 
    public function renew()
    {
        $subscription = $this;

        $starts_at = now();
        if (!$this->isEnded()) {
            $starts_at = $this->ends_at;
        }

        // Renew period
        $subscription->setNewPeriod('', '', $starts_at);
        $subscription->canceled_at = null;
        $subscription->save();

        return $this;
    }

    /**
     * Determine if the feature can be used.
     *
     * @param string $featureSlug
     *
     * @return bool
     */
    public function canUseFeature(string $featureSlug): bool
    {
        $featureValue = $this->getFeatureValue($featureSlug);
        $usage = $this->usage()->byFeatureSlug($featureSlug , $this->plan_id)->first();

        if (!$usage) {
            $this->recordFeatureUsage($featureSlug, 0);
            $usage = $this->usage()->byFeatureSlug($featureSlug , $this->plan_id)->first();
        }

        // If the plan feature is unlimited then return true
        if ($featureValue === '-1' || $featureValue === -1 ) {
            return true;
        }

        if ($featureValue === 'true') {
            return true;
        }

        // If the feature value is zero, let's return false since
        // there's no uses available. (useful to disable countable features)
        if ($usage->expired() || is_null($featureValue) || $featureValue === '0' || $featureValue === 'false') {
            return false;
        }

        // Check for available uses
        return $this->getFeatureRemainings($featureSlug) > 0;
    }

    /**
     * Record feature usage.
     *
     * @param string $featureSlug
     * @param int    $uses
     *
     * @return \App\Models\PlanSubscriptionUsage
     */
    public function recordFeatureUsage(string $featureSlug, int $uses = 1, bool $incremental = true): PlanSubscriptionUsage
    {
        $feature = $this->plan->getFeatureBySlug($featureSlug);

        $usage = $this->usage()->firstOrNew([
            'subscription_id' => $this->getKey(),
            'feature_id' => $feature->getKey(),
        ]);

        if ($feature->resettable_period) {
            // Set expiration date when the usage record is new or doesn't have one.
            if (is_null($usage->valid_until)) {
                // Set date from subscription creation date so the reset
                // period match the period specified by the subscription's plan.
                $usage->valid_until = $feature->getResetDate($this->created_at);
                if (Carbon::now()->gte($usage->valid_until)) {
                    $usage->valid_until = $feature->getResetDate(now());
                }
            } elseif ($usage->expired()) { 
                // If the usage record has been expired, let's assign
                // a new expiration date and reset the uses to zero.
                $usage->valid_until = $feature->getResetDate($usage->valid_until);
                $usage->used = 0;
            }
        }

        $usage->used = ($incremental ? $usage->used + $uses : $uses);

        $usage->save();

        return $usage;
    }

    /**
     * Reduce usage.
     *
     * @param string $featureSlug
     * @param int    $uses
     *
     * @return \App\Models\PlanSubscriptionUsage|null
     */
    public function reduceFeatureUsage(string $featureSlug, int $uses = 1): ?PlanSubscriptionUsage
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug , $this->plan_id)->first();

        if (is_null($usage)) {
            return null;
        }

        $usage->used = max($usage->used - $uses, 0);

        $usage->save();

        return $usage;
    }

    /**
     * Get how many times the feature has been used.
     *
     * @param string $featureSlug
     *
     * @return int
     */
    public function getFeatureUsage(string $featureSlug): int
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug , $this->plan_id)->first();

        if (!$usage) {
            return 0;
        }

        return ! $usage->expired() ? $usage->used : 0;
    }

    /**
     * Get the available uses.
     *
     * @param string $featureSlug
     *
     * @return int
     */
    public function getFeatureRemainings(string $featureSlug): int
    {
        return $this->getFeatureValue($featureSlug) - $this->getFeatureUsage($featureSlug);
    }

    /**
     * Get feature.
     *
     * @param string $featureSlug
     *
     * @return mixed
     */
    public function getFeature(string $featureSlug)
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        return $feature ?? null;
    }

    /**
     * Get feature value.
     *
     * @param string $featureSlug
     *
     * @return mixed
     */
    public function getFeatureValue(string $featureSlug)
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        return $feature->value ?? null;
    }

    /**
     * Get subscriptions of the given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser(Builder $builder, Model $user): Builder
    {
        return $builder->where('user_id', $user->getKey());
    }

    /**
     * Get subscriptions of the user searched.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchUser(Builder $builder, $search): Builder
    {
        return $builder->whereHas('user', function($query) use ($search){
            $query->search($search);
        });
    }

    /**
     * Get subscription of the user searched.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByStatus(Builder $builder, $status): Builder
    {
        switch ($status) {
            case 'active':
                return $builder->findActive();
                break;
            case 'cancelled':
                return $builder->findCancelled();
                break;
            case 'on_trial':
                return $builder->findOnTrial();
                break;
            case 'on_grace':
                return $builder->findOnGrace();
                break;
            case 'ended':
                return $builder->findEnded();
                break;
        }
    }

    /**
     * Scope subscriptions with active
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindActive(Builder $builder): Builder
    {
        return $builder->whereNotNull('ends_at')->where('ends_at', '>=', now());
    }

    /**
     * Scope subscriptions with cancelled
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindCancelled(Builder $builder): Builder
    {
        return $builder->where('canceled_at', '<=', now());
    }

    /**
     * Scope subscriptions with not cancelled
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindNotCancelled(Builder $builder): Builder
    {
        return $builder->whereNull('canceled_at');
    }

    /**
     * Scope subscriptions with on trial
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindOnTrial(Builder $builder): Builder
    {
        return $builder->where('trial_ends_at', '>', now());
    }

    /**
     * Scope subscriptions with on grace period
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindOnGrace(Builder $builder): Builder
    {
        return $builder->findNotCancelled()->where('ends_at', '>', now()->subDays(get_system_setting('grace_period')));
    }

    /**
     * Scope only expired subscriptions
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindExpired(Builder $builder): Builder
    {
        return $builder->findNotCancelled()->where('ends_at', '<', now()->subDays(get_system_setting('grace_period')));
    }

    /**
     * Scope subscriptions with ended trial.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEndedTrial(Builder $builder): Builder
    {
        return $builder->where('trial_ends_at', '<=', now());
    }

    /**
     * Scope subscriptions with on trial
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindEnded(Builder $builder): Builder
    {
        return $builder->where('ends_at', '<=', now());
    }
}