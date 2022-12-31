<?php

namespace App\Http\Controllers\Application\Components\Common;

use Livewire\Component;

class ShareModal extends Component
{
    public $show_modal = false;
    public $url;

    protected $listeners = ['showShareModal' => 'showShareModalListener'];

    public function close_modal()
    {
        $this->show_modal = false;
    }

    public function show_share_modal()
    {
        $this->show_modal = true;
    }

    public function showShareModalListener($url = '')
    {
        $this->url = $url;
        $this->show_share_modal();
    }

    public function render()
    {
        return view('application.components.common.share-modal');
    }
}