<?php

return [
    'name' => 'Mpesa',
    // Bumped from 1.0.0 -> 1.1.0 so existing installs pick up the new
    // invoice_no column migration via "php artisan module:migrate Mpesa"
    // or the module's "Update" button in Manage Modules.
    'module_version' => '1.1.0',
];
