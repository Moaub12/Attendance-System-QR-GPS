<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class QrGen extends Controller
{
    public function generateqr(Course $record)
    {
        
        $qr_text= $record->id . ',' . now()->format('Y-m-d');
        return view('qr', compact('qr_text'));

    }
}
