<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Laravel-admin auth setting
  |--------------------------------------------------------------------------
  |
  | Authentication settings for all admin pages. Include an authentication
  | guard and a user provider setting of authentication driver.
  |
  | You can specify a controller for `login` `logout` and other auth routes.
  |
  */
  'auth' => [

      'controller' => App\Http\Controllers\AuthController::class,

      'guards' => [
          'schoolconnect' => [
              'driver'   => 'passport',
              'provider' => 'schoolconnect',
          ],
      ],

      'providers' => [
          'schoolconnect' => [
              'driver' => 'eloquent',
              'model'  => App\Http\Models\User::class,
          ],
      ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Laravel-admin database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for laravel-admin builtin model & tables.
    |
    */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        //List table
        'user_table' => env('DB_PREFIX', '').'user',
        'user_profile_table' => env('DB_PREFIX', '').'user_profile',
        'location_table' => env('DB_PREFIX', '').'locations',
        'school_table' => env('DB_PREFIX','').'school',
        'school_level_table' => env('DB_PREFIX','').'school_level',
    ],
];
