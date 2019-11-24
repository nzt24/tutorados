@extends('tutor.plantilla')
@section('title', 'Reportes')
@section('GRUPOS','active bg-primary  rounded')


@section('content')


<div>
  <h3 class="text-primary text-center mb-4">Reportes</h3>
  <div class="AlumnosPorMateria border border-light rounded bg-light px-3 py-3">
    <span class="text-primary">Reporte de Alumnos Por Materia</span>
    <hr>
    <form class="mt-3" action="{{route('ReporteAlumnoMateria')}}" method="post">
       @csrf
    <input type="text" name="id_grupo" id="id_grupo" value="@php echo $_GET['id_grupo'];@endphp">


      <div class="form-group">
        <div class="row">
          <div class="col-9">
            <select class="form-control" id="materia" name="materia">
              <option value="">Seleccionar Materia</option>
              <option value="CCOS001">Metodología de la Programación</option>
              <option value="CCOS003">Algebra Superior</option>
              <option value="CCOS004">Programación 1</option>
            </select>
          </div>
          <div class="col-3">
            <button type="submit" class="btn btn-primary mb-2">Ver Reporte</button>
          </div>
        </div>

      </div>

    </form>
  </div>
</div>



<script type="text/javascript">

jQuery(document).ready(function(){

  $('#id_grupo').hide();

});

</script>


@endsection
