<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model{

  protected $table = 'grupo';
  protected $primaryKey = 'id_grupo';
  public $timestamps = false;

}
