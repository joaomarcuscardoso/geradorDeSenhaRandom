<?php
class HomeController extends Controller {
    private $array;
    public function __construct() {
        $this->array = array();
    }
    public function index() {
        
        $users = new Users();

        $id_user = $users->getId();

       
        $this->loadTemplate("home", $this->array);
    }


    



}


