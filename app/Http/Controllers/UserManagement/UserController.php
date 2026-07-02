<?php

namespace App\Http\Controllers\UserManagement;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $title = 'User Management | User';

    private $route = 'user_management.users.';

    private $header = 'User';

    private $sub_header = 'User Management';

    private $permission = 'users-';

    public function __construct()
    {
        DB::enableQueryLog();
        $this->middleware('permission:'.$this->permission.'read', ['only' => ['index', 'getData']]);
        $this->middleware('permission:'.$this->permission.'create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$this->permission.'update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$this->permission.'delete', ['only' => ['destroy']]);
        $this->middleware('permission:'.$this->permission.'restore', ['only' => ['restore']]);
        $this->middleware('permission:'.$this->permission.'force_delete', ['only' => ['forceDelete']]);
    }

    public function index()
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'header' => $this->header,
            'sub_header' => $this->sub_header,
            'permission' => $this->permission,
        ];

        return view($this->route.'index', $data);
    }

    public function getData()
    {
        $query = User::query();

        if ($name = request()->get('name')) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if ($email = request()->get('email')) {
            $query->where('email', 'like', '%'.$email.'%');
        }

        if ($role = request()->get('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $status = request()->get('status');
        if (request()->has('status') && $status !== '') {
            if ($status == '99') {
                $query->withTrashed();
            } else {
                $query->where('is_active', $status);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('selectedRow', function ($query) {
                $deletedAt = $query->deleted_at;
                if (! empty($deletedAt) || $query->id == 1) {
                    return '';
                }

                return '<input type="checkbox" class="form-check-input select-item" value="'.Hashids::encode($query->id).'">';
            })
            ->addColumn('status', function ($query) {
                if ($query->is_active) {
                    return '<span class="badge rounded-pill text-bg-success">Active</span>';
                } else {
                    return '<span class="badge rounded-pill text-bg-danger">Inactive</span>';
                }
            })
            ->addColumn('aksi', function ($query) {

                $deletedAt = $query->deleted_at;
                if (! empty($deletedAt)) {
                    $btnRestore = ! getPermission($this->permission.'restore') ? '' :
                        "<a href='javascript:;' data-route='".route($this->route.'restore', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-outline-secondary mx-1 btn-restore'><i class='ti ti-refresh'></i> Restore </a>";
                    $btnDelete = ! getPermission($this->permission.'force_delete') ? '' :
                        "<a href='javascript:;' data-route='".route($this->route.'force_delete', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-danger mx-1 btn-force-delete'><i class='ti ti-trash'></i> Force Delete </a>";

                    return $btnRestore.$btnDelete;
                }

                $btnEdit = ! getPermission($this->permission.'update') ? '' :
                    "<a href='".route($this->route.'edit', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-warning mx-1 btn-edit'><i class='ti ti-edit'></i> Edit </a>";

                if ($query->id != 1) {
                    $btnDelete = ! getPermission($this->permission.'delete') ? '' :
                        "<a href='javascript:;' data-route='".route($this->route.'destroy', ['id' => Hashids::encode($query->id)])."' class='btn btn-md btn-dark mx-1 btn-delete'><i class='ti ti-trash'></i> Delete </a>";
                }

                return $btnEdit.($btnDelete ?? '');
            })
            ->rawColumns(['selectedRow', 'aksi', 'status'])
            ->toJson();
    }

    public function create()
    {
        $userDetail = auth_api_user();
        $listRole = Helper::getRoles();
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'header' => $this->header.' Create',
            'sub_header' => $this->sub_header,
            'permission' => $this->permission,
            'user_detail' => $userDetail,
            'listRole' => $listRole,
        ];

        return view($this->route.'create', $data);
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $rules = [
            'name' => 'required',
            'phone' => 'nullable|min:0|digits_between:6,15',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.((int) Helper::staticValue('maximum', 'upload_size') * 1024),
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
        ];

        $alert = [
            'required' => ':attribute is required',
            'name.required' => 'Name is required',
            'phone.digits_between' => 'Phone number must be between 6 and 15 digits',
            'photo.max' => 'Photo size must be less than '.Helper::staticValue('maximum', 'upload_size').'MB',
            'photo.mimes' => 'Photo must be in jpeg,png,jpg,gif,svg format',
            'address.string' => 'Address must be a string',
            'password_confirm.required' => 'Password confirmation is required',
            'password_confirm.same' => 'Password confirmation must match',
        ];

        $validator = Validator::make($post, $rules, $alert);

        if ($validator->passes()) {
            DB::beginTransaction();

            $rolesUser = array_column($post['roles'], 'roles');
            $newUser = Helper::registerUser(
                $post['name'], $post['email'], $post['password'], $rolesUser
            );

            if ($newUser['status'] !== 200) {
                DB::rollBack();

                $errorMsg = '<b>'.$newUser['message'].'</b><br>';
                if (isset($newUser['errors'])) {
                    foreach ($newUser['errors'] as $error) {
                        $errorMsg .= '• '.$error[0].'<br>';
                    }
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', Helper::parsing_alert($errorMsg));
            }
            $photoName = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = $newUser['user_code'].'.'.$photo->getClientOriginalExtension();
                $photo->move(storage_path('app/public/assets/images/users'), $photoName);
            }

            $create_user = User::create([
                'user_code' => $newUser['data']['user_code'],
                'name' => $post['name'],
                'email' => $newUser['data']['email'],
                'password' => Hash::make($newUser['data']['email'].$newUser['data']['user_code']),
                'phone' => $post['phone'],
                'gender' => $post['gender'],
                'birth_date' => DateTime::createFromFormat('d/m/Y', $post['birth_date'])->format('Y-m-d'),
                'address' => $post['address'],
                'avatar' => $photoName,
            ]);

            if ($create_user) {
                DB::commit();
                $message = 'Successfully created user';

                return redirect(route($this->route.'create'))->with('success', Helper::parsing_alert($message));
            } else {
                DB::rollback();
                $message = 'Failed to create user';

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', Helper::parsing_alert($message));
            }
        }

        $message = Helper::parsing_alert($validator->errors()->all());

        return redirect()->back()->with('error', $message)->withInput();
    }

    public function activate(Request $request)
    {
        $post = $request->all();

        $rules = [
            'name' => 'required',
            'phone' => 'nullable|min:0|digits_between:6,15',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.((int) Helper::staticValue('maximum', 'upload_size') * 1024),
        ];

        $alert = [
            'required' => ':attribute is required',
            'name.required' => 'Name is required',
            'phone.digits_between' => 'Phone number must be between 6 and 15 digits',
            'photo.max' => 'Photo size must be less than '.Helper::staticValue('maximum', 'upload_size').'MB',
            'photo.mimes' => 'Photo must be in jpeg,png,jpg,gif,svg format',
            'address.string' => 'Address must be a string',
        ];

        $validator = Validator::make($post, $rules, $alert);

        if ($validator->passes()) {
            DB::beginTransaction();
            $userDetail = auth_api_user();

            $photoName = null;
            if ($request->file('photo')) {
                $photo = $request->file('photo');
                $photoName = $userDetail['user_code'].'.'.$photo->getClientOriginalExtension();
                $photo->move(storage_path('app/public/assets/images/users'), $photoName);
            }

            $create_user = User::create([
                'user_code' => $userDetail['user_code'],
                'name' => $post['name'],
                'email' => $userDetail['email'],
                'password' => Hash::make($userDetail['email'].$userDetail['user_code']),
                'phone' => $post['phone'],
                'gender' => $post['gender'],
                'birth_date' => DateTime::createFromFormat('d/m/Y', $post['birth_date'])->format('Y-m-d'),
                'address' => $post['address'],
                'avatar' => $photoName,
            ]);

            if ($create_user) {
                DB::commit();
                $message = 'Successfully created user';

                return redirect(route('dashboard.index'))->with('success', Helper::parsing_alert($message));
            } else {
                DB::rollback();
                $message = 'Failed to create user';

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', Helper::parsing_alert($message));
            }
        }

        $message = Helper::parsing_alert($validator->errors()->all());

        return redirect()->back()->with('error', $message)->withInput();
    }

    public function edit($id)
    {
        $id = Hashids::decode($id)[0];
        if (! empty($id)) {
            $cek_data = User::where('id', $id)->first();

            if ($cek_data) {

                $userRole = Helper::getRoles($cek_data->user_code);
                $listRole = Helper::getRoles();
                $data = [
                    'title' => $this->title,
                    'route' => $this->route,
                    'header' => $this->header.' Edit',
                    'sub_header' => $this->sub_header,
                    'permission' => $this->permission,
                    'data' => $cek_data,
                    'userRole' => $userRole,
                    'listRole' => $listRole,
                ];

                return view($this->route.'edit', $data);
            }
            $message = 'ID not found or has been deleted';

            return redirect()->back()->with('error', $message);
        }
        $message = 'ID not found';

        return redirect()->back()->with('error', $message);
    }

    public function update(Request $request)
    {
        $post = $request->all();
        $userId = Hashids::decode($post['id'])[0];
        $rules = [
            'name' => 'required',
            'phone' => 'nullable|min:0|digits_between:6,15',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:'.((int) Helper::staticValue('maximum', 'upload_size') * 1024),
        ];

        $alert = [
            'required' => ':attribute is required',
            'name.required' => 'Name is required',
            'phone.digits_between' => 'Phone number must be between 6 and 15 digits',
            'photo.max' => 'Photo size must be less than '.Helper::staticValue('maximum', 'upload_size').'MB',
            'photo.mimes' => 'Photo must be in jpeg,png,jpg,gif,svg format',
            'address.string' => 'Address must be a string',
        ];

        $validator = Validator::make($post, $rules, $alert);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {

                $user = User::findOrFail($userId);

                $userDetail = auth_api_user();

                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = $userDetail['user_code'].'.'.$photo->getClientOriginalExtension();
                    $photo->move(storage_path('app/public/assets/images/users'), $photoName);
                    $user->avatar = $photoName;
                }

                $user->name = $post['name'];
                $user->phone = $post['phone'];
                $user->gender = $post['gender'];
                $user->birth_date = DateTime::createFromFormat('d/m/Y', $post['birth_date'])->format('Y-m-d');
                $user->address = $post['address'];

                $update = $user->save();

                $rolesUser = array_column($post['roles'], 'roles');
                $setRoles = Helper::setRoles($user->user_code, $rolesUser);

                if ($update) {
                    DB::commit();
                    $message = 'Successfully updated user';

                    return redirect(route($this->route.'index'))
                        ->with('success', Helper::parsing_alert($message));
                } else {
                    DB::rollback();
                    $message = 'Failed to update user';

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', Helper::parsing_alert($message));
                }
            } catch (\Exception $e) {
                DB::rollback();
                $message = 'Failed to update user';

                return redirect()->back()->with('error', Helper::parsing_alert($message));
            }
        }

        $message = Helper::parsing_alert($validator->errors()->all());

        return redirect()->back()->with('error', $message)->withInput();
    }

    private function deleteSingle($encodedId)
    {
        $id = Hashids::decode($encodedId);
        if (empty($id)) {
            return ['status' => false, 'message' => 'ID cannot be decoded'];
        }

        $user = User::find($id[0]);
        if (! $user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $user->is_active = 0;
        $user->save();

        if ($user->delete()) {
            return ['status' => true, 'message' => 'Deleted'];
        } else {
            return ['status' => false, 'message' => 'Failed to delete'];
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $result = $this->deleteSingle($id);
        if ($result['status']) {
            DB::commit();

            return response()->json(['message' => $result['message'], 'status' => true]);
        } else {
            DB::rollBack();

            return response()->json(['message' => $result['message'], 'status' => false]);
        }
    }

    public function allDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided', 'status' => false]);
        }

        DB::beginTransaction();
        foreach ($ids as $encodedId) {
            $result = $this->deleteSingle($encodedId);
            if (! $result['status']) {
                DB::rollBack();

                return response()->json([
                    'message' => "Failed to delete item: {$encodedId}. Reason: {$result['message']}",
                    'failed_id' => $encodedId,
                    'status' => false,
                ]);
            }
        }

        DB::commit();

        return response()->json(['message' => 'Successfully deleted selected users', 'status' => true]);
    }

    public function restore($id)
    {
        $id = Hashids::decode($id)[0];

        if (! empty($id)) {
            $cek_data = User::withTrashed()->where('id', $id)->first();

            if ($cek_data) {
                DB::beginTransaction();

                $cek_data->is_active = 1;
                $cek_data->save();

                $restore = $cek_data->restore();

                if ($restore) {
                    DB::commit();
                    $message = 'Successfully restored';
                    $response = [
                        'message' => $message,
                        'status' => true,
                    ];

                    return response()->json($response);
                } else {
                    DB::rollback();
                    $message = 'Failed to restore';
                    $response = [
                        'message' => $message,
                        'status' => false,
                    ];

                    return response()->json($response);
                }
            } else {
                $message = 'ID not found or has been deleted';

                return redirect()->back()->with('error', $message);
            }
        } else {
            $message = 'ID cannot be empty';

            return redirect()->back()->with('error', $message);
        }
    }

    public function forceDelete($id)
    {
        $id = Hashids::decode($id)[0];

        if (! empty($id)) {
            $cek_data = User::withTrashed()->where('id', $id)->first();

            if ($cek_data) {
                DB::beginTransaction();
                $forceDelete = $cek_data->forceDelete();

                if ($forceDelete) {
                    DB::commit();
                    $message = 'Successfully permanently deleted';
                    $response = [
                        'message' => $message,
                        'status' => true,
                    ];

                    return response()->json($response);
                } else {
                    DB::rollback();
                    $message = 'Failed to permanently delete';
                    $response = [
                        'message' => $message,
                        'status' => false,
                    ];

                    return response()->json($response);
                }
            } else {
                $message = 'ID not found or has been deleted';

                return redirect()->back()->with('error', $message);
            }
        } else {
            $message = 'ID cannot be empty';

            return redirect()->back()->with('error', $message);
        }
    }
}
