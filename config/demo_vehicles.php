<?php

// Minimal placeholder demo vehicles to keep views rendering in offline mode.
return [
    // Example structure used by views: id, title, price, currency, image
    'vehicles' => [
        [
            'id' => 1,
            'title' => 'Demo Sedan 2020',
            'price' => 120000,
            'currency' => 'Nu.',
            'image' => '/storage/demo/vehicle1.jpg',
        ],
        [
            'id' => 2,
            'title' => 'Demo Hatchback 2019',
            'price' => 80000,
            'currency' => 'Nu.',
            'image' => '/storage/demo/vehicle2.jpg',
        ],
    ],
];
