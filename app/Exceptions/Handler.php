<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->messages()
            ], 422);
        }elseif($e instanceof \Illuminate\Http\Client\ConnectionException){
            return response()->json([
                'success' => false,
                'message' => 'P10 - Unable to process request at the moment, try again!'
            ], 400);
        }elseif ($e instanceof \Illuminate\Database\QueryException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }elseif ($e instanceof \PDOException) {
            return response()->json([
                'success' => false,
                'message' => 'P20 - Unable to process request at the moment'
            ], 500);
        }elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => '404 - Not Found'
            ], 404);
        }elseif ($e instanceof \BadMethodCallException) {
            return response()->json([
                'success' => false,
                'message' => 'P40 - Unable to process request at the moment'
            ], 500);
        }elseif ($e instanceof \Exception) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }elseif ($e instanceof \ArgumentCountError) {
            return response()->json([
                'success' => false,
                'message' => 'P50 - Unable to process request at the moment',
            ], 400);
        }elseif ($e instanceof \TypeError) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        return parent::render($request, $e);
    }
}
