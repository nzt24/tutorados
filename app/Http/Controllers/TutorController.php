<?php

namespace App\Http\Controllers;
use App\User;
use App\Grupo;
use App\Tutor;
use App\Alumno;
use App\Materia;
use App\Alumno_Materia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Imports\ImportarAlumnos;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class TutorController extends Controller{


    public function __construct(){
    $this->middleware(['auth','role:tutor']);
    }

    public function index(){
          return view("tutor.grupos");
    }

    public function perfil(){
          $tutor = Tutor::where('matricula',auth()->user()->matricula)->get();
          return view("tutor.perfil",[
            'Tutor' => $tutor[0],
          ]);
    }

    public function reportes(){
          return view("tutor.reportes");
    }


    public function reporteAlumnoMateria(Request $request){

      $NombreMateria = Materia::where('id_materia',$request->materia)->get();

      $alumnosAprobados = DB::table('alumno_materia')->join('alumno', 'alumno_materia.id_alumno', '=', 'alumno.id_alumno')
      ->where('alumno.id_grupo',$request->id_grupo)->where('alumno_materia.calificacion', '>=', 6)
      ->where('alumno_materia.id_materia',$request->materia)->get()->count();

      $alumnosReprobados = DB::table('alumno_materia')->join('alumno', 'alumno_materia.id_alumno', '=', 'alumno.id_alumno')
      ->where('alumno.id_grupo',$request->id_grupo)->where('alumno_materia.calificacion', '<', 6)
      ->where('alumno_materia.id_materia',$request->materia)->get()->count();

      return View('tutor.reporteAlumnoMateria',
      ['NombreMateria' => $NombreMateria[0]->nombre,
       'AlumnosAprobados' => $alumnosAprobados,
       'AlumnosReprobados' => $alumnosReprobados,
      ]);
    }


    public function ActualizarPerfilTutor(Request $request){
     $Tutor = Tutor::where('matricula',auth()->user()->matricula)->get();
     $Tutor[0]->nombre = $request->nombre;
     $Tutor[0]->apellido_paterno = $request->paterno;
     $Tutor[0]->apellido_materno = $request->materno;
     $Tutor[0]->correo = $request->correo;
     $Tutor[0]->horario_asesorias = $request->horario;
     $Tutor[0]->cubiculo = $request->cubiculo;
     $Tutor[0]->save();

      return response()->json(['status' => 'correcto'],);
    }

    public function CambiarContraseña(Request $request){
      $user = User::where('matricula',auth()->user()->matricula)->get();
      $user[0]->password = bcrypt($request->password1);
      $user[0]->save();
      return response()->json(['status' => 'correcto'],);
    }


    public function CargarDocumento(Request $request){
      $tutor = Tutor::where('matricula',auth()->user()->matricula)->get();
      $grupo = Grupo::where('generacion',$request->periodo)->where('seccion',$request->seccion)->where('id_tutor',$tutor[0]->id_tutor)->get();

      if($grupo->count() == 0){
        $nombre_archivo=$request->seccion.'_'.$request->periodo.'_'.$request->carrera.'_'.auth()->user()->matricula.'.xlsx';
        $ppath = $request->file('excel')->storeAs('public',$nombre_archivo);
         //hacemos una importacion
         $alumnos = Excel::toCollection(new ImportarAlumnos,'../storage/app/public/'.$nombre_archivo);
         //return $alumnos;
         return response()->json(['existe_grupo' => 'false', 'alumnos' => $alumnos]);
      }else{
        return response()->json(['existe_grupo' => 'true', 'alumnos' => '']);
      }

    }

    public function agregarGrupo(Request $request){

        // Guardamos el archivo en storage/public
        $nombre_archivo=$request->seccion.'_'.$request->periodo.'_'.$request->carrera.'_'.auth()->user()->matricula.'.xlsx';
        $ppath = $request->file('excel')->storeAs('public',$nombre_archivo);
        //hacemos una importacion
        $array = Excel::toCollection(new ImportarAlumnos,'../storage/app/public/'.$nombre_archivo);
        $array=$array[0];
        //borramos el archivo
        Storage::delete($ppath);

        //ingresamos datos en la base de datos
        //Creamos el grupo
         $tutor = Tutor::where('matricula',auth()->user()->matricula)->get();
        // return $tutor[0]->id_tutor;
         $grupo = new Grupo;
         $grupo->generacion = $request->periodo;
         $grupo->seccion = $request->seccion;
         $grupo->id_licenciatura = $request->licenciatura;
         $grupo->id_tutor = $tutor[0]->id_tutor;
         $grupo->save();
        //
        // //obtenemos el id_grupo del grupo creado
           $grupo = Grupo::where('generacion',$request->periodo)->where('seccion',$request->seccion)->where('id_licenciatura',$request->licenciatura)->where('id_tutor',$tutor[0]->id_tutor)->get();
         // creamos las cuentas de los alumnos

        // //tabla user
          foreach ($array as $alumno ) {
            if($alumno[0] != null){
              $user = new User;
              $user->matricula = $alumno[0];
              $user->password = bcrypt($alumno[0]);
              $user->rol = 'alumno';
              $user->save();
              }
          }

        // // tabla Alumnos
          foreach ($array as $alumno) {
              if($alumno[0] != null){
                $new_alumno = new Alumno;
                $new_alumno->nombre = $alumno[1];
                $new_alumno->apellido_paterno = $alumno[2];
                $new_alumno->apellido_materno = $alumno[3];
                $new_alumno->correo = '';
                $new_alumno->promedio_general = 0;
                $new_alumno->Porcentaje = 0;
                $new_alumno->matricula = $alumno[0];
                $new_alumno->id_grupo =  $grupo[0]->id_grupo;
                $new_alumno->save();
              }
          }

        // //obtenemos las materias que se cargaran al alumno
         $materias = Materia::where('id_licenciatura',$request->licenciatura)->get();
         // Cargamos a cada alumno sus materias
          foreach ($array as $alumno ) {
                  if($alumno[0] != null){
                    set_time_limit(0);
                      $id_alumno = Alumno::where('matricula',$alumno[0])->get();
                        foreach ($materias as $materia ) {
                            $alumno_materia = new Alumno_Materia;
                            $alumno_materia->id_alumno = $id_alumno[0]->id_alumno;
                            $alumno_materia->id_materia = $materia->id_materia;
                            $alumno_materia->status = 0;
                            $alumno_materia->calificacion = 0;
                            $alumno_materia->veces_cursada = 0;
                            $alumno_materia->save();
                            }

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','CCOS001')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','CCOS003')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','FGUS001')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','FGUS002')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','FGUS004')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','ICCS001')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','OPI')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','OPII')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                            $alumno_materia = Alumno_Materia::where('id_alumno',$id_alumno[0]->id_alumno)->where('id_materia','OPDESIT')->get();
                            $alumno_materia[0]->status = 1;
                            $alumno_materia[0]->save();

                        }
          }

    return redirect()->route('Tutor_Grupos');
    }


    public function verGrupo($seccion,$generacion,$carrera,$tutor){
      $grupo = Grupo::where('seccion',$seccion)->where('generacion', $generacion)->where('id_licenciatura', $carrera)->where('id_tutor', $tutor)->get();
      $Alumnos = Alumno::where('id_grupo', $grupo[0]->id_grupo)->get();
      return view('tutor.ver_alumnos', [
        'Alumnos' => $Alumnos,
        'Grupo' => $grupo,
      ] );
    }

    public function EliminarGrupo($seccion,$generacion,$carrera){
      $tutor = Tutor::where('matricula',auth()->user()->matricula)->get();
      $grupo = Grupo::where('generacion',$generacion)->where('seccion',$seccion)->where('id_licenciatura', $carrera)->where('id_tutor',$tutor[0]->id_tutor)->get();
      $Alumnos = Alumno::where('id_grupo',$grupo[0]->id_grupo)->get();


      foreach ($Alumnos as $Alumno) { //Eliminamos las materias de cada alumno
        $alumno_materiaDelete = Alumno_Materia::where('id_alumno',$Alumno->id_alumno)->delete();
      }

      // Eliminamos los alumnos del grupo
      $alumnosDelete = Alumno::where('id_grupo',$grupo[0]->id_grupo)->delete();

      // Eliminamos el grupos
      $grupoDelete = Grupo::where('id_grupo',$grupo[0]->id_grupo)->delete();

      // Eliminamos los usuario del sistema
      foreach ($Alumnos as $Alumno) {
        $user = User::where('matricula',$Alumno->matricula)->where('rol', 'alumno')->delete();
      }

      return redirect()->route('Tutor_Grupos');
    }

    public function verAlumno($id_alumno){
      $Alumno = Alumno::where('id_alumno',$id_alumno)->get();
      $Grupo = Grupo::where('id_grupo',$Alumno[0]->id_grupo)->get();

      return view('tutor.mapa_grafico_alumno', [
        'Alumno' => $Alumno[0],
        'Grupo' => $Grupo[0],
      ] );
    }


    public function MapaGraficoAlumno(Request $request){
    //return response()->json(['success'=>'Got Simple Ajax Request.']);
    $Materias = Alumno_Materia::where('id_alumno',$request->id_alumno)->get();
    return $Materias;
    }
}
