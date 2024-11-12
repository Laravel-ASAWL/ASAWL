<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sheets\Sheets;

class SheetsController extends Controller
{
    public function index(Sheets $sheets)
    {
        return view('sheets', [
            'sheets' => $sheets->all(),
        ]);
    }
}
