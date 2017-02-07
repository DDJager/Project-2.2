@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Top 5 balkan</div>
                <div class="panel-body">
					  <select id="station" onchange="stationChange()"></select>
                      <p id="error"></p>
                    <div class="chartWrapper">
                        <div class="chartAreaWrapper">
                            <div id="myChart" height="500" width="100%"></div>
                        </div>
                        <canvas id="myChartAxis" height="500" width="0"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

</script>
<script src="{{url('/js/chart.js')}}"></script>
<script>
var chart;
function updateGraph(data) {
    if(chart != undefined) {
        //redraw the graph
        chart.destroy();
    }
    var arrayOfStrings = data.humidity;
    var humidity = arrayOfStrings.map(Number);
    chart = Highcharts.chart('myChart', {

        title: {
            text: 'Humidity',
            x: -20 //center
        },

        xAxis: {
            categories: data.time
        },
        yAxis: {
            title: {
                text: 'Time'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Humidity',
            data: humidity,
        }]
    });
}
function drawGraph(id){
	$.ajax({
		url: 'humidity/live/data/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function(res) {
		    if(res == false){
                if(chart != undefined) {
                    chart.destroy();
                }

		        $('#error').text('Er is geen data beschikbaar voor dit station dat overeenkomt met het huidige uur');
            }else{
                updateGraph(res)
                return false;
            }
		}
	});
}
function stationChange() {
	var id = document.getElementById("station").value;
    drawGraph(id);
    intervalUpdate(id);
}
window.onload=  function(){
	var elt;
	$.ajax({
		 url: '/humidity/stations',
		 type: 'GET',
		 dataType: 'JSON',
		 success: function(res) {
			var numbers = res;
			var option = '<option>Select station</option>';
			for (var i=0;i<numbers.length;i++){
			   option += '<option value="'+ numbers[i].stn + '">' + numbers[i].name + '</option>';
			}
			$('#station').append(option);
		}
	 });
};

var intervalUpdate = function(id) {
    setInterval(function () {
        drawGraph(id);
    }, 10000);
}
</script>
@endsection
