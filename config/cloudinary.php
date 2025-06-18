<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Credentials
    |--------------------------------------------------------------------------
    |
    | You can find your credentials on your Cloudinary dashboard.
    | Use your .env file to keep them secure.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', null),

    /*
    |--------------------------------------------------------------------------
    | Default Upload Options
    |--------------------------------------------------------------------------
    |
    | These options will be used by default when uploading media files unless
    | you override them in your code. You can set folder, resource type, etc.
    |
    */

    'upload_options' => [
        // Example: 'folder' => 'my-folder',
    ],
];
