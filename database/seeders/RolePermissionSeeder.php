<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Customer'],
            ['name' => 'Seller'],
            ['name' => 'Super Administrator']
        ];
        foreach($roles as $rows) {
            $rows['guard_name'] = 'web';
            $role = Role::create($rows);
            if($rows['name'] == 'Super Administrator') {
                return $role->givePermissionTo(Permission::whereNotIn('name', ['relate_on_organization', 'relate_on_portal'])->get());
            }
            else if($rows['name'] == 'Customer') {
                $arrPermission  = [
                    'dashboard',
                    'dashboard.dashboard_view',
                    'transaction',
                    'transaction.rfq_view',
                    'transaction.rfq_create',
                    'transaction.quotation_view',
                    'transaction.quotation_update',
                    'transaction.purchase-order_view',
                    'transaction.purchase-order_update',
                    'shipping',
                    'shipping.shipping-order_view',
                    'shipping.shipping-order_update',
                    'invoice',
                    'invoice.invoice_view',
                ];
            } 
            else if($rows['name'] == 'Seller') {
                $arrPermission  = [
                    'dashboard',
                    'dashboard.dashboard_view',
                    'product',
                    'product.product_view',
                    'product.product_update',
                    'product.product_create',
                    'product.product_delete',
                    'transaction',
                    'transaction.rfq_view',
                    'transaction.rfq_update',
                    'transaction.rfq_create',
                    'transaction.quotation_view',
                    'transaction.quotation_update',
                    'transaction.purchase-order_view',
                    'transaction.purchase-order_update',
                    'shipping',
                    'shipping.shipping-order_view',
                    'shipping.shipping-order_update',
                    'invoice',
                    'invoice.invoice_view',
                    'marketing',
                    'marketing.coupon_view',
                    'marketing.coupon_update',
                    'marketing.coupon_create',
                    'marketing.coupon_delete',
                ];
            } 
            $role->givePermissionTo($arrPermission);
        }
    }
}
