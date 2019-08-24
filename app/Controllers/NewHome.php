<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 3/27/2019
 * Time: 10:44 PM
 */
namespace App\Controllers;
use CodeIgniter\Controller;

class NewHome extends BaseController
{
    public function index()
    {


        $myData['data'] =  array(
            'id'=>array(20,30,40),
            'name'=>array('a','b','c')
        );
        //[10,20,30];
        //$myData['anotherData'] = [100,200,300];
        echo view('index',$myData);
        echo view('header');
        echo view('footer');
        //echo 'NewHome index method.';
    }

    public function second($para)
    {
        echo 'Second Method here..'.$para;
    }
}