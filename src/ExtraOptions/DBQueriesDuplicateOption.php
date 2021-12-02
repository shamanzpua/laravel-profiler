<?php

namespace shamanzpua\LaravelProfiler\ExtraOptions;

use shamanzpua\LaravelProfiler\Contracts\IExtraOption;

class DBQueriesDuplicateOption implements IExtraOption
{
    private $stacktrace;
    private $queries = [];
    private $duplicateQueries = [];

    /**
     * @param $data
     * @return array
     */
    public function get($data)
    {
        $this->stacktrace = $data;
        foreach ($this->stacktrace as $point) {
            if (!empty($point['db'])) {
                $dbs = array_keys($point['db']);
                foreach ($dbs as $db) {
                    $this->findDuplicates($point, $db);
                }
            }
        }
        return $this->duplicateQueries;
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