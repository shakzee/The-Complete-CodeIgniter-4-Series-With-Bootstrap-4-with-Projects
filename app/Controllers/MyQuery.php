<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/26/2019
 * Time: 11:05 PM
 */

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;

class MyQuery extends Controller
{


    public function index()
    {
                $db = \Config\Database::connect();
        $myLists = $db->listTables();
        $tableData = $db->getFieldNames('students');
        foreach ($tableData as $mydata) {
            echo $mydata.'<br>';

        }

        /*        if ($db->tableExists('studentsdfdf')) {
                    echo 'yes find.';
                }
                else{
                    echo 'not found.';
                }*/
        /*foreach ($myLists as $list) {
            echo $list.'<br>';
        }*/
        //var_dump();
        /*$builder = $db->table('students');
        $result = $builder->select('s_id,s_name')
                        ->where('s_id',3)
                        ->where('s_name','john')
                        ->getResult();
        var_dump($result);*/
        /*$builder->where('s_id',8);
        $result = $builder->delete();
        var_dump($result);*/
        /*        $result = $builder->update(['s_name'=>'shakzee 2'],['s_id'=>6]);
                var_dump($result);*/
        /*$data = [
            [
                's_name'=>'shakzee',
                's_subjects'=>'java',
                's_age'=>40
            ],
            [
                's_name'=>'mark',
                's_subjects'=>'jquery',
                's_age'=>30
            ],
            [
                's_name'=>'john alex',
                's_subjects'=>'ajax',
                's_age'=>25
            ],


        ];
        $result = $builder->insertBatch($data);
        //$result = $builder->insert($user);
        var_dump($result);*/
        /*$builder->select('s_id,s_name');
        $builder->orderBy('s_id','asc');
        $builder->limit(1);
        $result = $builder->get();
        var_dump($result->getResult());
        echo '<br>';
        echo '<br>';
        echo $db->getLastQuery();*/

        /*$builder->selectMin('s_age');
        //$builder->groupBy('s_age');
        $builder->having('s_age' ,20);
        $result = $builder->get();
        var_dump($result->getResult());
        echo '<br>';
        echo '<br>';
        echo $db->getLastQuery();*/

        /* $builder->select('s_id,s_name');
         $builder->like('s_name','sha');
         $builder->notLike('s_subjects','ja');*/
        /*$myArray = ['s_name'=>'sha','s_id'=>1];
        $builder->like($myArray);*/

        //$builder->like('s_name','a','before');
        /*        $result = $builder->get();
                var_dump($result->getResult());
                echo '<br>';
                echo '<br>';
                echo $db->getLastQuery();*/

        /*$builder->select('s_id,s_name,s_age');
        $builder->whereNotIn('s_name',['alex','abc','john']);
        $builder->orWhereNotIn('s_name',['y','b','x']);*/
        //$builder->whereIn('s_name',['shakzee','john','xyz']);
        //$builder->orWhereIn('s_name',['maria','xyz','abc']);
        //$where = 's_id = 1 AND s_name = "shakzee"';
        //$builder->where($where);
        //$builder->where('s_id',1);
        //$builder->where(['s_id'=>2,'s_name'=>'alex']);
        //$builder->orWhere('s_id',100);
        //$builder->orWhere('s_name','john alex');
        //$builder->where('s_name','maria');
        /*$result = $builder->get();
        var_dump($result->getResult());
        echo '<br>';
        echo '<br>';
        echo $db->getLastQuery();*/
        //$builder->where('s_id',1);
        //$result = $builder->get();
        //var_dump($result);

        /* $db = \Config\Database::connect();
         $builder = $db->table('students');
         //echo $builder->countAll();
         //echo $db->getPlatform();
         echo $db->getVersion();*/
        /*$builder->where('s_id',1);
        $builder->get();
        echo $db->getLastQuery();*/
        /*$builder->selectAvg('s_age');
        $result = $builder->get();
        var_dump($result->getResult());*/



        //var_dump($result->getResult());
        /*$result = $builder->getWhere(['s_id'=>1]);
        var_dump($result->getResult())*/;
        /*$builder->select('s_id,s_name');
        $builder->where('s_id',2);
        $result = $builder->get();
        var_dump($result->getResultArray());*/

        /* foreach ($result->getResultArray() as $item)
        {
            echo $item['s_id'].'<br>';
            echo $item['s_age'].'<br>';
        }*/
        //var_dump();
        //$result = $builder->get(1,1);
        //select * from students limit 1

        /*$db = db_connect('','');
        var_dump($db);*/

        /*$query = $builder->get(1,1);
        var_dump($query->getResult());*/

        //var_dump($db->table('students'));

        /*  $db = \Config\Database::connect();
          $result = $db->query('select * from students');
          foreach ($result->getResultArray() as $student) {
              echo $student['s_id'].'<br>';
              echo $student['s_name'].'<br>';
              echo $student['s_subject'].'<br>';
              echo $student['s_age'].'<br>';
              echo $student['s_date'].'<br>';
          }*/
        //var_dump();
    }


}