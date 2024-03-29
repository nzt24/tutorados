@extends('tutor.plantilla')
@section('title', 'Reporte de Alumnos Por Materia')
@section('GRUPOS','active bg-primary  rounded')

@section('content')



<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Alumnos', 'Total'],
          ['Alumnos Aprobados',     {{$AlumnosAprobados}}],
          ['Alumnos Reprobados',       {{$AlumnosReprobados}}]

        ]);

        var options = {
          title: 'Reporte de Alumnos de la Materia: {{$NombreMateria}}',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
  </body>
</html>


@endsection
