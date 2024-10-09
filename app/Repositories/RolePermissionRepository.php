<?php 

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class RolePermissionRepository {

    public static function permissionAll()
    {
        $group = Permission::where('name', 'NOT LIKE', '%\_%')->get();
        $all = Permission::where('name', 'LIKE', '%\_%')->where('name', '!=', 'dashboard_view')->get();
        $array = [];
        $i = 0;
        foreach($group as $rows) {
            array_push($array, ['name' => $rows->name, 'child' => []]);
            foreach($all as $var) {
                $explode = explode('.', $var->name);
                if($rows->name == $explode[0]){
                    array_push($array[$i]['child'], [
                        'name'      => $var->name,
                        'display'   => ucwords(str_replace('_', ' ', $explode[1]))
                    ]);
                }
            }
            $i++;
        }
        return $array;
    }

}