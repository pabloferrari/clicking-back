<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        if ($exception instanceof ValidationException) {
            $errors = self::getCustomMessagesByValidator($exception->errors());
            return response()->json(["error" => $errors], 422);
        }

        return response()->json($exception);
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
