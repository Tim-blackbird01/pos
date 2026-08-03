<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class AddMpesaPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::firstOrCreate([
            'name' => 'mpesa.settings',
            'guard_name' => 'web'
        ]);

        Permission::firstOrCreate([
            'name' => 'mpesa.view_transactions',
            'guard_name' => 'web'
        ]);

        Permission::firstOrCreate([
            'name' => 'mpesa.collect_payment',
            'guard_name' => 'web'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('name', [
            'mpesa.settings',
            'mpesa.view_transactions',
            'mpesa.collect_payment'
        ])->delete();
    }
}
