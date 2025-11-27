<?php
namespace Core;

class BaseController {
    
    // Trả về JSON Response chuẩn
    protected function jsonResponse($data, $status = 200) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    // Lấy dữ liệu từ POST hoặc GET an toàn
    protected function getInput($key = null, $default = null) {
        // Ưu tiên lấy từ JSON Body (cho fetch API)
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData && is_array($jsonData)) {
            if ($key === null) return $jsonData;
            return isset($jsonData[$key]) ? $jsonData[$key] : $default;
        }

        // Nếu không có JSON, lấy từ $_POST
        if ($key === null) return $_POST;
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}
