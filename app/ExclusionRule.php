<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ExclusionRule extends Model
{
	protected $primaryKey  = 'exclusion_rules_id';
	protected $table='exclusion_rules';
	protected $fillable = ['exclusion_name', 'exclusion_value'];
	public $timestamps = false;
}