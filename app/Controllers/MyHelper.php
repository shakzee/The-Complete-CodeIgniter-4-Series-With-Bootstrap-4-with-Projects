<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/19/2019
 * Time: 8:31 PM
 */

namespace App\Controllers;
use CodeIgniter\Controller;

class MyHelper extends Controller
{
    public function index()
    {


        /*$myArray =  array(
            'width'=>500,
            'height'=>500,
            'scrollbars'=>'yes',
            'status'=>'yes',
            'resize'=>'yes',
        );
        echo anchor_popup('MyHelper/user','Please click me',$myArray);*/
        //echo current_url();
        //echo anchor('myHelper','go to controller');
        //echo uri_string();
        //echo current_url();

        //base_url('images/one.jpg');
        //echo site_url('controller/method');
        /*helper('inflector');
        echo $myDog = singular('dogs');
        echo '<br>';
        echo plural($myDog);
        echo '<br>';
        echo camelize('my cat is fine');
        echo '<br>';
        echo underscore('my cat is fine');
        echo '<br>';
        echo humanize('my_cat_is_fine');*/
        /*helper('form');

        echo form_open('MyHelper/user',array('id'=>'xyz','class'=>'xyz'));
        echo form_input('name[]','',array('id'=>'myid','class'=>'myclass'));
        echo form_textarea('myTextArea','',array('class'=>'myclass'));
        echo form_password('mypassword','',array('class'=>'myclass'));
        echo form_upload('fileName','','');
        $myvar = array(
            '1'=>1,
            '2'=>2,
            '3'=>3,
            '4'=>4,
        );
        //echo form_dropdown('myDropDown',array('1'=>1,'2'=>2),'2');
        echo form_multiselect('xyz',$myvar);
        echo form_label('Enter your Name','xyz');
        echo form_checkbox('mycheck','xyz',false,'');
        echo form_checkbox('mycheck','xyz',false,'');
        echo form_checkbox('mycheck','xyz',false,'');
        echo form_radio('myRadio','xyz',true,'');
        echo form_radio('myRadio','xyz',true,'');
        echo form_radio('myRadio','xyz',true,'');
        echo form_hidden('xyz','my value','');
        echo form_submit('Send Now','Send Now','');
        echo form_close();*/
        //echo random_string('nozero',10);
       /* helper('html');
        $mycolors = ['red','blue','green'];
        echo ol($mycolors,'id="red"');*/


        //echo script_tag('custom.js');

/*        $myCss =[
            'alt'=>'',
            'href'=>'css/style.css'
        ];
        echo link_tag($myCss);*/
        /*$myVar = [
            'width'=>500,
            'height'=>500,
            'src'=>'images/one.jpg',
        ];

        echo img($myVar);*/

    }

    public function user()
    {
        echo 'working form..';
    }
}