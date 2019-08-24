<?php namespace App\Models;

use CodeIgniter\Model;

class MyStudents extends Model
{
	protected $DBGroup = 'default';
	protected $table = 'students';


	protected $primaryKey = 's_id';
	protected $returnType = 'object';

	protected $allowedFields = ['s_name','s_subjects','s_age'];
	protected $useTimestamps = true;
	protected $createdField = 's_date';
	protected $updatedField = 's_updated';


	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = false;

}
