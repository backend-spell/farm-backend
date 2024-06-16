<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    /**
     * Success Response.
     *
     * @param  mixed  $data
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function successResponse(mixed $data, string $message = '', int $statusCode = Response::HTTP_OK)
    {
        return response()->json([$data, $message, $statusCode]);
    }

    public function errorResponse(mixed $data, string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {

        if (!$message) {
            $message = Response::$statusTexts[$statusCode];
        }

        $data = [
            'message' => $message,
            'errors' => $data,
        ];

        return response()->json([$data, $message, $statusCode]);

    }

    /**
     * Response with status code 200.
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    public function okResponse(mixed $data, string $message = '')
    {
        return $this->successResponse($data, $message);
    }

    /**
     * Response with status code 201.
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    public function createdResponse(mixed $data)
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    public function noContentResponse()
    {
        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }


    public function badRequestResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

  
    public function unauthorizedResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_UNAUTHORIZED);
    }


    public function forbiddenResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_FORBIDDEN);
    }

    public function notFoundResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

 
    public function conflictResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_CONFLICT);
    }

    public function unprocessableResponse(mixed $data, string $message = '')
    {
        return $this->errorResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}