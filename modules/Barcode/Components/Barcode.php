<?php

namespace Modules\Barcode\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Barcode extends Component
{

    public function __construct(
        public mixed $value = 'Laravel',
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('barcode::barcode');
    }

}
