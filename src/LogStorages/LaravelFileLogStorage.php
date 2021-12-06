<?php
namespace shamanzpua\LaravelProfiler\LogStorages;

use shamanzpua\LaravelProfiler\Contracts\IExtraOptionFactory;
use shamanzpua\LaravelProfiler\Contracts\ILogCleaner;
use shamanzpua\LaravelProfiler\Contracts\ILogProvider;
use shamanzpua\LaravelProfiler\Exceptions\InvalidConfigException;
use shamanzpua\LaravelProfiler\Exceptions\InvalidParamException;
use shamanzpua\Profiler\LogStorages\FileStorage;
use shamanzpua\Profiler\Contracts\ILogStorage;
use Closure;

class LaravelFileLogStorage implements ILogStorage, ILogProvider, ILogCleaner
{
    const SYSTEM_DIRS = [
        '.',
        '..',
    ];

    private $targetLog;

    /**
     * @var FileStorage $fileStorage
     */
    private $fileStorage;

    /**
     * @var array $logFiles
     */
    private $logFiles = [];

    /**
     * @var IExtraOptionFactory $extraOptionFactory
     */
    private $extraOptionFactory;

    private $logsPath;

    public function __construct(IExtraOptionFactory $extraOptionFactory)
    {
        $this->extraOptionFactory = $extraOptionFactory;
        $currentLogStorage = config("code-profiler.storages.file");

        if (!isset($currentLogStorage['path'])) {
            throw new InvalidConfigException("'path' should be specified");
        }

        $this->logsPath = $currentLogStorage['path'] . "/";
        $this->fileStorage = new FileStorage($this->logsPath);
    }

    public function put(string $name, array $logs, $time)
    {
        $this->fileStorage->put($name, $logs, $time);
    }

    public function get($options = null)
    {
        if (isset($options['log_name'])) {
            $this->targetLog = $options['log_name'];
        }
        $ensureLogsAction = function ($fileFullPath, $file) use ($options) {
            $logFile[$file] = unserialize(file_get_contents($fileFullPath));
            $logFile[$file]['datetime'] = $this->extraOptionFactory
                ->create('start-log-datetime')
                ->get($logFile[$file]['start_time']);
            $logFile[$file]['duplicateQueries'] = $this->extraOptionFactory
                ->create('db-queries')
                ->get($logFile[$file]['stacktrace']);

            $this->logFiles = array_merge($this->logFiles, $logFile);
        };

        $this->scanDir($this->logsPath, $ensureLogsAction);

        return collect($this->logFiles)->sortByDesc('start_time');
    }


    /**
     * recircive
     */
    private function scanDir(string $path, Closure $action)
    {
        $files = scandir($path);
        foreach ($files as $file) {
            $fileFullPath =  $path. "/" . $file;

            if (in_array($file, static::SYSTEM_DIRS)) {
                continue;
            }

            if ($this->targetLog && (strpos($file, $this->targetLog) === false)) {
                continue;
            }

            if (is_dir($fileFullPath)) {
                $this->scanDir($fileFullPath, $action);
                continue;
            }

            $action($fileFullPath, $file);
        }

    }

    public function delete($options = null)
    {
        $deleteLogsAction = function ($fileFullPath, $file) use ($options) {
            if (!isset($options['delete_after_minutes'])) {
                throw new InvalidParamException("'delete_after_minutes' should be set");
            }

            $minutes = $options['delete_after_minutes'];
            $log = unserialize(file_get_contents($fileFullPath));
            if ($log['start_time'] < (time() - $minutes * 60)) {
                unlink($fileFullPath);
            }
        };

        $this->scanDir($this->logsPath, $deleteLogsAction);
    }
}