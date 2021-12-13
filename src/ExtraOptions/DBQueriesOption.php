<?php

namespace shamanzpua\LaravelProfiler\ExtraOptions;

use shamanzpua\LaravelProfiler\Contracts\IExtraOption;

class DBQueriesOption implements IExtraOption
{
    private $logData;
    private $stacktrace;
    private $queries = [];
    private $duplicateQueries = [];

    /**
     * @param $data
     * @return array
     */
    public function get($data)
    {
        $this->logData = $data;
        $this->stacktrace = $this->logData['stacktrace'];
        foreach ($this->stacktrace as $pointKey => $point) {
            if (!empty($point['db'])) {
                $dbs = array_keys($point['db']);
                foreach ($dbs as $db) {
                    $this->calculateDbDurationSum($pointKey, $db);
                    $this->findDuplicates($point, $db);
                }
                if (!empty($dbs)) {
                    $this->calculateTotalDurationSum($pointKey);
                }
            }
        }

        $this->logData['stacktrace'] = $this->stacktrace;
        $this->logData['duplicate_queries'] = $this->duplicateQueries;

        return $this->logData;
    }

    private function calculateDbDurationSum($pointKey, $db)
    {
        $stacktrace = $this->stacktrace;
        $pointQueryLogs = $stacktrace[$pointKey]['db'][$db];
        $durationSum = 0;
        foreach ($pointQueryLogs as $pointQueryLog) {
            $durationSum += $pointQueryLog['time'];
        }
        $this->stacktrace[$pointKey]['db_duration_sum'][$db] = $durationSum;
    }

    private function calculateTotalDurationSum($pointKey)
    {
        $stacktrace = $this->stacktrace;
        $this->stacktrace[$pointKey]['db_duration_sum']['total'] = 0;
        foreach ($stacktrace[$pointKey]['db_duration_sum'] as $durationSum) {
            $this->stacktrace[$pointKey]['db_duration_sum']['total'] += $durationSum;
        }
    }

    private function findDuplicates($point, $db)
    {
        foreach ($point['db'][$db] as $queryData) {
            $queryHash = md5($db . $queryData['query'] . serialize($queryData['bindings']));
            if (!isset($this->queries[$queryHash])) {
                $this->queries[$queryHash] = 1;
                continue;
            }
            if (!isset($this->duplicateQueries[$queryHash])) {
                $this->duplicateQueries[$queryHash] = 2;
                continue;
            }
            $this->duplicateQueries[$queryHash]++;
        }
    }
}