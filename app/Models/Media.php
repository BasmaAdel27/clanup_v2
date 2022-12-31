<?php

namespace App\Models;

use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * Define Relation with User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCreatedByAttribute()
    {
        $created_by_id = $this->getCustomProperty('created_by_id');
        return $created_by_id ? User::find($created_by_id) : null;
    }
}
