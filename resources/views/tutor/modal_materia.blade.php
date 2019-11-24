


<!-- VENTANA MODAL QUE MUESTRA INFORMACION DE UNA MATERIA SELECCIONADA POR EL TUTOR DE UN MAPA GRAFICO DE UN ALUMNO -->


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary buttonModal" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade mimodal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h6 class="modal-title" id="Title"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

                 @csrf

                <div class="no_disponible">
                  Esta materia no se encuentra disponible.
                </div>

                <div class="disponible">
                <span>EL alumno puede cursar esta materia.</span><br>
                </div>

                <div class="cursando">
                  <span>El alumno esta cursando esta materia.</span><br>
                </div>

                <div class="aprobada">
                <h6 class="text-primary">Materia Aprobada</h6>
                <span class="text-secondary">Calificación: <span id="aprobada_calificacion"></span></span><br>
                <span class="text-secondary">Recursos: <span id="aprobada_intentos"></span></span>
                </div>

                <div class="reprobada">
                  <span>El alumno tiene reprobada esta materia.</span><br>
                </div>

      </div>


    </div>
  </div>
</div>
