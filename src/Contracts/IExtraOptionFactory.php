<?php
namespace shamanzpua\LaravelProfiler\Contracts;


interface IExtraOptionFactory
{
    public function create(string $extraOptionName) : IExtraOption;
}