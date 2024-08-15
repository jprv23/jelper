<?php

namespace App\Helpers;

class ResponseApi {

    /**
     * @param string $message
     * @param array $data
     */
    public function success($message = '',$data = []){

        return response()->json(['isSuccess' => true, 'message' => $message, 'data' => $data]);
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function error($message, $data = []){

        return response()->json(['isSuccess' => false, 'message' => $message, 'data' => $data], 500);
    }

    /**
     * @param Exception $e
     */
    public function catch($e, $fullMessage = false){

        $message = $e->getMessage();

        if($fullMessage){
            $message = $e->getMessage() . ' | Line: '. $e->getLine() . ' | File: '. $e->getFile();
        }

        return response()->json(['isSuccess' => false, 'message' => $message], 500);
    }

    public function withStoreMessage($message = null, $data = []){

        return $this->success(
            message: $message ?? 'Guardado correctamente.', 
            data: $data,
        );
    }

    public function withUpdateMessage($message = null, $data = []){

        return $this->success(
            message: $message ?? 'Actualizado correctamente.', 
            data: $data,
        );
    }

    public function withDeleteMessage($message = null, $data = []){

        return $this->success(
            message: $message ?? 'Eliminado correctamente.', 
            data: $data,
        );
    }
}
