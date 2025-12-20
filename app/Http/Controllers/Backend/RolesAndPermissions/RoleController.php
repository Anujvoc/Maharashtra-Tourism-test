<?php

namespace App\Http\Controllers\Backend\RolesAndPermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Exception;
use Yajra\DataTables\Facades\DataTables;
class RoleController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view roles', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create roles', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit roles', only: ['update']),
            new Middleware(middleware: 'permission:delete roles', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $search = $request->string('q');
        $roles = Role::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        return view('admin.RolesAndPermission.roles.index', compact('roles', 'search'));
    }


    public function create()
    {
        $title = "Create Roles";
        return view('admin.RolesAndPermission.roles.add', compact('title'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'unique:roles,name',
                ]
            ]);

            Role::Create([
                'name' => $request->name,
                'status' => $request->is_visible,
            ]);
            return redirect()->route('admin.roles.index')->with('success', 'Role Created Successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }


    public function show(string $id)
    {
        //
    }

    public function edit(Role $role)
    {
        return view('admin.RolesAndPermission.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'unique:roles,name,' . $role->id,
                ]
            ]);

            $role->update([
                'name' => $request->name,
                'status' => $request->is_visible,
            ]);

            return redirect()->route('admin.roles.index')->with('success', 'Role Updated Successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }


    public function destroy(string $id)
    {
        $Role = Role::find($id);
        if ($Role) {
            $Role->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Role has been deleted successfully.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Role not found.'
        ]);
    }

    public function AllRolesPermission(Request $request)
{
    $search = $request->string('q');

    $roles = Role::with('permissions') // important
        ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
        ->orderBy('name')
        ->orderByDesc('created_at')
        ->paginate(15)
        ->withQueryString();

    return view('admin.RolesAndPermission.AssignRolePermission.all_permission', compact('roles', 'search'));
}

    public function AddRolesPermission()
    {
        $title = "Add Roles Permission";
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::get_permissionGroups();
        return view('admin.RolesAndPermission.AssignRolePermission.add_permissions', compact('roles', 'permissions', 'permission_groups', 'title'));
    }
    public function RolePermissionStore(Request $request)
    {

        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'permission' => 'required|array',
                'permission.*' => 'exists:permissions,id',
            ], [
                'role_id.required' => 'Please select a role.',
                'role_id.exists' => 'The selected role is invalid.',
                'permission.required' => 'Please select at least one permission.',
                'permission.array' => 'Invalid permission format.',
                'permission.*.exists' => 'One or more selected permissions are invalid.',
            ]);

            $role = Role::find($request->role_id);
            if (!$role) {
                return redirect()
                    ->back()
                    ->with('error', 'Selected role not found.')
                    ->withInput();
            }

            DB::beginTransaction();
            DB::table('role_has_permissions')
                ->where('role_id', $role->id)
                ->delete();

            $insertData = [];
            foreach ($request->permission as $permissionId) {
                $insertData[] = [
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ];
            }

            DB::table('role_has_permissions')->insert($insertData);

            DB::commit();

            return redirect()
                ->route('admin.all.roles.permission')
                ->with('success', 'Permissions assigned to role successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RolePermissionStore error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'role_id' => $request->role_id ?? null,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while assigning permissions. Please try again.')
                ->withInput();
        }
    }
    public function editRolesPermission($id)
    {
        $title = "Edit Roles Permission";
        $roles = $Role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::get_permissionGroups();
        return view('admin.RolesAndPermission.AssignRolePermission.edit_permissions', compact('roles', 'permissions', 'permission_groups', 'title'));
    }


   

    public function RolePermissionUpdate(Request $request)
    {
        try {
            $request->validate([
                'role_id'     => 'required|exists:roles,id',
                'permission'  => 'required|array',
                'permission.*' => 'required|string|exists:permissions,name',
            ], [
                'role_id.required'        => 'Please select a role.',
                'role_id.exists'          => 'The selected role is invalid.',
                'permission.required'     => 'Please select at least one permission.',
                'permission.array'        => 'Invalid permission format.',
                'permission.*.exists'     => 'One or more selected permissions are invalid.',
            ]);

            $role = Role::findOrFail($request->role_id);

            $permissionNames = collect($request->permission)
                ->map(fn($n) => trim($n))
                ->filter()
                ->unique()
                ->values()
                ->all();

            DB::beginTransaction();
            $role->syncPermissions($permissionNames);

            DB::commit();

            return redirect()
                ->route('admin.all.roles.permission')
                ->with('success', 'Permissions updated for role: ' . $role->name);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('RolePermissionUpdate error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'role_id' => $request->role_id ?? null,
            ]);
            return back()->with('error', 'Something went wrong while updating permissions.')->withInput();
        }
    }

    public function RolePermissionDelete($id){

        $role = Role::findOrFail($id);
        if ($role) {
            $role->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Role Permissions Deleted Successfully.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Role Permissions not found.'
        ]);

    }

    public function addPermissionToRole($roleId)
    {
        $title = "Edit Roles";
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'title' => $title,
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);
        return redirect()->back()->with('success', 'Permissions Added to Role Successfully');
    }
}
