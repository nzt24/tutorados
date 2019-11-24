<!-- SE MUESTRAN TODOS LOS GRUPOS QUE TIENE EL TUTOR -->
@extends('tutor.plantilla')
@section('title', 'Grupos')
@section('GRUPOS','active bg-primary  rounded')
@section('content')
<!-- CONTENIDO -->
<div class=" border-left border-right">

  <div class="px-3 pt-3 bg-white">
    <nav class="bg-white">
      <div class="nav nav-tabs " id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-grupos-tab" data-toggle="tab" href="#nav-grupos" role="tab" aria-controls="nav-grupos" aria-selected="true">Grupos</a>
        <a class="nav-item nav-link" id="nav-agregar_grupo-tab" data-toggle="tab" href="#nav-agregar_grupo" role="tab" aria-controls="nav-agregar_grupo" aria-selected="false">Agregar Grupo</a>
      </div>
    </nav>

    <div class="tab-content " id="nav-tabContent">
      <!-- GRUPOS -->
      <div class="tab-pane fade show active " id="nav-grupos" role="tabpanel" aria-labelledby="nav-grupos-tab">
        <div class="tab-pane fade show active table-responsive " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <table class="table text-secondary">
            <thead class="">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Sección</th>
                <th scope="col">Generación</th>
                <th scope="col">Carrera</th>
                <th scope="col">Grupo</th>
                <th scope="col">Eliminar</th>
              </tr>
            </thead>
            <tbody>

                @php
                $tutor = App\Tutor::where('matricula',auth()->user()->matricula)->get();
                $grupos = App\Grupo::where('id_tutor',$tutor[0]->id_tutor)->get();
                $indice = 0;
                @endphp

                @foreach ($grupos as $grupo)

                @php $indice=$indice+1; @endphp

                @php $carrera = App\Carrera::where('id_licenciatura',$grupo->id_licenciatura)->get();
                @endphp

                <tr>
                  <td>{{$indice}}</td>
                  <td>{{$grupo->seccion}}</td>
                  <td>{{$grupo->generacion}}</td>
                  <td>{{$carrera[0]->nombre}}</td>
                  <td> <a target="_blank" href="{{route('Ver_Grupo',['seccion' => $grupo->seccion ,'generacion' => $grupo->generacion, 'carrera' => $carrera[0]->id_licenciatura, 'tutor' => $tutor[0]->id_tutor])}}">Ver Grupo</a> </td>
                  <!-- <td> <button type="button" name="button" data-toggle="modal" data-target="#modal_eliminar_grupo" >Eliminar</button> </td> -->
                  <td> <a href="{{route('EliminarGrupo',['seccion' => $grupo->seccion ,'generacion' => $grupo->generacion, 'carrera' => $carrera[0]->id_licenciatura])}}" data-toggle="modal"  data-target="#modal_eliminar_grupo">Eliminar</a> </td>
                </tr>
                @endforeach



            </tbody>
          </table>
        </div>
          @include('tutor.modal_eliminar_grupo')
      </div>

        <!-- AGREGAR GRUPO -->
      <div class="tab-pane fade" id="nav-agregar_grupo" role="tabpanel" aria-labelledby="nav-agregar_grupo-tab">
        <div class="alert alert-warning alert-document" role="alert">
        </div>
        <div class="pt-2">
          <form class="" id="CrearGrupo" action="{{route('agregarGrupo')}}" method="post" enctype="multipart/form-data">
          {{ method_field('POST') }}
             @csrf
              <!-- fila 1 -->
             <div class="row mt-3">
               <div class="col">
                 <p class="text-secondary">Para cargar alumnos al grupo deberá subir un archivo con extensión .xlsx. El cual deberá llevar Matricula, Nombre, Apellido paterno, Apellido materno de los alumnos.
                 <a href="#">Descargar Ejemplo</a>
                 </p>
               </div>
             </div>
              <!-- fila 2 -->
             <div class="row">
                    <div class="col-12 col-sm-4">
                      <span>Seccion:</span>
                      <input type="text" class="form-control" placeholder="Ejemplo: 101" name="seccion" required>
                    </div>

                    <div class="col-12 col-sm-4">
                        <span>Periodo de Ingreso:</span>
                              <select class="form-control" type="text" name="periodo" value="">
                                <option value="Otoño @php echo date('Y') @endphp" >Otoño @php echo date('Y') @endphp </option>
                                <option value="Primavera @php echo date('Y') @endphp" >Primavera @php echo date('Y') @endphp</option>
                                <option value="Otoño @php echo date('Y')-1 @endphp" >Otoño @php echo date('Y')-1 @endphp </option>
                                <option value="Primavera @php echo date('Y')-1 @endphp" >Primavera @php echo date('Y')-1 @endphp</option>
                                <option value="Otoño @php echo date('Y')-2 @endphp" >Otoño @php echo date('Y')-2 @endphp </option>
                                <option value="Primavera @php echo date('Y')-2 @endphp" >Primavera @php echo date('Y')-2 @endphp</option>
                                <option value="Otoño @php echo date('Y')-3 @endphp" >Otoño @php echo date('Y')-3 @endphp </option>
                                <option value="Primavera @php echo date('Y')-3 @endphp" >Primavera @php echo date('Y')-3 @endphp</option>
                                <option value="Otoño @php echo date('Y')-4 @endphp" >Otoño @php echo date('Y')-4 @endphp </option>
                                <option value="Primavera @php echo date('Y')-4 @endphp" >Primavera @php echo date('Y')-4 @endphp</option>

                             </select>
                    </div>

                    <div class="col-12 col-sm-4">
                        <span>Licenciatura:</span>
                        <select class="form-control" type="text" name="licenciatura" value="">
                           <option value="1" >Ing. en Cs. de la Computación</option>
                        </select>
                    </div>
            </div>

              <!-- fila 3 -->
            <div class="row mt-3">
              <div class="col-12 col-sm-4">
                <input class="form-control-file" type="file" name="excel" id="excel">
              </div>
              <div class="col-12 col-sm-4">
                  <button type="button"  class=" btn btn-primary mb-2  pl-5 pr-5" name="CargarDocumento" id="CargarDocumento">Cargar documento</button>
              </div>
            </div>

            <!-- MOSTRAMOS LA LISTA DE ALUMNOS QUE SE CARGARON -->
            <div class="alert alert-warning alert-agregarGrupo" role="alert">
              ¡ ATENCIÓN ! Verifique que los datos de los alumnos estén escritos correctamente antes de crear el grupo.
            </div>
            @include('tutor.mostrar_alumnos_tabla')
            <!-- FIN -->

          </form>

        </div>
        <div class="text-center text-primary bg-white pt-5 pb-5  mx-auto cargando ">
          <h5>
          <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
            <span class="sr-only">Loading...</span><br>
          </div>
          <p>Cargando ...</p>
        </h5>
        </div>




      </div>
        </div>
    </div>
</div>


@endsection
