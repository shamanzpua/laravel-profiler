<?php


namespace shamanzpua\LaravelProfiler;

use shamanzpua\Profiler\Contracts\ICustomProfiler;
use Illuminate\Database\Connection;
use Throwable;
use DB;

class DBQueriesProfiler implements ICustomProfiler
{
    private $connections = [];
    private $badConnections = [];
    private $logs = [];

    public function init()
    {
        $connections = config('code-profiler.db_connections');

        if (!$connections || $connections == '') {
            return;
        }

        $connections = explode(',', $connections);
        foreach ($connections as $connectionName) {
            try {
                $connection = DB::connection($connectionName);
                if ($connection instanceof Connection) {
                    $connection->enableQueryLog();
                    $this->connections[$connectionName] = $connection;
                    continue;
                }
                $this->badConnections[$connectionName] = "BAD CONNECTION CLASS. INSTANCE:". get_class($connection);
            } catch (Throwable $exception) {
                $this->badConnections[$connectionName] = $exception->getMessage();
            }
        }

    }


    private function flushLogs()
    {
        $this->logs = [];
    }

    public function run(): array
    {
        $this->flushLogs();
        foreach ($this->connections as $connectionName => $connection) {
            $logs = $connection->getQueryLog();
            if (!empty($logs)) {
                $this->logs[$connectionName] = $connection->getQueryLog();
            }
            $connection->flushQueryLog();
        }

        return [
            'db' => $this->logs,
            'badConnections' => $this->badConnections,
        ];
    }
}
