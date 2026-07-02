<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    private $title = 'Dashboard';

    private $route = 'dashboard.';

    private $header = 'Dashboard';

    private $sub_header = 'Dashboard';

    private $permission = 'dashboard-';

    public function index()
    {
        $user_code = auth_api_user()['user_code'];
        $check_user = User::where('user_code', $user_code)->first();
        if ($check_user == null) {
            return redirect()->route('dashboard.activation');
        } else {
            return view('dashboard.index');
        }
    }

    public function activation()
    {
        $roles = Helper::getRoles();
        $userDetail = auth_api_user();
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'header' => $this->header.' Create',
            'sub_header' => $this->sub_header,
            'permission' => $this->permission,
            'roles' => $roles,
            'user_detail' => $userDetail,
        ];

        return view('user_management.users.activation', $data);
    }
}
