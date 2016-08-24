<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->delete();

        $users =
        [
            ['id' => 1, 'name' => 'seller',  'email' => 'seller@seller.com', 'password' => bcrypt('123456')],
            ['id' => 2, 'name' => 'buyer', 'email' => 'buyer@buyer.com', 'password' => bcrypt('123456')],
            ['id' => 3, 'name' => 'supplier', 'email' => 'supplier@supplier.com', 'password' => bcrypt('123456')]
        ];

        User::insert($users);

        DB::table('roles')->delete();

        $roles =
        [
            ['id' => 1, 'name' => 'seller','display_name' => 'seller', 'description' => 'This is administartor. Administrator can manage all modules.'],
            ['id' => 2, 'name' => 'buyer','display_name' => 'buyer Resource', 'description' => 'This is HR. HR can manage some of modules.'],
            ['id' => 3, 'name' => 'supplier','display_name' => 'supplier Resource', 'description' => 'This is HR. HR can manage some of modules.']
        ];

        Role::insert($roles);

        DB::table('role_user')->delete();

        $role_user =
        [
            ['user_id' => 1, 'role_id' => '1'],
            ['user_id' => 2, 'role_id' => '2'],
            ['user_id' => 3, 'role_id' => '3']
        ];

        DB::table('role_user')->insert($role_user);


        // Permission
        DB::table('permissions')->delete();

        $permission =
        [
            ['id' => 1, 'name' => 'group1', 'display_name' => 'Administrator', 'description' => 'This is administartor. Administrator can manage all modules.'],
            ['id' => 2, 'name' => 'group2','display_name' => 'HR','description' => 'This is HR. HR can manage some of modules.'],
            ['id' => 3, 'name' => 'group3','display_name' => 'HR','description' => 'This is HR. HR can manage some of modules.']
        ];

        Permission::insert($permission);

        DB::table('permission_role')->delete();

        // Permission Role
        $permission_role =
        [
            ['permission_id' => 1, 'role_id' => '1'],
            ['permission_id' => 2, 'role_id' => '2'],
            ['permission_id' => 3, 'role_id' => '3']
        ];

        DB::table('permission_role')->insert($permission_role);

    

    }
}
