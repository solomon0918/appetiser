<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class ApiController extends Controller
{
    public function respond($data, $status){
        return response()->json($data, $status);
    }
    
    public function success($message = "Success"){
        return $this->respond($message, Config::get("const.status_code.success"));
    }

    public function invalid($message = "Invalid Request"){
        return $this->responseWithError($message, Config::get("const.status_code.invalid"));
    }

    public function notFound($message = "NotFound"){
        return $this->responseWithError($message, Config::get("const.status_code.not_found"));
    }

    public function responseWithError($message, $status){
        return $this->respond([
            "error" => [
                "message" => $message
            ]
        ], $status);
    }
}
