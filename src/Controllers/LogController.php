<?php

namespace shamanzpua\LaravelProfiler\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use shamanzpua\LaravelProfiler\Contracts\ILogCleaner;
use shamanzpua\LaravelProfiler\Contracts\ILogProvider;
use shamanzpua\LaravelProfiler\LogStorages\LaravelFileLogStorage;
use shamanzpua\LaravelProfiler\Requests\DeleteLogsRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogController extends Controller
{
    public function show(Request $request, ILogProvider $logsProvider)
    {
        return view('profiler-logs', ['logFiles' => $logsProvider->get(['log_name' => $request->get('log_name')])]);
    }

    public function delete(DeleteLogsRequest $request, ILogCleaner $ILogCleaner)
    {
        $minutes = $request->get('delete_after_minutes');
        $ILogCleaner->delete(['delete_after_minutes' => $minutes]);
        return response()->json("Logs older then $minutes minutes was deleted");
    }
}