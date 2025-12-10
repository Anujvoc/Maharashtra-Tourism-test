<?php

namespace App\Http\Controllers\Backend\RolesAndPermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

// use Illuminate\Routing\Controllers\HasMiddleware;
// use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view permission', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create permission', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit permission', only: ['update']),
            new Middleware(middleware: 'permission:delete permission', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $search = $request->string('q');
        $permissions = Permission::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.RolesAndPermission.Permission.index', compact('permissions', 'search'));
    }

    public function create()
    {
        // Fetch permissions grouped by group name
        $permissionGroups  = Permission::all()->groupBy('permission_group');
        return view('admin.RolesAndPermission.Permission.add', compact('permissionGroups'));
    }



    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
                'guard_name' => ['nullable', 'string'],
                'group_name' => ['required', 'string'],
            ]);

            Permission::create([
                'name' => $data['name'],
                'group_name' => $data['group_name'],
                'guard_name' => $data['guard_name'] ?? config('auth.defaults.guard', 'web'),
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Permission store error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while creating the permission. Please try again.')
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.RolesAndPermission.Permission.edit', compact('permission'));
    }


    public function update(Request $request, Permission $permission)
    {
        try {

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
                'group_name' => ['required', 'string', 'max:255'],
            ]);

            $permission->update([
                'name' => $data['name'],
                'group_name' => $data['group_name'],
            ]);

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while updating the permission. Please try again.')
                ->withInput();
        }
    }


    public function destroy(string $id)
    {
        $Permission = Permission::find($id);
        if ($Permission) {
            $Permission->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Permission has been deleted successfully.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Permission not found.'
        ]);
    }
}
