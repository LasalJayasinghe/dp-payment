<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class RequestDetailsModal extends Component
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function render()
    {
        return view('components.request-details-modal');
    }
    
}
