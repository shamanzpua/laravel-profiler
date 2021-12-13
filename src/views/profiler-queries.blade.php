
@if(!empty($pointData['db'][$db]))
    <div class='inline-div db {{$db}}'><a title="Total Duration: {{$pointData['db_duration_sum'][$db]}}ms" class="{{$btnClass}}" data-toggle="collapse" href="#{{$db}}-{{md5($name . $pointKey)}}"> {{$btnName}}</a> </div>
    <div class="collapse query" id="{{$db}}-{{md5($name . $pointKey)}}">
        @foreach($pointData['db'][$db] as $key => $queryData)
            <ul>
                <li>Query:{{$queryData['query']}}</li>
                <li>Bindings: {{json_encode($queryData['bindings'])}}</li>
                <li class="{{$queryData['time'] < 100 ? "" : ($queryData['time'] < 500 ? "orange-zone" : "red-zone")}}">Time: {{$queryData['time']}} ms</li>
                @if(isset($profileData['duplicate_queries'][($queryHash = md5($db . $queryData['query']. serialize($queryData['bindings'])))]))
                    <li class="bold {{$profileData['duplicate_queries'][$queryHash] > 5 ? "red-zone": "orange-zone"}}">Duplicates count:  {{$profileData['duplicate_queries'][$queryHash]}}</li>
                @endif
            </ul>
        @endforeach
    </div>
@endif