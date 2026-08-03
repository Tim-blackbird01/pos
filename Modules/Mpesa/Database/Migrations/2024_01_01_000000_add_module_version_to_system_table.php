<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $exists = DB::table('system')
            ->where('key', 'mpesa_version')
            ->exists();

        if (!$exists) {
            DB::table('system')->insert([
                'key' => 'mpesa_version',
                'value' => config('mpesa.module_version'),
            ]);
        }
    }

    public function down()
    {
        DB::table('system')
            ->where('key', 'mpesa_version')
            ->delete();
    }
};