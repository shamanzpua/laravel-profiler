<?php

namespace shamanzpua\LaravelProfiler\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use shamanzpua\LaravelProfiler\Contracts\ILogProvider;
use shamanzpua\LaravelProfiler\LogStorages\LaravelFileLogStorage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogController extends Controller
{
    public function show(Request $request, ILogProvider $logsProvider)
    {
        return view('profiler-logs', ['logFiles' => $logsProvider->get()]);
    }

    public function delete()
    {

    }
}