<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Encrypted casts are persisted as encrypted strings, which are not valid
     * JSON. Convert databases that ran the original eTIMS migration already.
     */
    public function up()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `business` MODIFY `etims_settings` LONGTEXT NULL');
        }
    }

    /**
     * No safe rollback exists: any saved encrypted value cannot be converted
     * back to a JSON column without discarding it.
     */
    public function down()
    {
        // Intentionally left unchanged to preserve encrypted eTIMS credentials.
    }
};
