<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Str;
use Throwable;
use Log;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    function render($request, Throwable $exception)
    {
        $hash = Str::random(15);
        $error = env('APP_ENV') == 'production' ? $hash : $exception->getMessage();
        
        if ($exception instanceof ValidationException) {
            $errors = self::getCustomMessagesByValidator($exception->errors());
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(["message" => $errors], 422);
        }

        if ($exception instanceof AuthenticationException) {
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // if ($exception instanceof \Exception) {
        //     dd($exception);
        Log::debug(__METHOD__ . ' -> ' . __LINE__);//     
        return response()->json(['message' => 'Whoops, looks like something went wrong.', 'code' => $error], 500);
        // }
        
        if ($exception instanceof MethodNotAllowedHttpException) { // for checking  api method
            // do stuff
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(['message' => 'Route not found.', 'code' => $error], 404);
        }

        if ($exception instanceof NotFoundHttpException) { // for 404
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(['message' => 'Not Found Http', 'code' => $error], 404);
        }
        try {
            //code...
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(['message' => 'Whoops, looks like something went wrong.', 'code' => $error], $exception->getStatusCode());
        } catch (\Throwable $th) {
            Log::debug(__METHOD__ . ' -> ' . __LINE__);
            return response()->json(['message' => 'Whoops, looks like something went wrong.', 'code' => $error], 500);
        }
    }

    /**
     * [customize message errors by validator request]
     *
     * @param   array  $errors  [array of errores]
     *
     * @return  string          [errores]
     */
    static function getCustomMessagesByValidator(array $errors)
    {
        foreach ($errors as $key => $value) {
            $errors[$key] = implode(" | ", $value);
        }
        return $errors;
    }
}
