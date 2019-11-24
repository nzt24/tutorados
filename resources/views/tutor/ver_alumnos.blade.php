<!-- SE MUESTRAN LOS ALUMNOS DE UN GRUPO SELECCIONADO -->

@extends('tutor.plantilla')
@section('title', 'Grupo')
@section('GRUPOS','active bg-primary  rounded')
@section('content')
<!-- CONTENIDO -->
<div class=" border-left border-right">
  <div class=" card table-responsive mt-4">
    <div class=" text-center card-header">
     <h5>Alumnos</h5>
    </div>
      <div class="card-body">
        <div class="row">
        <div class="col-sm">
          <span class="text-secondary">Carrera: </span> <span class="text-primary">Ing. en Cs. Computación</span>
        </div>
        <div class="col-sm">
          <span class="text-secondary">Generación: </span> <span class="text-primary">{{$Grupo[0]->generacion}}</span>
        </div>
        <div class="col-sm">
        <span class="text-secondary">Sección: </span> <span class="text-primary">{{$Grupo[0]->seccion}}</span>
        </div>
         <div class="col-sm">
          <span class="text-secondary">Reportes: </span> <a target="_self"


        href="{{route('reportes',['id_grupo' => $Grupo[0]->id_grupo])}}"> Reportes </a>
        </div>
      </div>
      <hr>
      <table class="table text-secondary table-sm">
        <thead class="">
          <tr>
            <th scope="col">N°</th>
            <th scope="col">Matricula</th>
            <th scope="col">Nombre</th>
            <th scope="col">Promedio Gral.</th>
            <th scope="col">Avance.</th>
            <th scope="col">Mapa Grafico</th>
        </thead>
        <tbody>
          @php
          $indice = 0;
          @endphp

          @foreach ($Alumnos as $Alumno)
          <tr>
            <th scope="row">{{$indice=$indice+1}}</th>
            <td>{{$Alumno->matricula}}</td>
            <td>{{$Alumno->nombre.' '.$Alumno->apellido_paterno.' '.$Alumno->apellido_materno}}</td>
            <td>{{$Alumno->promedio_general}}</td>
            <td>{{$Alumno->porcentaje}} %</td>
            <td> <a target="_blank" href="{{route('Ver_Alumno',['id_alumno' => $Alumno->id_alumno])}}">Mapa Grafico</a> </td>
          </tr>
          @endforeach

        </tbody>
      </table>
      </div>


  </div>

</div>
@endsection
