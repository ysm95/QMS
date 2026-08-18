<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsDepartment;
use App\Models\QmsLocation;
use App\Models\User;
use App\Support\QmsAdminControlCenter;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request, QmsAdminControlCenter $controlCenter)
    {
        $users = User::query()->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $users->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('qms_role', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        return view('qms.admin.index', [
            'users' => $users->paginate(10)->withQueryString(),
            'departments' => QmsDepartment::orderBy('name')->get(),
            'locations' => QmsLocation::orderBy('name')->get(),
            'summary' => $controlCenter->summary(),
            'workspaces' => $controlCenter->workspaces(),
            'readiness' => $controlCenter->readiness(),
            'evidence' => $controlCenter->evidence(),
            'moduleCounts' => $controlCenter->moduleCounts(),
        ]);
    }
}
