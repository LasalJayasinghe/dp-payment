<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class RequestDetailsModal extends Component
{
    public $rq_id;

    public function __construct($rq_id = null)
    {
        $this->rq_id = $rq_id;
    }
    
    public function render()
    {
        return view('components.request-details-modal');
    }
    
}
