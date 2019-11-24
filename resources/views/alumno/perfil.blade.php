@extends('alumno.plantilla')
@section('title', 'Perfil')
@section('PERFIL','active bg-primary  rounded')
@section('content')
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<div class="container bg-light  pt-5 pb-5 ">
  @php
  $Alumno = App\Alumno::where('matricula',auth()->user()->matricula)->get();
  $Grupo = App\Grupo::where('id_grupo',$Alumno[0]->id_grupo)->get();
  $Carrera = App\Carrera::where('id_licenciatura',$Grupo[0]->id_licenciatura)->get();
  @endphp
  <div class="container bg-white border border-primary rounded ">
    <div>
      <p class="border-bottom "><h5 class="text-primary text-center">Datos Personales</h5></p>
      <hr>

      <div class="container">
        <!-- Nombre -->
        <div class="alert alert-success alert-correcto-datos">
          Datos Guardados Correctamente.
        </div>
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Nombre:
          </div>
          <div class="col-6 col-sm-6 nombre">
            {{$Alumno[0]->nombre.' '.$Alumno[0]->apellido_paterno.' '.$Alumno[0]->apellido_materno}}
          </div>
          <div class="col-6 col-sm-6 CorregirNombre">
            <input class="form-control" type="text" name="nombre" value="{{$Alumno[0]->nombre}}" placeholder="Nombre">
            <input class="form-control" type="text" name="paterno" value="{{$Alumno[0]->apellido_paterno}}" placeholder="Apellido Paterno">
            <input class="form-control" type="text" name="materno" value="{{$Alumno[0]->apellido_materno}}" placeholder="Apellido Materno">
            <input type="button" class="btn btn-primary mt-2 " name="CorregirNombre" value="Corregir Nombre">
          </div>
            <a class="EditarNombre" href="#">Editar <i class='far fa-edit' style='font-size:20px'></i></a>
        </div>
        <!-- Facultad -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Facultad:
          </div>
          <div class="col-6 col-sm-6">
              Ciencias de la Computación
          </div>
        </div>
        <!-- Carrera -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Licenciatura:
          </div>
          <div class="col-6 col-sm-6">
              {{$Carrera[0]->nombre}}
          </div>
        </div>
        <!-- Matricula -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Matricula:
          </div>
          <div class="col-6 col-sm-6">
              {{auth()->user()->matricula}}
          </div>
        </div>
        <!-- Correo-->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Correo:
          </div>
          <div class="col-6 col-sm-6 correo">
              {{$Alumno[0]->correo}}
          </div>
          <div class="col-6 col-sm-6 CorregirCorreo">
            <input class="form-control" type="text" name="correo" value="{{$Alumno[0]->correo}}" placeholder="Correo">
            <input type="button" class="btn btn-primary mt-2 " name="CorregirCorreo" value="Corregir Correo">
          </div>
            <a class="EditarCorreo" href="#">Editar <i class='far fa-edit' style='font-size:20px'></i></a>
        </div>
    </div>
    </div>
    <hr>

    <!---                        Cambiar contraseña                              -->
    <div>
      <p class="border-bottom "><h5 class="text-primary text-center">Cuenta</h5></p>

      <!--              alerts -->
      <div class="alert alert-danger alert-error">
      </div>
      <div class="alert alert-success alert-correcto-password ">
      La contraseña se ha cambiado correctamente.
      </div>

    <!-- Nueva contraseña -->
    <div class="row mb-2">
      <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
          Nueva Contraseña:
      </div>
      <div class="col-6 col-sm-6">
          <input class="form-control" type="password" name="password1" value="">
      </div>
    </div>
    <!-- Repetir contraseña contraseña -->
    <div class="row mb-2">
      <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
          Repetir Contraseña:
      </div>
      <div class="col-6 col-sm-6">
          <input class="form-control" type="password" name="password2" value="">
      </div>
    </div>

      <div class="text-center">
        <input type="button" class="btn btn-primary mb-3 mt-3" name="CambiarContraseña" value="Cambiar Contraseña">
      </div>

</div>

    </div>
  </div>

@endsection
