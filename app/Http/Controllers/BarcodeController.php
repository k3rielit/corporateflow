<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class BarcodeController extends Controller
{

    public function __invoke(Request $request, string $value)
    {
        return Blade::render(
            '<x-barcode::clubcard :value="\'' . $value . '\'"/>',
            deleteCachedView: true
        );
    }

}
