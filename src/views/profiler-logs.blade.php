<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style>
    .inline-div {
      display: inline-block;
      padding-left: inherit;
        font-size: 18px;
    }
    .point {
      font-weight: inherit;
      width: 800px;
    }

    .time {
      font-weight: inherit;
      width: 100px;
    }
    .db {
      font-weight: inherit;
      width: 200px;
      margin-left: 80px;
      margin-bottom: 20px;
      /*padding-left: 10px;*/
    }
    .query {
        /*font-weight: inherit;*/
        width: 600px;
        margin-left: 60px;
        font-size: 16px;
        /*padding-left: 10px;*/
    }
    .duration {
      font-weight: inherit;
      width: 200px;
    }
    .point-data {
      margin-bottom: 5px;
    }
    .bold {
        font-weight: bold;
    }
    .red-zone {
      color: red;
    }
    .orange-zone {
      color: orange;
    }
  </style>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body id="home">

  <div class="container">

    <h2>Profiler logs</h2>


    @foreach($logFiles as $name => $profileData)

      <a class="btn btn-success " data-toggle="collapse" href="#{{md5($name)}}" role="button" aria-expanded="false" aria-controls="collapseExample">
        {{$profileData['datetime'] . ": " . $name . " Total duration: " .$profileData['total_duration'] . "ms" }}
      </a> <br/><br/>
      <div class="collapse" id="{{md5($name)}}">
        <div class="card card-body">
          @foreach($profileData['stacktrace'] as $pointKey => $pointData)
            <div class="point-data">
             <span class="{{ $pointData['duration'] >= 1000 ? "red-zone" : ($pointData['duration'] >= 150 ? "orange-zone" : "") }}">
                <div class='inline-div point' >{{ $pointData['specialBreakpoint'] ?? $pointData['class'] . "::". $pointData['method'] . ":". $pointData['line']  }}{{isset($pointData['break_point']) ? "  (Breakpoint name:" . $pointData['break_point'] . ")"  : ""}}

                </div>
                <div class='inline-div duration' ><b>Duration:</b>{{$pointData['duration']}} ms </div>
                 @if(!empty($pointData['db']))
                     <a class="btn btn-danger" title="Total Duration: {{$pointData['db_duration_sum']['total']}}ms" data-toggle="collapse" href="#db-{{md5($name . $pointKey)}}">DB Queries</a>
                 @endif
             </span>

            </div>

            <div class="collapse" id="db-{{md5($name . $pointKey)}}">

                @foreach($pointData['db'] as $connection => $connectionData)
                    @include('profiler-queries', ['db' => $connection, 'btnName' => strtoupper($connection), 'btnClass' => 'btn btn-info'])
                @endforeach
            </div>

            @endforeach
        </div>
      </div>
    @endforeach

  </div>

</body>
</html>