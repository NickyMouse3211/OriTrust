<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
?>
