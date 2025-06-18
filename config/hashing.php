<?php

return [
    'default' => env('HASH_DRIVER', 'bcrypt'),

    'drivers' => [
        'bcrypt' => [
            'driver' => 'bcrypt',
            'rounds' => env('BCRYPT_ROUNDS', 10),
            'verify' => env('HASH_VERIFY', true), // Add this line
        ],
        // ... other drivers
    ],
];