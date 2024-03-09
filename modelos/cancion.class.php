<?php
require_once '../respuestas/response.php';
require_once '../modelos-datos/database.php';

class Cancion extends Database
{
    private $table = 'canciones';

    private $allowedConditions_get = array(
        'id',
        'id_usuario',
        'title',
        'artist',
        'genre',
        'duration',
        'imagen'
    );
    
    // parámetros permitidos para la inserción.
    private $allowedConditions_insert = array(
        'id_usuario',
        'title',
        'artist',
        'genre',
        'duration',
        'imagen'
    );
    
    // parámetros permitidos para la actualización.
    private $allowedConditions_update = array(
        'id_usuario',
        'title',
        'artist',
        'genre',
        'duration',
        'imagen'
    );

    private function validate($data){
        
        if(!isset($data['id_usuario']) || empty($data['id_usuario'])){
            $response = array(
                'result' => 'error',
                'details' => 'El campo id del usuario es obligatorio'
            );

            Response::result(400, $response);
            exit;
        }
        if(!isset($data['title']) || empty($data['title'])){
            $response = array(
                'result' => 'error',
                'details' => 'El campo título es obligatorio'
            );

            Response::result(400, $response);
            exit;
        }
        if(!isset($data['artist']) || empty($data['artist'])){
            $response = array(
                'result' => 'error',
                'details' => 'El campo artista es obligatorio'
            );

            Response::result(400, $response);
            exit;
        }

        /*
        Tengo que comprobar la extensión de la imagen de la canción
        */
        if (isset($data['imagen']) & !empty($data['imagen'])){
            $img_array = explode(';base64,', $data['imagen']);
            $extension = strtoupper(explode('/', $img_array[0])[1]);
            if ($extension !='PNG' && $extension!= 'JPG' && $extension!= 'JPEG'){
                $response = array('result'  => 'error', 'details' => 'Formato de la imagen no permitida, sólo PNG/JPE/JPEG');
                Response::result(400, $response);
                exit;
            }	
        }
            
        return true;
    }

    public function get($params){
        foreach ($params as $key => $param) {
            if(!in_array($key, $this->allowedConditions_get)){
                unset($params[$key]);
                $response = array(
                    'result' => 'error',
                    'details' => 'Error en la solicitud'
                );
    
                Response::result(400, $response);
                exit;
            }
        }

        $canciones = parent::getDB($this->table, $params);

        return $canciones;
    }

    public function insert($params)
    {
        foreach ($params as $key => $param) {
            if(!in_array($key, $this->allowedConditions_insert)){
                unset($params[$key]);
                
                $response = array(
                    'result' => 'error',
                    'details' => 'Error en la solicitud de insercion, por parametros'
                );	
    
                Response::result(400, $response);
                exit;
            }
        }
        

        if($this->validate($params)){
            if (isset($params['imagen'])){
                $img_array = explode(';base64,', $params['imagen']);  
                $extension = strtoupper(explode('/', $img_array[0])[1]); 
                $datos_imagen = $img_array[1]; 
                $nombre_imagen = uniqid(); 
                $path = dirname(__DIR__, 1)."/public/img/".$nombre_imagen.".".$extension;
                file_put_contents($path, base64_decode($datos_imagen));  
                $params['imagen'] = $nombre_imagen.'.'.$extension;  
            }

            return parent::insertDB($this->table, $params);
        }
    }

    public function update($id, $params)
    {
        foreach ($params as $key => $parm) {
            if(!in_array($key, $this->allowedConditions_update)){
                unset($params[$key]);
                $response = array(
                    'result' => 'error',
                    'details' => 'Error en la solicitud dentro del modelo datos'
                );
    
                Response::result(400, $response);
                exit;
            }
        }

        if($this->validate($params)){
            if (isset($params['imagen'])){
                $canciones = parent::getDB($this->table, $_GET);
                $cancion = $canciones[0];
                $imagen_antigua = $cancion['imagen'];
                $path = dirname(__DIR__, 1)."/public/img/".$imagen_antigua;
                unlink($path);

                $img_array = explode(';base64,', $params['imagen']);  
                $extension = strtoupper(explode('/', $img_array[0])[1]); 
                $datos_imagen = $img_array[1]; 
                $nombre_imagen = uniqid(); 
                $path = dirname(__DIR__, 1)."/public/img/".$nombre_imagen.".".$extension;
                file_put_contents($path, base64_decode($datos_imagen));  
                $params['imagen'] = $nombre_imagen.'.'.$extension;  
            }

            $affected_rows = parent::updateDB($this->table, $id, $params);
            if($affected_rows==0){
                $response = array(
                    'result' => 'error',
                    'details' => 'No hubo cambios'
                );
                
                Response::result(200, $response);
                exit;
            }
        }
    }

    public function delete($id)
    {
        $canciones = parent::getDB($this->table, $_GET);
        $cancion = $canciones[0];
        $imagen_antigua = $cancion['imagen'];
        if(!empty($imagen_antigua)){
            $path = dirname(__DIR__, 1)."/public/img/".$imagen_antigua;
            if (!unlink($path)){
                $response = array(
                    'result' => 'warning',
                    'details' => 'No se ha podido eliminar la imagen de la canción'
                );	
                Response::result(200, $response);
                exit;
            }

        }
        $affected_rows = parent::deleteDB($this->table, $id);

        if($affected_rows==0){
            $response = array(
                'result' => 'error',
                'details' => 'No hubo cambios'
            );

            Response::result(200, $response);
            exit;
        }
    }
}

?>