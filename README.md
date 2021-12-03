Install

```shell
 composer require shamanzpua/laravel-profiler
```

Add Service Provider to project providers config:
```php
 shamanzpua\LaravelProfiler\ProfilerServiceProvider::class
```

Usage
```php
 performance_profiling_start("SOME_LOG_NAME");

 profiler_breakpoint("BREAK_POINT_NAME_1");
 sleep(2);
 profiler_breakpoint("BREAK_POINT_NAME_1");
 //some code
 profiler_breakpoint("BREAK_POINT_NAME_1");
    
 performance_profiling_stop("LAST_BREAK_POINT_NAME");
```

Logs url:

http://{PROJECT_URL}/show-profiler-logs?code_auth={PROFILER_AUTH_CODE}

.env configs

```shell
 PROFILER_AUTH_CODE #secure package routes
 PROFILER_QUERY_LOG_DBS #comma separated db connections (Illuminate\Database\Connection). Example: mysql,mongo,mysql2
 PROFILER_LOCAL_TIMEZONE #timezone shown logs. If not set default timezone - utc

 PROFILER_DB_CRITICAL_DURATION
 PROFILER_DB_WARNING_DURATION
 PROFILER_CODE_WARNING_DURATION
 PROFILER_CODE_CRITICAL_DURATION

 PROFILER_STORAGE     #default file
 PROFILER_FILE_STORAGE_PATH 
```