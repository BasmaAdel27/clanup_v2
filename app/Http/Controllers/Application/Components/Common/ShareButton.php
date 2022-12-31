<?php

namespace App\Http\Controllers\Application\Components\Common;

use Livewire\Component;

class ShareButton extends Component
{
    public $button = true;
    public $icon_class = '';
    public $url = '';

    public function render()
    {
        return view('application.components.common.share-button');
    }
}