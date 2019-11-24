<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Alumno;
use App\User;
use App\Grupo;
use App\Carrera;
use App\Materia;
use App\Alumno_Materia;

class AlumnoController extends Controller{


    public function __construct(){
    $this->middleware(['auth','role:alumno']);
    }

    public function index(){
      return view("alumno.inicio");
    }

    public function perfil(){
        return view("alumno.perfil");
    }

    public function mapaGrafico(){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Grupo = Grupo::where('id_grupo',$Alumno[0]->id_grupo)->get();
      $Carrera = Carrera::where('id_licenciatura',$Grupo[0]->id_licenciatura)->get();

      if($Carrera[0]->id_licenciatura == 1){
        return view('alumno.mapa_grafico');
      }else('Perteneces a una carrera difernte a ICC');
    }

    public function ColorMapaGrafico(){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Materias = Alumno_Materia::where('id_alumno',$Alumno[0]->id_alumno)->get();
      return $Materias;
    }

    public function avance(){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Grupo = Grupo::where('id_grupo',$Alumno[0]->id_grupo)->get();
      $Carrera = Carrera::where('id_licenciatura',$Grupo[0]->id_licenciatura)->get();
      $MateriasAprobadas = Alumno_Materia::where('id_alumno',$Alumno[0]->id_alumno)->where('status', 3)->get();
      $Creditos = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',$Alumno[0]->id_alumno)
      ->where('alumno_materia.status',3)->select('materia.nombre','materia.creditos','alumno_materia.status')->get();

      return view('alumno.avance', [
          'Alumno' => $Alumno[0],
          'Carrera' => $Carrera[0],
          'MateriasAprobadas' => $MateriasAprobadas,
          'Creditos' => $Creditos,
        ] );
      }

      public function ActualizarNombre(Request $request){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Alumno[0]->nombre = $request->nombre;
      $Alumno[0]->apellido_paterno = $request->paterno;
      $Alumno[0]->apellido_materno = $request->materno;
      $Alumno[0]->save();
      return response()->json(['status' => 'Correcto',]);
      }

      public function ActualizarCorreo(Request $request){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Alumno[0]->correo= $request->correo;
      $Alumno[0]->save();
      return response()->json(['status' => 'Correcto',]);
      }

      public function ActualizarContraseña(Request $request){

      $user = User::where('matricula',auth()->user()->matricula)->where('rol', 'alumno')->get();
      $user[0]->password = bcrypt( $request->password1);
      $user[0]->save();
      return response()->json(['status' => 'Correcto',]);
      }

    public function MateriasEspeciales(){
      // $Creditos = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',1)
      // ->where('alumno_materia.status',3)->where('materia.tipo_materia','Basica')
      // ->select('materia.nombre','materia.creditos','alumno_materia.status')->get();
      // echo $Creditos->count();

      $Creditos = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',1)
      ->where('alumno_materia.status',3)->select('materia.nombre','materia.creditos','alumno_materia.status')->get();
      $CreditosGanados = $Creditos->SUM('creditos');
      echo $CreditosGanados;

      }


        public function DesbloquearMateriasEspeciales($id_alumno){ // Desbloquear Materias con 2 requisitos, servicio social, practicas profesionales
        $SO = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS252')->get();
        if($SO[0]->status == 0){
        $Ensamblador = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS005')->get();
        $EstructuraDatos = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS013')->get();
        if($Ensamblador[0]->status == '3' && $EstructuraDatos[0]->status == '3'){
            $SO[0]->status = 1;
            }else{
              $SO[0]->status = 0;
              }
              $SO[0]->save();
              }

              $Graficacion = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS261')->get();
              if($Graficacion[0]->status == 0){
              $ProgramacionII = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS010')->get();
              $AlgebraLinealEGA = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'ICCS003')->get();
              if($ProgramacionII[0]->status == '3' && $AlgebraLinealEGA[0]->status == '3'){
                  $Graficacion[0]->status = 1;
                  }else{
                    $Graficacion[0]->status = 0;
                    }
                    $Graficacion[0]->save();
                  }

                    $ProgramacionConcurrenteParalela = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'ICCS251')->get();
                    if($ProgramacionConcurrenteParalela[0]->status ==0){
                    $AnalisisDiseñoAlgoritmos = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'ISCO201')->get();
                      $EstructuraDatos = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'CCOS013')->get();
                    if($EstructuraDatos[0]->status == '3' && $AnalisisDiseñoAlgoritmos[0]->status == '3'){
                        $ProgramacionConcurrenteParalela[0]->status = 1;
                        }else{
                          $ProgramacionConcurrenteParalela[0]->status = 0;}
                          $ProgramacionConcurrenteParalela[0]->save();
                          }
                          // SERVICIO SOCIAL Y PRACTICAS PROFESIONALES
                          $Creditos = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',$id_alumno)
                          ->select('materia.nombre','materia.creditos','alumno_materia.status')
                          ->where('alumno_materia.status',3)->get();
                          if($Creditos->SUM('creditos')>=197){
                            $ServicioSocial = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'SSCC100')->get();
                            $PracticaProfesional = Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'PPCc101')->get();

                            if($ServicioSocial[0]->status == '0' && $PracticaProfesional[0]->status == '0'){
                            $ServicioSocial[0]->status=1;
                            $PracticaProfesional[0]->status=1;
                            $ServicioSocial[0]->save();
                            $PracticaProfesional[0]->save();
                          }
                          }
                          // Admin De Proyectos
                          $NivelBasico = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',$id_alumno)
                          ->where('alumno_materia.status',3)->where('materia.tipo_materia','Basica')
                          ->select('materia.nombre','materia.creditos','alumno_materia.status')->get();
                           $AdminProyectos= Alumno_Materia::where('id_alumno',$id_alumno)->where('id_materia', 'IDDS001')->get();
                           if($NivelBasico->count()>10 && $AdminProyectos[0]->status == 0){
                            $AdminProyectos[0]->status = 1;
                            $AdminProyectos[0]->save();
                            }
    }



      public function DesbloquearMaterias($id_alumno,$id_materia){ // desbloquear materias con un requisito
      $Materias = Materia::where('materia_requisito',$id_materia)->get(); // obtenemos las materias que se van a desbloquear
      foreach ($Materias as $materia) { // Actualizaremos el status de nuevas materias
      $ActualizarMaterias = Alumno_Materia ::where('id_alumno',$id_alumno)->where('id_materia',$materia->id_materia)->get();
      $ActualizarMaterias[0]->status=1;
      $ActualizarMaterias[0]->save();
      }}


      public function promedioPorcentaje($id_Alumno){
        $MateriasAprobadas = Alumno_Materia::where('id_alumno',$id_Alumno)->where('status', 3)->get();
        if($MateriasAprobadas->SUM('calificacion') != 0 && $MateriasAprobadas->count() !=0){
          $promedio = ($MateriasAprobadas->SUM('calificacion'))/($MateriasAprobadas->count());
          $promedio =  number_format($promedio, 2, '.', '');
        }else {
          $promedio = 0;
        }

        $Creditos = Materia::join('alumno_materia','materia.id_materia','=','alumno_materia.id_materia')->where('alumno_materia.id_alumno',$id_Alumno)
        ->where('alumno_materia.status',3)->select('materia.nombre','materia.creditos','alumno_materia.status')->get();
        $CreditosGanados = $Creditos->SUM('creditos');
        if($CreditosGanados !=0){
          $porcentaje =($CreditosGanados*100)/287;
        }else {
          $porcentaje = 0;
        }
        $porcentaje =($CreditosGanados*100)/287;
        $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
        $Alumno[0]->promedio_general = $promedio;
        $Alumno[0]->porcentaje = $porcentaje;
        $Alumno[0]->save();

      }

      public function updateMateria(Request $request){
      $Alumno = Alumno::where('matricula',auth()->user()->matricula)->get();
      $Alumno_materia = Alumno_Materia::where('id_Alumno',$Alumno[0]->id_alumno)->where('id_materia',$request->materia)->get();

      if($request->cursar == '1'){
          $Alumno_materia[0]->status = 2;
          $Alumno_materia[0]->veces_cursada = $Alumno_materia[0]->veces_cursada +1;
      }else if ($request->aprobar == '1'){
          $Alumno_materia[0]->status = 3;
          $Alumno_materia[0]->calificacion = $request->Calificacion;
            self::DesbloquearMaterias($Alumno[0]->id_alumno,$request->materia); // Se desbloquean las materias que tenian como requisito esta materia
      }else if ($request->aprobar == '0'){
          $Alumno_materia[0]->status = 4;
      }else if ($request->reprobada_cursar == '1'){
          $Alumno_materia[0]->status = 2;
          $Alumno_materia[0]->veces_cursada = $Alumno_materia[0]->veces_cursada +1;
      }else if ($request->reprobada_cursar == '0'){
            $Alumno_materia[0]->status = 4;
      }
        $Alumno_materia[0]->save();
        self::DesbloquearMateriasEspeciales($Alumno[0]->id_alumno); // Se desbloquean las materias con otros requisitos
        self::promedioPorcentaje($Alumno[0]->id_alumno);
        return redirect()->back();
      }
}
