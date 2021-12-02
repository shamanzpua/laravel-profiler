<?php

namespace shamanzpua\LaravelProfiler\ExtraOptions;

use shamanzpua\LaravelProfiler\Contracts\IExtraOption;
use shamanzpua\LaravelProfiler\Contracts\IExtraOptionFactory;


class Factory implements IExtraOptionFactory
{
    /**
     * @return string[]
     */
    protected function getClassMap() : array
    {
        return [
            'db-queries' => DBQueriesDuplicateOption::class,
            'start-log-datetime' => StartLogDatetimeOption::class,
        ];
    }

    public function create(string $extraOptionName): IExtraOption
    {
        $classMap = $this->getClassMap();
        return app($classMap[$extraOptionName]);
    }
}