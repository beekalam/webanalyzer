<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Nas extends Model
{
	protected $primaryKey  = 'nas_id';
	protected $table='nases';
	protected $fillable = ['nasip', 'username', 'password','description'];
	public $timestamps = false;
}

