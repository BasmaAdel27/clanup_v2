<?php

namespace App\Services;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media) : string
    {
        $model_name = strtolower(str_replace('App\Models\\', '', $media->model_type));
        return $model_name . '/' . $media->id . '/';
    }

    public function getPathForConversions(Media $media) : string
    {
        return $this->getPath($media).'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'/responsive/';
    }
}
