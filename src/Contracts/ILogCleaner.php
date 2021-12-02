<?php
namespace shamanzpua\LaravelProfiler\Contracts;

interface ILogCleaner
{
    public function delete($options = null);
}