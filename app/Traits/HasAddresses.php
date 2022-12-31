<?php

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAddresses
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'model');
    }

    /**
     * @param string $role
     * @param array $address
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function setAddress(string $role, $address)
    {
        if (is_array($address)) {
            $address = $this->addresses()->create($address);
        }

        if ($address instanceof Model) {
            $address->role($role);
        }

        return $this->addresses()->whereRole($role)->first();
    }

    /**
     * @param string $role
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateAddress(string $role, $address = null)
    {
        if ($this->hasAddress($role)) {
            $a = $this->addresses()->whereRole($role)->first();
            $a->update($address);
            return $a;
        }

        return $this->setAddress($role, $address);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getAddressAttribute(): ?Model
    {
        return $this->hasAddress('main') 
            ? $this->addresses()->whereRole('main')->first()
            : new Address();
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasAddress(string $role): bool
    {
        return !empty($this->setAddress($role, null));
    }
}