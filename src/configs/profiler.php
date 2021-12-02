<?php
use shamanzpua\LaravelProfiler\LogStorages\LaravelFileLogStorage;

return [
    'auth_code' => env('PROFILER_AUTH_CODE', null),
    'db_connections' => env('PROFILER_QUERY_LOG_DBS'),
    'timezone' => env('PROFILER_LOCAL_TIMEZONE'),
    'durations' => [
        'database_critical' => env('PROFILER_DB_CRITICAL_DURATION', 500),
        'database_warning' => env('PROFILER_DB_WARNING_DURATION', 100),
        'code_warning' => env('PROFILER_CODE_WARNING_DURATION', 150),
        'code_critical' => env( 'PROFILER_CODE_CRITICAL_DURATION', 500),
    ],
    'storage' => env( 'PROFILER_STORAGE', 'file'),
    'storages' => [
        'file' => [
            'path' => env( 'PROFILER_FILE_STORAGE_PATH', storage_path('logs/code-profile')),
            'class' => LaravelFileLogStorage::class,
        ]
    ]
];