<?php

namespace Jeanp\Jelper\Helpers;

use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class Response {

    /**
     * @param string $message
     * @param array $data
     * @param string|null $redirect
     */
    public function success($message = '',$data = [], $redirect = null){


        if(request()->ajax()){
            return response()->json(['isSuccess' => true, 'message' => $message, 'data' => $data, 'redirect' => $redirect]);
        }

        Session::flash('success', $message);

        if(!$redirect){
            return redirect()->back();
        }

        return redirect($redirect);
    }

    /**
     * @param string $message
     * @param array $data
     * @param string|null $redirect
     */
    public function error($message, $data = [], $redirect = null){


        if(request()->ajax()){
            return response()->json(['isSuccess' => false, 'message' => $message, 'data' => $data]);
        }

        Session::flash('error', $message);

        if(!$redirect){
            return back()->withInput(request()->all());
        }

        return redirect($redirect);
    }

    /**
     * @param Exception $e
     */
    public function catch($e, $fullMessage = false){

        $message = $e->getMessage();

        if($fullMessage){
            $message = $e->getMessage() . ' | Line: '. $e->getLine() . ' | File: '. $e->getFile();
        }

        if(request()->ajax()){
            return response()->json(['isSuccess' => false, 'message' => $message]);
        }

        Session::flash('error', $message);

        return back()->withInput(request()->all());
    }

    public function getCatch($e, $fullMessage = true){
        $message = $e->getMessage();

        if($fullMessage){
            $message = $e->getMessage() . ' | Line: '. $e->getLine() . ' | File: '. $e->getFile();
        }

        return $message;
    }

    public function withStoreMessage($message = null, $data = [], $redirect = null){

        return $this->success(
            message: $message ?? 'Guardado correctamente.', 
            data: $data,
            redirect: $redirect
        );
    }

    public function withUpdateMessage($message = null, $data = [], $redirect = null){

        return $this->success(
            message: $message ?? 'Actualizado correctamente.', 
            data: $data,
            redirect: $redirect
        );
    }

    public function withDeleteMessage($message = null, $data = [], $redirect = null){

        return $this->success(
            message: $message ?? 'Eliminado correctamente.', 
            data: $data,
            redirect: $redirect
        );
    }

    public function dataTables($data){

        return DataTables::of($data)->addIndexColumn()->toJson();
    }

    public function getDataTable($data){

        return DataTables::of($data)->addIndexColumn();
    }
}
