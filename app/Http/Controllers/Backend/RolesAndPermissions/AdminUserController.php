<?php

namespace App\Http\Controllers\Backend\RolesAndPermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\District;
use App\Models\Country;
use App\Models\Admin\Master\Divisions;
use App\Models\UserRegion;
class AdminUserController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view user', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create user', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit user', only: ['update']),
            new Middleware(middleware: 'permission:delete user', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.RolesAndPermission.AdminUsers.index');
    }

    public function data()
    {
        $query = User::query()
        ->where('role', 'admin')
        ->with('roles')
        ->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $src = $row->image ? asset('storage/' . $row->image) : asset('images/no-image.png');
                return '<img src="' . $src . '" style="height:50px;width:50px;border-radius:6px;border:1px solid #ddd;">';
            })
            ->editColumn('name', function ($row) {
                $name = $row->name ?? '';
                return $name;
            })
            ->editColumn('email', function ($row) {
                $email = $row->email ?? '';
                return $email;
            })
            ->editColumn('phone', function ($row) {
                $phone = $row->phone ?? '';
                return $phone;
            })
            // ->editColumn('role', function ($row) {
            //     $role = $row->role ?? '';
            //     return $role;
            // })

            ->editColumn('role', function ($row) {
                $roles = $row->roles->pluck('name')->implode(', ');
                return $roles ?: '<span class="badge bg-secondary">No Role Assigned</span>';
            })



            ->editColumn('status', function ($row) {
                return $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            ->addColumn('actions', function ($row) {
                $edit = route('admin.users.edit', $row);

                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                ';
            })
            // allow HTML for these columns
            ->rawColumns(['role','image', 'status', 'actions'])
            ->make(true);
    }

    public function get_Region_District($id)
    {

        $division = Divisions::where('id', $id)->first();
        if (!$division) {
            return response()->json(['error' => 'Division not found'], 404);
        }
        $districtIds = json_decode($division->districts, true);

        if (!is_array($districtIds)) {
            return response()->json(['error' => 'Invalid district data'], 400);
        }

        $districts = District::whereIn('id', $districtIds)
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }

    public function create()
    {
        $data['roles']=Role::all();
        $regions      = DB::table('divisions')->select('id', 'name')->get();
        $districts    = DB::table('districts')->where('state_id', 14)->select('id', 'name')->get();
        $data['regions']   = $regions;
        $data['districts'] = $districts;
        return view('admin.RolesAndPermission.AdminUsers.create',$data);
    }
    public function store(Request $request)
    {
        try {


            $rules = [
                'name'       => 'required|string|max:255',
                'email'      => 'required|email:rfc,dns|unique:users,email',
                'phone'      => 'required|digits:10',
                'password'   => 'required|string|min:8',
                'roles'      => 'required|exists:roles,id',
                'is_visible' => 'required|in:active,inactive',
            ];

    
            if ($request->roles == 11) {
                $rules['region_id']   = 'required|exists:divisions,id';
                $rules['district_id'] = 'required|exists:districts,id';
            }

            $validated = $request->validate($rules);

            /* -----------------------------
             | 3️⃣ CREATE USER
             ----------------------------- */
            $user = new User();
            $user->name     = $validated['name'];
            $user->email    = $validated['email'];
            $user->phone    = $validated['phone'];
            $user->password = Hash::make($validated['password']);
            $user->role     = 'admin';
            $user->status   = $validated['is_visible'];
            $user->save();

            /* -----------------------------
             | 4️⃣ ASSIGN ROLE (SPATIE)
             ----------------------------- */
            $role = Role::where('id', $validated['roles'])
                ->where('guard_name', 'web')
                ->first();

            if (!$role) {
                return back()
                    ->withInput()
                    ->withErrors(['roles' => 'Selected role is not valid.']);
            }

            $user->assignRole($role);

            /* -----------------------------
             | 5️⃣ SAVE REGION (ONLY DY DIRECTOR)
             ----------------------------- */
            if ($validated['roles'] == 11) {

                UserRegion::create([
                    'user_id'     => $user->id,
                    'region_id'   => $validated['region_id'],
                    'district_id' => $validated['district_id'],
                ]);
            }

            /* -----------------------------
             | 6️⃣ SUCCESS
             ----------------------------- */
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Admin user created successfully.');

        } catch (QueryException $e) {

            Log::error('DB error while creating admin user', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Database error occurred. Please try again.'
                ]);

        } catch (Exception $e) {

            Log::error('Unexpected error while creating admin user', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Something went wrong. Please try again.'
                ]);
        }
    }

    public function storekj(Request $request)
    {
        // dd($request->all());
        try {
            // Validation
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email:rfc,dns|unique:users,email',
                'phone'      => 'required|digits:10',
                'password'   => 'required|string|min:8',
                'roles'      => 'required|exists:roles,id',
                'is_visible' => 'required|in:active,inactive',
            ]);

            // User create
            $user = new User();
            $user->name       = $validated['name'];
            $user->email      = $validated['email'];
            $user->phone      = $validated['phone'];
            $user->password   = Hash::make($validated['password']);
            $user->role       = 'admin';
            $user->status = $validated['is_visible'];
            $user->save();

            // Role assign (Spatie)
            if (!empty($validated['roles'])) {
                $role = Role::where('id', $validated['roles'])
                            ->where('guard_name', 'web') // YAHAN guard match kara raha hoon
                            ->first();

                if (!$role) {
                    // role mila hi nahi is guard ke liye
                    return back()
                        ->withInput()
                        ->withErrors(['roles' => 'Selected role is not valid for web guard.']);
                }

                $user->assignRole($role); // id ya model dono chalta hai
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Admin user created successfully.');

        } catch (QueryException $e) {
            Log::error('DB error while creating admin user', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Database error occurred while creating user.',['error' => $e->getMessage()]]);
        } catch (Exception $e) {
            Log::error('Unexpected error while creating admin user', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }



    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::where('guard_name', 'web')->get();

        return view('admin.RolesAndPermission.AdminUsers.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email:rfc,dns|unique:users,email,' . $user->id,
                'phone'      => 'required|digits:10',
                'password'   => 'nullable|string|min:8',
                'roles'      => 'required|exists:roles,id',
                'is_visible' => 'required|in:active,inactive',
            ]);

            $user->name       = $validated['name'];
            $user->email      = $validated['email'];
            $user->phone      = $validated['phone'];
            $user->status = $validated['is_visible'];

            $user->role = 'admin';

            // Only change password if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Update role (Spatie)
            $role = Role::where('id', $validated['roles'])
                        ->where('guard_name', 'web')
                        ->first();

            if (!$role) {
                return back()
                    ->withInput()
                    ->withErrors(['roles' => 'Selected role is not valid for web guard.']);
            }


            $user->syncRoles([$role->name]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Admin user updated successfully.');

        } catch (QueryException $e) {
            Log::error('DB error while updating admin user', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Database error occurred while updating user.']);
        } catch (Exception $e) {
            Log::error('Unexpected error while updating admin user', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
