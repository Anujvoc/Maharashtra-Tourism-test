<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles;
    protected $guard_name = 'web';
    protected $fillable = [
        'name',
        'username',
        'registration_id',
        'image',
        'phone',
        'email',
        'role',
        'status',
        'password',
        'aadhar',
        'is_email_verified',
        'is_phone_verified',
        'is_aadhar_verified',
        'phone_verified_at',
        'aadhar_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verified_at' => 'datetime',
            'aadhar_verified_at' => 'datetime',
            'is_email_verified' => 'boolean',
            'is_phone_verified' => 'boolean',
            'is_aadhar_verified' => 'boolean',
        ];
    }

    public static function get_permissionGroups()
    {
        $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;
    }
    public static function get_permissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public function regions()
    {
        return $this->hasMany(\App\Models\UserRegion::class);
    }
}
