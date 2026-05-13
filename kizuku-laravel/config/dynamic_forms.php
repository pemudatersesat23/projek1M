<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Field Types
    |--------------------------------------------------------------------------
    |
    | Defines all the field types supported by the Dynamic Form Builder.
    |
    */
    'allowed_field_types' => [
        'text',
        'textarea',
        'number',
        'email',
        'phone',
        'date',
        'select',
        'radio',
        'checkbox',
        'file',
        'section',
    ],

    /*
    |--------------------------------------------------------------------------
    | Choice Field Types
    |--------------------------------------------------------------------------
    |
    | Field types that require an array of options to be presented to the user.
    |
    */
    'choice_field_types' => [
        'select',
        'radio',
        'checkbox',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Field Type
    |--------------------------------------------------------------------------
    |
    | The designated field type for file uploads.
    |
    */
    'file_field_type' => 'file',

    /*
    |--------------------------------------------------------------------------
    | File Size Configuration
    |--------------------------------------------------------------------------
    |
    | The default maximum file size in KB if not explicitly set by the admin.
    |
    */
    'default_max_file_size' => 2048,

    /*
    |--------------------------------------------------------------------------
    | Blocked File Extensions
    |--------------------------------------------------------------------------
    |
    | Extensions that are universally blocked from being uploaded, regardless
    | of admin configuration, for security reasons.
    |
    */
    'blocked_file_extensions' => [
        'php',
        'phtml',
        'exe',
        'sh',
        'bat',
        'js',
        'html',
        'svg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Allowed File Extensions
    |--------------------------------------------------------------------------
    |
    | The default file extensions allowed if the admin does not specify any.
    |
    */
    'default_allowed_file_extensions' => [
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'doc',
        'docx',
    ],

];
