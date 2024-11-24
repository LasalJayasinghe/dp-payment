<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class RequestDetailsModal extends Component
{
    public $requestId;

    public function __construct($requestId = null)
    {
        $this->requestId = $requestId;
    }
    
    public function render()
    {
        return view('components.request-details-modal');
    }
    
}
