Install
Add Service Provider to Laravel project:
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

.env configs

```shell
PROFILER_AUTH_CODE
PROFILER_LOG_STORAGE
PROFILER_QUERY_LOG_DBS
PROFILER_LOCAL_TIMEZONE

PROFILER_DB_CRITICAL_DURATION
PROFILER_DB_WARNING_DURATION
PROFILER_CODE_WARNING_DURATION
PROFILER_CODE_CRITICAL_DURATION

PROFILER_STORAGE
PROFILER_FILE_STORAGE_PATH
```