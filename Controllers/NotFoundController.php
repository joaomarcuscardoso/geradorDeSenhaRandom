<?php 
class NotFoundController extends Controller {
    public function index() {

        $this->loadTemplate("NotFound", array());
    }
}