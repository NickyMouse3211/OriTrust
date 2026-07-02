<?php

namespace App\Http\Controllers\UserManagement;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\RoleHasPermission;
use App\Models\Applications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    private $title = 'User Management | Role';
    private $route = 'user_management.roles.';
    private $header = 'Role';
    private $sub_header = 'User Management';
    private $permission = 'roles-';

    public function __construct()
    {
        DB::enableQueryLog();
        $this->middleware('permission:'.$this->permission.'read', ['only' => ['index', 'getData']]);
        $this->middleware('permission:'.$this->permission.'create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$this->permission.'update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$this->permission.'delete', ['only' => ['destroy']]);
        $this->middleware('permission:'.$this->permission.'restore', ['only' => ['restore']]);
    }

    public function index()
    {
        $data = [
            'title'      => $this->title,
            'route'      => $this->route,
            'header'     => $this->header,
            'sub_header' => $this->sub_header,
            'permission'   => $this->permission,
        ];

        return view($this->route . 'index', $data);
    }

    public function getData()
    {
        $query = Role::query()
        ->join('applications as apps', 'apps.apps_code', '=', 'roles.apps_code')
        ->select([
            'roles.*',
            'apps.apps_name',
        ]);

        if ($name = request()->get('name')) {
            $query->where(function ($q) use ($name) {
                $q->where('roles.name', 'like', "%{$name}%")
                ->orWhere('apps.apps_name', 'like', "%{$name}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($query) {

            $btnEdit = !Gate::check($this->permission.'update')
                ? ''
                : "<a href='".route($this->route.'edit', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-warning mx-1 btn-edit'><i class='ti ti-edit'></i> Edit </a>";

            $btnDelete = !Gate::check($this->permission.'delete')
                ? ''
                : "<a href='javascript:;' data-route='".route($this->route.'destroy', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-dark mx-1 btn-delete'><i class='ti ti-trash'></i> Delete </a>";

            return $btnEdit . $btnDelete;
        })
        ->rawColumns(['aksi'])
        ->toJson();
    }

    public function create()
    {
        $permission = Helper::get_permission_by_role();

        $groupedPermission = [];
            
        foreach ($permission as $item) {
            [$groupKey, $permission] = explode('-', $item['name'], 2);
            $groupedPermission[$groupKey][] = $item;
        }

        $data = [
            'title'      => $this->title,
            'route'      => $this->route,
            'header'     => $this->header.' Create',
            'sub_header' => $this->sub_header,
            'data'       => $groupedPermission,
        ];

        return view($this->route . 'create', $data);
    }

    public function store(Request $request)
    {
        $cek_permission_id = ! empty($request['permission_id']);
        // dd($cek_permission_id);
        if ($cek_permission_id) {

            $post = $request->all();
            $rules = [
                'name' => 'required|min:2|unique:roles,name',
                'application' => 'required',
            ];

            $alert = [
                'required' => 'The :attribute is required',
                'min' => ':attribute Min 2 Char',
            ];

            $validator = Validator::make($post, $rules, $alert);

            if ($validator->passes()) {

                DB::beginTransaction();
                $insert_role = Role::create(
                    [
                        'name' => $request['name'],
                        'guard_name' => 'web',
                        'apps_code' => Helper::decryptFromUrl($request['application']),
                    ]
                );
                $data_has_role = [];
                foreach ($request['permission_id'] as $key => $value) {
                    $data_has_role[] = [
                        'permission_id' => $value,
                        'role_id' => $insert_role->id,
                    ];
                }
                $insert_has_role = RoleHasPermission::insert($data_has_role);

                if ($insert_role && $insert_has_role) {
                    DB::commit();
                    $message = 'Successfully';

                    return redirect(route($this->route.'index'))->with('success', Helper::parsing_alert($message));
                } else {
                    DB::rollback();
                    $message = 'Failed';

                    return redirect()->back()->with('error', Helper::parsing_alert($message));
                }
            }

            $message = Helper::parsing_alert($validator->errors()->all());

            return redirect()->back()->with('error', $message)->withInput();
        }
        $message = 'Please filled the permission least one';

        return redirect()->back()->with('error', $message)->withInput();
    }

    public function edit($id)
    {
        $role_id = Hashids::decode($id);
        if (! empty($role_id)) {

            $cek_role = Role::where('id', $role_id[0])->first();
            $cek_application = Applications::where('apps_code', $cek_role->apps_code)->first();

            $permission = Permission::get();
            $groupedPermission = [];
            
            foreach ($permission as $item) {
                [$groupKey, $permissionName] = explode('-', $item['name'], 2);
                $groupedPermission[$groupKey][] = $item;
            }

            $rolePermissions = RoleHasPermission::where('role_id', $cek_role->id)
                ->pluck('permission_id')
                ->toArray();

            if ($cek_role) {
                $data = [
                    'title' => $this->title,
                    'route' => $this->route,
                    'header' => $this->header,
                    'sub_header' => $this->sub_header,
                    'data' => $cek_role,
                    'permission' => $groupedPermission,
                    'rolePermissions' => $rolePermissions,
                    'application' => $cek_application,
                ];

                return view($this->route.'edit', $data);
            }
            $message = 'Id role not found or has been deleted';

            return redirect()->back()->with('error', $message);
        }
        $message = 'ID not found';

        return redirect()->back()->with('error', $message);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|min:2',
            'application' => 'required',
        ];
        $alert = [
            'required' => 'The :attribute is required',
            'min' => ':attribute Min 2 Char',
        ];
        $validator = Validator::make($request->all(), $rules, $alert);

        $role_id = Hashids::decode($request['id']);

        $cek_permission_id = ! empty($request['permission_id']);

        if ($cek_permission_id) {
            if (! empty($role_id)) {
                $cek_role = Role::where('id', $role_id[0])->first();

                if ($cek_role) {
                    if ($validator->passes()) {

                        DB::beginTransaction();
                        $update_role = $cek_role->update([
                            'name' => $request['name'],
                            'apps_code' => Helper::decryptFromUrl($request['application']),
                        ]);

                        RoleHasPermission::where('role_id', $cek_role->id)->delete();

                        $data_has_role = [];
                        foreach ($request['permission_id'] as $key => $value) {
                            $data_has_role[] = [
                                'permission_id' => $value,
                                'role_id' => $cek_role->id,
                            ];
                        }
                        $insert_has_role = RoleHasPermission::insert($data_has_role);

                        if ($update_role && $insert_has_role) {
                            DB::commit();
                            $message = 'Successfully';

                            return redirect(route($this->route.'index'))->with('success', Helper::parsing_alert($message));
                        } else {
                            DB::rollback();
                            $message = 'Failed';

                            return redirect()->back()->with('error', Helper::parsing_alert($message));
                        }
                    }

                    $message = Helper::parsing_alert($validator->errors()->all());

                    return redirect()->back()->with('error', $message);
                } else {
                    $message = 'ID Not Found';

                    return redirect()->back()->with('error', $message);
                }
            } else {
                $message = 'ID cannot be empty';

                return redirect()->back()->with('error', $message);
            }
        }
        $message = 'Permission must be filled least one';

        return redirect()->back()->with('error', $message);
    }

    public function destroy($id)
    {
        $roleId = Hashids::decode($id);

        if (!empty($roleId)) {
            $role = Role::where('id', $roleId[0])->first();

            if ($role) {
                DB::beginTransaction();
                try {
                    // Hapus semua permission yang terkait dengan role ini
                    RoleHasPermission::where('role_id', $role->id)->delete();

                    // Hapus role
                    $delete = $role->delete();

                    if ($delete) {
                        DB::commit();
                        $message = 'Successfully deleted';
                        $response = [
                            'message' => $message,
                            'status' => true,
                        ];
                        return response()->json($response);
                    } else {
                        DB::rollback();
                        $message = 'Failed to delete';
                        $response = [
                            'message' => $message,
                            'status' => false,
                        ];
                        return response()->json($response);
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    $response = [
                        'message' => 'Error: ' . $e->getMessage(),
                        'status' => false,
                    ];
                    return response()->json($response);
                }
            }
            $message = 'ID not found or already deleted';
            $response = [
                'message' => $message,
                'status' => false,
            ];
            return response()->json($response);
        }
        $message = 'ID not found';
        $response = [
            'message' => $message,
            'status' => false,
        ];
        return response()->json($response);
    }
}