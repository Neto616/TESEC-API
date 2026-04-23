<?php

namespace App\Traits;

trait ApiResponse{
    public function success($message, $response = [], $codigo = 200){
        return response()->json([
            "success"=> true,
            "message"=> $message,
            "response"=> $response
        ], $codigo);
    }

    public function error($message, $response, $codigo = 500){
        $cleanCode = (is_numeric($codigo) && $codigo >= 400 && $codigo <= 599) 
                 ? (int)$codigo : 500;
                 
        return response()->json([
            "success"=> false,
            "message"=> $message,
            "response"=> $response
        ], $cleanCode);
    }
}