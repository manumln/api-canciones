<?php
require_once '../respuestas/response.php';
require_once '../modelos/cancion.class.php';
require_once '../modelos/auth.class.php';

/**
 * endpoint para la gestión de datos con las canciones.
 * Get (para objeter todas las canciones)
 *  - token (para la autenticación y obtención del id usuario)
 * 
 * Post (para la creación de cancion)
 *  - token (para la autenticación y obtención del id usuario)
 *  - datos del cancion por body
 * 
 * Put (para la actualización del cancion)
 *  *  - token (para la autenticación y obtención del id usuario)
 *  - id del cancion por parámetro
 *  - datos nuevos del cancion por body
 * 
 * Delete (para la eliminación del cancion)
 *  *  - token (para la autenticación y obtención del id usuario)
 *  - id del cancion por parámetro
 * 
 */


$auth = new Authentication();
//Compara que el token sea el correcto 
$auth->verify();



//hasta aquí, el token está perfectamente verificada. Creamos modelo para que pueda gestionar las peticiones
$cancion = new Cancion();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $params = $_GET;  //aquí están todos los parámetros por url

       // $auth->insertarLog(); exit;
        //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
        if (isset($_GET['id_usuario']) && !empty($_GET['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($_GET['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        //necesitamos que esté obligatoriamente el id_usuario
        /*
        if(!isset($_GET['id_usuario']) || empty($_GET['id_usuario'])){
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud. id usuario desconocido '
            );

            Response::result(400, $response);
            exit;
        }
        
        if (!($auth->igualesIdUser($params["id_usuario"])))
        {
                $response = array(
                            'result' => 'error',
                            'details' => 'No tiene permisos para esa consulta'
                );
            
                Response::result(400, $response);
                exit;
        }
        */
        //Recuperamos todas las canciones
        $canciones = $cancion->get($params);
        //$auth->insertarLog('lleva a solicitud de canciones');
        $url_raiz_img = "http://".$_SERVER['HTTP_HOST']."/api-canciones/public/img";
        for($i=0; $i< count($canciones); $i++){
            if (!empty($canciones[$i]['imagen']))
                $canciones[$i]['imagen'] = $url_raiz_img ."/". $canciones[$i]['imagen'];
        }


/*
        $response = array(
            'result'=> 'ok',
            'details'=>"Hay canciones"
        );
        Response::result(200, $response);
        break;
*/
        $response = array(
            'result'=> 'ok',
            'canciones'=> $canciones
        );
       // $auth->insertarLog('devuelve canciones'); 
        Response::result(200, $response);
        break;
    
    case 'POST':
       // $auth->insertaLog("Recibe petición de creacion de cancion");

        /**
         * Recibimos el json con los datos a insertar, pero necesitamos
         * ogligatoriamente el id del usuario. Si no está, habrá un error.
         * El id del usuario verificado, deberá ser igual al id_usuario que
         * es la clave secundaria.
         * PUEDO SACAR TAMBIÉN LA id DEL USUARIO A PARTIR DE LA KEY.
         * ESTO LO HARÉ EN OTRA MODIFICACIÓN.
         */
        $params = json_decode(file_get_contents('php://input'), true);
     
       /*if (!isset($params) || !isset($params["id_usuario"]) || empty($params["id_usuario"])  || 
             !($auth->igualesIdUser($params["id_usuario"]))
            ){
                        $response = array(
                            'result' => 'error',
                            'details' => 'Error en la solicitud. Debes autenticarte o faltan parametros.'
                        );
            
                        Response::result(400, $response);
                        exit;
            }
        */
            //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
        if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id pasado por body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }




        $insert_id_cancion = $cancion->insert($params);
        //Debo hacer una consulta, para devolver tambien el nombre de la imagen.
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
        /*
        Es totalmente necesario tener los parámetros del id del cancion a modificar
        y también el id del usuario, aunque esto lo puedo sacar del token.
        */
        $params = json_decode(file_get_contents('php://input'), true);
       /* if (!isset($params) ||  !isset($_GET['id']) || empty($_GET['id']) || !isset($params['id_usuario']) || empty($params['id_usuario'])){
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud de actualización del cancion'
            );

            Response::result(400, $response);
            exit;
        }
        */

        if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])  ){
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud de actualización del cancion. No has pasado el id del cancion'
            );

            Response::result(400, $response);
            exit;
        }

         //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
         if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id del body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
                exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        $cancion->update($_GET['id'], $params);  //actualizo ese cancion.
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
        /*
        El id, también lo puedo sacar del token. Lo modificaré mas adelante.
        */
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

?>