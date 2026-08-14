<?php

return [
    /* Keep this false until KRA has approved and activated the OSCU device. */
    'enabled' => env('ETIMS_ENABLED', false),
    'environment' => env('ETIMS_ENVIRONMENT', 'sandbox'),
    'base_url' => env('ETIMS_BASE_URL') ?: (
        env('ETIMS_ENVIRONMENT', 'sandbox') === 'production'
            ? 'https://etims-api.kra.go.ke/etims-api/'
            : 'https://etims-api-sbx.kra.go.ke/etims-api/'
    ),
    'timeout' => (int) env('ETIMS_TIMEOUT', 20),

    // Issued/provided during OSCU onboarding and device activation.
    'tin' => env('ETIMS_TIN'),
    'branch_id' => env('ETIMS_BRANCH_ID', '00'),
    'device_serial_number' => env('ETIMS_DEVICE_SERIAL_NUMBER'),
    'communication_key' => env('ETIMS_COMMUNICATION_KEY'),

    // Defaults used when a product does not have a more specific eTIMS mapping.
    // Use codes supplied by KRA for your inventory; do not assume these defaults
    // are correct for every product category.
    'item_code_prefix' => env('ETIMS_ITEM_CODE_PREFIX', ''),
    'item_classification_code' => env('ETIMS_ITEM_CLASSIFICATION_CODE'),
    'package_unit_code' => env('ETIMS_PACKAGE_UNIT_CODE', 'NT'),
    'quantity_unit_code' => env('ETIMS_QUANTITY_UNIT_CODE', 'U'),
    'default_tax_code' => env('ETIMS_DEFAULT_TAX_CODE', 'B'),
    'zero_tax_code' => env('ETIMS_ZERO_TAX_CODE', 'A'),
    'default_payment_code' => env('ETIMS_DEFAULT_PAYMENT_CODE', '01'),
    'tax_rates' => json_decode(env('ETIMS_TAX_RATES', '{"A":0,"B":16}'), true) ?: ['A' => 0, 'B' => 16],
];
