<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsDepartment;
use App\Models\QmsLocation;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('qms.admin.index', [
            'users' => User::orderBy('name')->get(),
            'departments' => QmsDepartment::orderBy('name')->get(),
            'locations' => QmsLocation::orderBy('name')->get(),
        ]);
    }
}
