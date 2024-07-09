<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_attendance","view_any_attendance","create_attendance","update_attendance","restore_attendance","restore_any_attendance","replicate_attendance","reorder_attendance","delete_attendance","delete_any_attendance","force_delete_attendance","force_delete_any_attendance","view_coordinate","view_any_coordinate","create_coordinate","update_coordinate","restore_coordinate","restore_any_coordinate","replicate_coordinate","reorder_coordinate","delete_coordinate","delete_any_coordinate","force_delete_coordinate","force_delete_any_coordinate","view_course","view_any_course","create_course","update_course","restore_course","restore_any_course","replicate_course","reorder_course","delete_course","delete_any_course","force_delete_course","force_delete_any_course","view_departement","view_any_departement","create_departement","update_departement","restore_departement","restore_any_departement","replicate_departement","reorder_departement","delete_departement","delete_any_departement","force_delete_departement","force_delete_any_departement","view_professor","view_any_professor","create_professor","update_professor","restore_professor","restore_any_professor","replicate_professor","reorder_professor","delete_professor","delete_any_professor","force_delete_professor","force_delete_any_professor","view_semester","view_any_semester","create_semester","update_semester","restore_semester","restore_any_semester","replicate_semester","reorder_semester","delete_semester","delete_any_semester","force_delete_semester","force_delete_any_semester","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_student","view_any_student","create_student","update_student","restore_student","restore_any_student","replicate_student","reorder_student","delete_student","delete_any_student","force_delete_student","force_delete_any_student","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","view_year","view_any_year","create_year","update_year","restore_year","restore_any_year","replicate_year","reorder_year","delete_year","delete_any_year","force_delete_year","force_delete_any_year","widget_AttendancesChart"]},{"name":"professor","guard_name":"web","permissions":["view_attendance","view_any_attendance","create_attendance","update_attendance","delete_attendance","delete_any_attendance","view_course","view_any_course","view_student","view_any_student"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                // Debug statement to check role creation
                echo 'Processing role: ' . $rolePlusPermission['name'] . PHP_EOL;
                
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                // Debug statement to confirm role creation
                echo 'Created/Found role: ' . $role->name . PHP_EOL;

                if (!blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    // Debug statement to list permissions
                    echo 'Syncing permissions for role: ' . $role->name . ' - Permissions: ' . implode(', ', $rolePlusPermission['permissions']) . PHP_EOL;

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);

                    // Debug statement to confirm permission creation
                    echo 'Created direct permission: ' . $permission['name'] . PHP_EOL;
                }
            }
        }
    }
}
