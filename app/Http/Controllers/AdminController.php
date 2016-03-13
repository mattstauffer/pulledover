<?php

namespace App\Http\Controllers;

use App\PhoneNumber;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::with(['recordings', 'phoneNumbers', 'friends'])->get();

        return view('admin.index', [
            'users' => $users
        ]);
    }
}
