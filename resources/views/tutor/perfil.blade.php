
<!-- SE MUESTRA LA INFORMACION DEL PERFIL DEL TUTOR -->

@extends('tutor.plantilla')
@section('title', 'Perfil')
@section('PERFIL','active bg-primary  rounded')

@section('content')

<div class="container bg-light  pt-2 ">

  <div class="container bg-white border border-primary rounded ">

      <p class="border-bottom "><h5 class="text-primary text-center">Perfil</h5></p>
      <hr>

      <div class="alert alert-success alert-perfil" role="alert">
      Información guardada correctamente.
      </div>
      <form class="" action="index.html" method="post">
        <!-- Nombre -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Nombre:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="text" name="nombre" value="{{$Tutor->nombre}}">
          </div>
        </div>
        <!-- Apellido Paterno -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Apellido Paterno:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="text" name="paterno" value="{{$Tutor->apellido_paterno}}">
          </div>
        </div>
        <!-- Apellido Materno -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Apellido Materno:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="text" name="materno" value="{{$Tutor->apellido_materno}}">
          </div>
        </div>
        <!-- Cubiculo -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
            Cubículo:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="text" name="cubiculo" value="{{$Tutor->cubiculo}}">
          </div>
        </div>
        <!-- Horario -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Horario Para Asesorías:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="text" name="horario" value="{{$Tutor->horario_asesorias}}">
          </div>
        </div>
        <!-- correo -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Correo:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="mail" name="correo" value="{{$Tutor->correo}}">
          </div>
        </div>

        <div class="text-center">
          <button  class=" btn btn-primary btn-lg mb-2  pl-3 pr-3" id="actualizarInformacion" name="button">Guardar Información</button>
        </div>
        <!-- Nueva contraseña -->
        <br>

        <p class="border-bottom "><h5 class="text-primary text-center">Contraseña</h5></p>
        <hr>
        <div class="alert alert-success alert-password" role="alert">
        La contraseña se ha cambiado correctamente.
        </div>

        <div class="alert alert-danger alert-password-error" role="alert">

        </div>

        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary">
              Nueva Contraseña:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="password" name="password1" value="" required>
          </div>
        </div>
        <!-- Repetir contraseña contraseña -->
        <div class="row mb-2">
          <div class=" col-3 col-sm-3 text-right font-weight-bold text-primary" required>
              Repetir Contraseña:
          </div>
          <div class="col-6 col-sm-6">
              <input class="form-control" type="password" name="password2" value="">
          </div>
        </div>

          <div class="text-center">
            <button type="button"  class=" btn btn-primary btn-lg mb-2  pl-3 pr-3" id="cambiarContraseña" name="button">Cambiar contraseña</button>
          </div>
      </form>

    </div>

    </div>
  </div>

  <script type="text/javascript">

        $('.alert-perfil').hide();
        $('.alert-password').hide();
        $('.alert-password-error').hide();

      $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });


      $("#actualizarInformacion").click(function(e){
        console.log('Actualizar Perfil Tutor');
          e.preventDefault();
          var nombre = $("input[name=nombre]").val();
          var paterno = $("input[name=paterno]").val();
          var materno = $("input[name=materno]").val();
          var cubiculo = $("input[name=cubiculo]").val();
          var horario = $("input[name=horario]").val();
          var correo = $("input[name=correo]").val();

          $.ajax({
             type:'POST',
             url:"{{ url('/ActualizarPerfilTutor') }}",
             data:{nombre:nombre, paterno:paterno,materno:materno,cubiculo:cubiculo,horario:horario,correo:correo},
             dataType:'json',
             success:function(data){
               console.log(data);
               if(data.status == 'correcto'){
                 $('.alert-perfil').fadeIn( 300 ).slideUp( 2000 );
               }
             }
          });
  	});

    $('#cambiarContraseña').click(function(){
      console.log('Cambiar Contraseña');
      var password1 = $("input[name=password1]").val();
      var password2 = $("input[name=password2]").val();
      var error = false;
      var mensaje_error = '';

      if(password1 != '' && password2 !=''){
          if(password1 == password2){
              if(password1.length>5){
                $.ajax({
                   type:'POST',
                   url:"{{ url('/CambiarContraseñaTutor') }}",
                   data:{password1:password1},
                   dataType:'json',
                   success:function(data){
                     console.log(data);
                     if(data.status == 'correcto'){
                          $('.alert-password').fadeIn( 300 ).slideUp( 2000 );
                          $("input[name=password1]").val('');
                          $("input[name=password2]").val('');

                     }
                   }
                });

              }else{
                error = true;
                mensaje_error ='Las Contraseña debe tener un minimo de 6 caracteres';
              }
          }else{
            error = true;
            mensaje_error ='Las Contraseñas no coinciden';
          }

      }else{
        error = true;
        mensaje_error ='Todos los campos son necesarios';
      }

      if(error == true){
        $('.alert-password-error').text('');
        $('.alert-password-error').text(mensaje_error);
        $('.alert-password-error').fadeIn( 300 ).slideUp( 2000 );
      }

    });
  </script>
@endsection
