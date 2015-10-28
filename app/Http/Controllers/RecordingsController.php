<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordingsController extends Controller
{
    public function index()
    {
        return view('recordings.index')
            ->with('recordings', Auth::user()->recordings);
    }
}
