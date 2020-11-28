<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        return response([
            'error' => $exception->getMessage(),
        ], $exception->getCode() ? $exception->getCode() : 400);
    }

    public function unauthenticated($request, $exception)
    {
        return response(['error' => 'unauthenticated'], 401);
    }
}
