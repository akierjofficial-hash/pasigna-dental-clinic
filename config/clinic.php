<?php

return [
    'name' => env('APP_NAME', 'Pasigna Dental Clinic'),
    'logo' => 'images/PasignaAssets/logo.jpg',
    'favicon' => 'images/favicon.png',
    'colors' => [
        'lavender' => '#E4D3F5',
        'soft' => '#D1B4E2',
        'medium' => '#B78CD4',
        'accent' => '#9D66B9',
        'primary' => '#7B4DA5',
        'deep' => '#563682',
        'ink' => '#3C1E5E',
    ],
    'tagline' => 'Healthy Smiles • Brighter Tomorrows',
    'city' => 'Guihulngan, Negros Oriental',
    'phone' => env('CLINIC_PUBLIC_PHONE', '0917 568 3755'),
    'email' => env('CLINIC_PUBLIC_EMAIL'),
    'address' => env('CLINIC_PUBLIC_ADDRESS', 'R. Villegas St. Poblacion Guihulngan City Negros Oriental (Beside Mercury Drug), Guihulngan, Philippines, 6214'),
    'hours' => env('CLINIC_PUBLIC_HOURS', 'Monday to Saturday, 9:00 AM to 5:00 PM (Closed Sunday)'),
    'facebook' => env('CLINIC_PUBLIC_FACEBOOK', 'https://www.facebook.com/pasignadentalclinic'),
];
