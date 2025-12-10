<?php
class BookImages
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
}