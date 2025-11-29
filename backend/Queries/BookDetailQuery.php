<?php
namespace Queries;
use PDO;

class BookDetailPageQuery
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function handle()
    {
        return 'test';
    }
}