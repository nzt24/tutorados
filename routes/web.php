<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


  Route::post('registrar_tutor','AdministradorController@agregarTutor')->name('registrar');

// RUTAS PARA INICIO DE SESION,LOGIN,LOGOUT
  Route::get('/','Auth\LoginController@ShowLoginForm');
  Route::post('login','Auth\LoginController@login')->name('login');
  Route::get('logout','Auth\LoginController@Logout')->name('logout');

  //ALUMNO
  Route::get('Alumno','AlumnoController@index')->name('Alumno');
  Route::get('Alumno/Perfil','AlumnoController@perfil')->name('Alumno_Perfil');
  Route::get('Alumno/Mapa_grafico','AlumnoController@mapaGrafico')->name('Alumno_MapaGrafico');
  Route::get('Alumno/Avance','AlumnoController@avance')->name('Alumno_Avance');
  Route::post('ActualizarNombreAlumno','AlumnoController@ActualizarNombre')->name('ActualizarNombreAlumno');
  Route::post('ActualizarCorreoAlumno','AlumnoController@ActualizarCorreo')->name('ActualizarCorreoAlumno');
  Route::post('ActualizarContraseñaAlumno','AlumnoController@ActualizarContraseña')->name('ActualizarContraseñaAlumno');
  Route::post('ActualizarMateria','AlumnoController@updateMateria')->name('ActualizarMateria');
  Route::get('ColorMapaGrafico','AlumnoController@ColorMapaGrafico');
  Route::get('MateriaEspecial','AlumnoController@MateriasEspeciales')->name('MateriaEspecial');

  // TUTOR
  Route::get('Tutor','TutorController@index')->name('Tutor_Grupos');
  Route::get('Tutor/Perfil','TutorController@perfil')->name('Tutor_Perfil');
  Route::post('ActualizarPerfilTutor','TutorController@ActualizarPerfilTutor')->name('ActualizarPerfilTutor');
  Route::post('CambiarContraseñaTutor','TutorController@CambiarContraseña')->name('cambiarContraseñarTutor');
  Route::post('agregarGrupo','TutorController@agregarGrupo')->name('agregarGrupo');
  Route::post('/CargarDocumento','TutorController@CargarDocumento');
  Route::get('Ver_Grupo/seccion/{seccion?}/generacion/{generacion?}/carrera/{carrera}/tutor/{tutor}', 'TutorController@verGrupo')->name('Ver_Grupo');
  Route::get('EliminarGrupo/seccion/{seccion?}/generacion/{generacion?}/carrera/{carrera}', 'TutorController@EliminarGrupo')->name('EliminarGrupo');
  Route::get('/Tutor/Grupo/Alumno/{id_alumno}','TutorController@verAlumno')->name('Ver_Alumno');
  Route::post('MapaGraficoAlumno','TutorController@MapaGraficoAlumno')->name('MapaGraficoAlumno');
  Route::get('reportes','TutorController@reportes')->name('reportes');
  Route::post('ReporteAlumnoMateria','TutorController@reporteAlumnoMateria')->name('ReporteAlumnoMateria');


  //ADMINISTRADOR

  Route::get('Admin','AdministradorController@index')->name('Admin_Tutores');
  Route::get('Admin/Cuenta','AdministradorController@cuenta')->name('Admin_Cuenta');
  Route::post('registrar_tutor','AdministradorController@agregarTutor')->name('registrar');

//  RUTA PRUEBA PARA CREAR USUARIOS
  Route::post('crear','Auth\RegisterController@create')->name('crear');
  Route::get('/AgregarAdmin', function(){
    return view('crear');
  });

// Route::get('/Query', function(){
//
// $tutor = App\Tutor::where('matricula',auth()->user()->matricula)->get()->find(1);
// $grupos = App\Grupo::where('id_tutor',$tutor->id_tutor)->get();
// echo $grupos;
//
//
// });
