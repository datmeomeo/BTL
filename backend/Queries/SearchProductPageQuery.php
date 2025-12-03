<?php

namespace Queries;

use Exception;
use PDO;

class SearchProductPageQuery
{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }
    /**
     * @throws Exception
     */

    public function handle (int $bookId): SearchProductPageDto
    {
        
    }
}