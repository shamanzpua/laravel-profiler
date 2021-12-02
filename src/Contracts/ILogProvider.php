<?php
namespace shamanzpua\LaravelProfiler\Contracts;


interface ILogProvider
{
    public function get($options = null);
}