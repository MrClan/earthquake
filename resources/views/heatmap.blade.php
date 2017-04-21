@extends('layouts.default')
@section('title', 'Heatmap of Earthquakes in the Philippines')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>@yield('title')</h3>
        </div>
    </div>

    <div class="row">
        <form name="earthquake_count" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-lg-3 col-sm-1">
                <div class="form-group">
                    <select class="form-control" name="period">
                        @foreach ([7,30,90,180,360,1080,1800,3600,7200] as $v)
                            <option value="{{ $v }}" @if ($data['params']['period'] == $v) selected="selected"@endif>Last @if ($v <= 30) {{ $v . " days" }} @elseif ($v > 30 && $v <=360)  {{ $v/30 . " months" }} @else  {{ $v/360 . " years" }} @endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-1">
                <div class="form-group">
                    <select class="form-control" name="filter">
                        @foreach (['days', 'months', 'years'] as $v)
                            <option value="{{ $v }}" @if ($data['params']['filter'] == $v) selected="selected"@endif>{{ ucfirst($v) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-1">
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-success">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-thermometer-half"></i> Heatmap</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-1">
                            <div id="heatmap_canvas" style="width: 100%; height: 600px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-genderless"></i> Circlemap</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-1">
                            <div id="circlemap_canvas" style="width: 100%; height: 600px;"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-page-specific')
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_maps_api_key') }}&libraries=visualization&callback=initMap">
</script>

<script>
    // Google Maps
    // TODO: rewrite and optimize these JS shiznits.
    var map;
    var earthquakeData = $.parseJSON($.ajax({
        type: 'get',
        url: '{!! $data['url'] !!}',
        dataType: 'json',
        data: [],
        async: false
    }).responseText);

    function initMap()
    {
        generateHeatmap(earthquakeData);
        generateCircleMap(earthquakeData);
    }


    function generateCircleMap(results)
    {
        map = new google.maps.Map(document.getElementById('circlemap_canvas'), {
            zoom: 5,
            center: {lat: 12.501920, lng: 122.279620}, // philippines
            mapTypeId: 'terrain'
        });

        map.data.setStyle(function(feature) {
            var magnitude = feature.getProperty('mag');
            return {
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: 'red',
                    fillOpacity: .2,
                    scale: Math.pow(2, magnitude) / 4,
                    strokeColor: 'white',
                    strokeWeight: .1
                }
            };
        });

        map.data.addGeoJson(results);
    }

    function generateHeatmap(results)
    {
        map = new google.maps.Map(document.getElementById('heatmap_canvas'), {
            zoom: 5,
            center: {lat: 12.501920, lng: 122.279620}, // philippines
            mapTypeId: 'terrain'
        });

        var heatmapData = [];
        for (var i = 0; i < results.features.length; i++) {
            var coords = results.features[i].geometry.coordinates;
            var latLng = new google.maps.LatLng(coords[1], coords[0]);
            heatmapData.push(latLng);
        }
        var heatmap = new google.maps.visualization.HeatmapLayer({
            data: heatmapData,
            dissipating: false,
            map: map,
        });
        heatmap.set('radius', 0.2);
    }
</script>
@endsection