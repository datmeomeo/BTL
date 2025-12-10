<?php
require_once __DIR__ . "/../models/BookImages.php";

class Book_imagesController{
    private $model;

    public function __construct($db){
        $this->model = new BookImages($db);
    }

    public function index(){
        
    }
}