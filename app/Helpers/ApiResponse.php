<?php

if (! function_exists("successResponse")) {

    function successResponse($message = "Success! Action completed.", $data = [])
    {
        $response = [
            'status'  => "success",
            'message' => $message,
        ];
        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response);

    }
}

if (! function_exists("warningResponse")) {

    function warningResponse($message = "Warning: Please check your inputs before proceeding.", $data = [])
    {
        $response = [
            'status'  => "warning",
            'message' => $message,
        ];
        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, 422);

    }
}

if (! function_exists("errorResponse")) {

    function errorResponse( Exception $e ,$message = "Error: Something went wrong. Please try again.")
    {
        $response = [
            'status'            => "error",
            'message'           => $message,
            'exception_message' => $e->getMessage(),
            "exception_file"    => $e->getFile(),
            "exception_line"    => $e->getLine(),
        ];

        return response()->json($response, 500);

    }
}
