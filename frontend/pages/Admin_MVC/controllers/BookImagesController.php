<?php
require_once "models/BookImages.php";

class BookImagesController{
    private $model;

    public function __construct($db){
        $this->model = new BookImages($db);
    }

    public function index(){
        
    }
}