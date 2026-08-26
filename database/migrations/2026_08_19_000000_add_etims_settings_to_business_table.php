<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            // The Business model stores this as an encrypted array, so the
            // database must accept the encrypted string rather than JSON.
            $table->longText('etims_settings')->nullable()->after('email_settings');
        });
    }

    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('etims_settings');
        });
    }
};
