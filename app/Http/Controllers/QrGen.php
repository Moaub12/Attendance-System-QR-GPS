<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class QrGen extends Controller
{
    public function generateqr(Course $record)
    {
        $userTimezone = 'Asia/Beirut';
        date_default_timezone_set($userTimezone);
        $qr_text= $record->id . ',' . now();
        return view('qr', compact('qr_text'));

    }
}
