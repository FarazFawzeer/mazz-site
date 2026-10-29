<?php

return [

    /*
    | Business details. Use exactly as written (see admin CLAUDE.md).
    */
    'name' => 'Mazz Laptop Hub',
    'tagline' => 'Refurbished laptops & parts, Negombo',
    'address' => '374/8, Yoosuf Hajiyar Mawatha, Periyamulla, Negombo',
    'phone_display' => '078 999 9976',
    'phone_tel' => '+94789999976',
    'whatsapp' => '94789999976',
    'email' => 'mazzlaptophub@gmail.com',
    'registration' => 'WD/GAM/NG/2024/00461',
    'map_query' => 'Mazz Laptop Hub, Periyamulla, Negombo, Sri Lanka',

    /*
    | Product photos live in the admin's public folder.
    | media_url:  where browsers load them from (live: https://admin.mazzlaptop.lk).
    | media_path: optional filesystem path to that same folder. When set, a .webp
    |             copy next to a photo is used if it exists.
    */
    'media_url' => rtrim(env('MAZZ_MEDIA_URL', 'https://admin.mazzlaptop.lk'), '/'),
    'media_path' => env('MAZZ_MEDIA_PATH'),

    // Order of the slots on the Build page. Part categories not listed here
    // follow alphabetically.
    'build_order' => ['Processor', 'RAM', 'SSD', 'HDD', 'Charger', 'Monitor', 'Keyboard', 'Mouse'],

];
