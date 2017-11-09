<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:09 PM
 */

namespace KielPack\LaraLibs\Supports;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class Result
{
    public static function ok($message = '',$data = []) {

        return [
            'isOk'      => true,
            'message'   => $message,
            'data'      =>  $data
        ];
    }

    public static function response($data = array()) {

        return response()->json($data,200);
    }

    public static function badRequest($errors = array()) {

        return new JsonResponse($errors,500);

    }

    public static function badRequestWeb($exception) {

        return Response::view('errors.500',compact('exception'))->header('Content-Type', "text/html");

    }
}