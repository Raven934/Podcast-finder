<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     * 
     * This method provides beginner-friendly error messages for common exceptions
     * 
     * @param Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e)
    {
        try {
            // Handle API requests (JSON responses)
            if ($request->wantsJson() || $request->is('api/*')) {
                return $this->handleApiException($request, $e);
            }

            // For non-API requests, use default Laravel handling
            return parent::render($request, $e);
            
        } catch (\Exception $renderException) {
            // If something goes wrong in our error handling, fall back to default
            return parent::render($request, $e);
        }
    }

    /**
     * Handle exceptions for API requests with beginner-friendly messages
     * 
     * @param Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Model Not Found Exception (404)
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource Not Found',
                'message' => 'The requested resource could not be found.',
                'details' => 'Please check your request and try again.',
                'status_code' => 404
            ], 404);
        }

        // Validation Exception (422)
        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Failed',
                'message' => 'The provided data is invalid.',
                'errors' => $e->errors(),
                'details' => 'Please check the highlighted fields and correct any errors.',
                'status_code' => 422
            ], 422);
        }

        // Authentication Exception (401)
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Authentication Required',
                'message' => 'You must be logged in to access this resource.',
                'details' => 'Please login with valid credentials and try again.',
                'status_code' => 401
            ], 401);
        }

        // Authorization Exception (403)
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'error' => 'Access Denied',
                'message' => 'You do not have permission to access this resource.',
                'details' => 'Contact an administrator if you believe this is an error.',
                'status_code' => 403
            ], 403);
        }

        // Route Not Found Exception (404)
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Endpoint Not Found',
                'message' => 'The requested API endpoint does not exist.',
                'details' => 'Please check your URL and HTTP method.',
                'status_code' => 404
            ], 404);
        }

        // Method Not Allowed Exception (405)
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method Not Allowed',
                'message' => 'The HTTP method is not allowed for this endpoint.',
                'details' => 'Please check the allowed methods for this URL.',
                'status_code' => 405
            ], 405);
        }

        // Database Connection Errors (500)
        if ($e instanceof \PDOException || $e instanceof \Illuminate\Database\QueryException) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'A database error occurred while processing your request.',
                'details' => 'Please try again later or contact support if the problem persists.',
                'status_code' => 500
            ], 500);
        }

        // Generic Server Error (500)
        if (app()->environment(['local', 'testing'])) {
            // In development, show detailed error information
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
                'status_code' => 500
            ], 500);
        } else {
            // In production, show generic error message
            return response()->json([
                'error' => 'Server Error',
                'message' => 'An unexpected error occurred while processing your request.',
                'details' => 'Please try again later or contact support if the problem persists.',
                'status_code' => 500
            ], 500);
        }
    }
}
