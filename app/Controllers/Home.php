<?php namespace App\Controllers;
use App\Models\MyStudents;
use CodeIgniter\Controller;

class Home extends BaseController
{
	public function index()
	{

		echo view('home');

		die();
		$std = new MyStudents();
		$data['allStudents'] = $std->findAll();
		echo view('students',$data);

		//$result = $std->where('s_id',12)->delete();
		//$result = $std->delete([8,9,10]);
		//var_dump($result);
		/*$student = [
			's_name'=>'John Alex New',
			's_subjects'=>'Advance PHP',
			's_age'=>25,
		];
		$result = $std->update(2,$student);
		var_dump($result);*/

		/*$newStudents = [
			's_name'=>'new Alex third',
			's_subjects'=>'Data Science 3',
			's_age'=>23,
		];
		$results = $std->insert($newStudents);
		var_dump($results);*/
		/*$results = $std->select('s_id,s_name')
			->where('s_id',3)
			->findAll();*/
		//$results = $std->where('s_id',3)->findAll();

		//$results = $std->find([1,3,7]);

		//echo 'CodeIgniter 4 Series';
	}

    public function newUser()
    {
        $request = \Config\Services::request();
        echo $request->getPostGet('email');
        //echo 'new user method working...';
    }
    public function second()
    {
        echo 'second is working..';
    }
	//--------------------------------------------------------------------

}
