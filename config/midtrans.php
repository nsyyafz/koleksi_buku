<?php
/**
 * Buat file baru: config/midtrans.php
 */

return [

    /**
     * Midtrans Server Key
     * Dapat dari: https://dashboard.midtrans.com/settings/config_info
     */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /**
     * Midtrans Client Key
     * Dapat dari: https://dashboard.midtrans.com/settings/config_info
     */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /**
     * Midtrans Merchant ID
     */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),

    /**
     * Set to true for production environment
     * Set to false for sandbox/testing
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * Enable auto sanitization
     */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    /**
     * Enable 3D Secure
     */
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

];