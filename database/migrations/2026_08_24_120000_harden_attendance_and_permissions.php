<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    private array $permissions = [
        'user-list',
        'user-create',
        'user-edit',
        'user-delete',
        'attendance-list',
        'attendance-create',
        'attendance-edit',
        'attendance-delete',
        'attendance-report',
    ];

    public function up(): void
    {
        if (Schema::hasTable('attendances')) {
            $duplicates = DB::table('attendances')
                ->select('user_id', 'attendance_date')
                ->groupBy('user_id', 'attendance_date')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if ($duplicates) {
                throw new \RuntimeException(
                    'Cannot add the attendance uniqueness constraint because duplicate employee/date records exist. Resolve duplicate attendance records first, then run migrations again.'
                );
            }

            Schema::table('attendances', function (Blueprint $table) {
                $table->unique(['user_id', 'attendance_date'], 'attendances_user_date_unique');
                $table->index('attendance_date', 'attendances_date_index');
            });
        }

        foreach ($this->permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Preserve the existing application's Admin access while introducing
        // explicit authorization for the previously unprotected modules.
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($this->permissions);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropUnique('attendances_user_date_unique');
                $table->dropIndex('attendances_date_index');
            });
        }

        foreach ($this->permissions as $permission) {
            Permission::where('name', $permission)->where('guard_name', 'web')->delete();
        }
    }
};
