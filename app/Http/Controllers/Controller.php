<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function successResponse($data)
    {
        return response()->json($data, 200);
    }

    public function entityCreated($data)
    {
        return response()->json($data, 201);
    }

    public function badRequest($data)
    {
        return response()->json($data, 400);
    }

    public function notFound()
    {
        return response()->json([
            'status' => 'not found',
            'success' => false,
            'message' => 'the resource you are looking for cannot be found'], 400);
    }

    public function otherResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }
}
