<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        //overwrite Laravel's default Method not allowed exception into our default Json
        if ($exception instanceof MethodNotAllowedHttpException){

            return response()->json([
                'status'    => 'error',
                'message'   => 'method not allowed',
            ], 405);
        }

        //overwrite Laravel's default Method not allowed exception into our default Json
        if ($exception instanceof NotFoundHttpException){

            return response()->json([
                'status'    => 'error',
                'message'   => 'not found',
            ], 404);
        }

        //overwrite Laravel's default Method not allowed exception into our default Json
        if ($exception instanceof UnprocessableEntityHttpException){

            return response()->json([
                'status'    => 'error',
                'message'   => 'cannot process data',
            ], 422);
        }

        return parent::render($request, $exception);
    }
}
