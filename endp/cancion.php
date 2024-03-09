<?php
require_once '../respuestas/response.php';
require_once '../modelos/cancion.class.php';
require_once '../modelos/auth.class.php';

$auth = new Authentication();
$auth->verify();

$cancion = new Cancion();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $params = $_GET;

        if (isset($_GET['id_usuario']) && !empty($_GET['id_usuario'])){
            if ($_GET['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            $params['id_usuario'] = $auth->getIdUser();
        }

        $canciones = $cancion->get($params);
        $url_raiz_img = "http://".$_SERVER['HTTP_HOST']."/api-canciones/public/img";
        for($i=0; $i< count($canciones); $i++){
            if (!empty($canciones[$i]['imagen']))
                $canciones[$i]['imagen'] = $url_raiz_img ."/". $canciones[$i]['imagen'];
        }

        $response = array(
            'result'=> 'ok',
            'canciones'=> $canciones
        );
        Response::result(200, $response);
        break;
    
    case 'POST':
        $params = json_decode(file_get_contents('php://input'), true);
     
        if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id pasado por body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            $params['id_usuario'] = $auth->getIdUser();
        }

        $insert_id_cancion = $cancion->insert($params);
        $id_param['id'] = $insert_id_cancion;
        $cancion = $cancion->get($id_param);
        if($cancion[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-canciones/public/img/".$cancion[0]['imagen'];
        else
            $name_file = '';

        $response = array(
            'result' => 'ok insercion',
            'insert_id' => $insert_id_cancion,
            'file_img'=> $name_file
        );

        Response::result(201, $response);
        break;


    case 'PUT':
        $params = json_decode(file_get_contents('php://input'), true);

        if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])  ){
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud de actualización del cancion. No has pasado el id del cancion'
            );

            Response::result(400, $response);
            exit;
        }

        if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id del body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            $params['id_usuario'] = $auth->getIdUser();
        }

        $cancion->update($_GET['id'], $params);
        $id_param['id'] = $_GET['id'];
        $cancion = $cancion->get($id_param);
       
        if($cancion[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-canciones/public/img/".$cancion[0]['imagen'];
        else
            $name_file = '';
            
        $response = array(
            'result' => 'ok actualizacion',
            'file_img'=> $name_file
        );

        Response::result(200, $response);
        break;


    case 'DELETE':
        if(!isset($_GET['id']) || empty($_GET['id'])){
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud'
            );

            Response::result(400, $response);
            exit;
        }

        $cancion->delete($_GET['id']);

        $response = array(
            'result' => 'ok'
        );

        Response::result(200, $response);
        break;

    default:
        $response = array(
            'result' => 'error'
        );

        Response::result(404, $response);

        break;
}
