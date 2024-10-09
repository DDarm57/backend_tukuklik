<?php 

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use Illuminate\Support\Facades\Auth;

class RolePermissionService {

    public static function validatePermission($route, $method)
    {
        $allowed = [
            'POST'      => 'Create',
            'PUT'       => 'Update',
            'DELETE'    => 'Delete',
            'GET'       => 'View'
        ];
        $route = explode('.', $route)[0] ?? $route;
        $permissionName = "";
        $permissionDisplay = ucfirst($route).' '.$allowed[$method];
        $permissionAll = RolePermissionRepository::permissionAll();
        foreach($permissionAll as $permission) {
            foreach($permission['child'] as $child) {
                $child['display'] == $permissionDisplay ? $permissionName = $child['name'] : '';
            }
        }
        return Auth::user()->hasPermissionTo($permissionName) ?? false;
    }
}