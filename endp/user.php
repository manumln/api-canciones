<?php
require_once '../respuestas/response.php';
require_once '../modelos/user.class.php';
require_once '../modelos/auth.class.php';

/**
 * Endpoint para la gestión de datos de usuarios.
 * 
 * GET (para obtener todos los usuarios)
 * - token (para la autenticación y obtención del ID de usuario)
 * 
 * POST (para la creación de un usuario)
 * - datos del usuario en el cuerpo de la solicitud (JSON)
 * 
 * PUT (para la actualización de un usuario)
 * - ID del usuario en los parámetros de la URL
 * - nuevos datos del usuario en el cuerpo de la solicitud (JSON)
 * 
 * DELETE (para la eliminación de un usuario)
 * - ID del usuario en los parámetros de la URL
 */

$auth = new Authentication();
$auth->verify();

$user = new User();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $params = $_GET;
        $usuarios = $user->get($params);

        $url_raiz_img = "http://" . $_SERVER['HTTP_HOST'] . "/api-canciones/public/img";
        foreach ($usuarios as &$usuario) {
            if (!empty($usuario['imagen'])) {
                $usuario['imagen'] = $url_raiz_img . "/" . $usuario['imagen'];
            }
        }

        $response = array(
            'result' => 'ok',
            'usuarios' => $usuarios
        );

        Response::result(200, $response);
        break;

    case 'POST':
        $params = json_decode(file_get_contents('php://input'), true);

        if (!isset($params)) {
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud'
            );

            Response::result(400, $response);
            exit;
        }

        $insert_id = $user->insert($params);

        $response = array(
            'result' => 'ok',
            'insert_id' => $insert_id
        );

        Response::result(201, $response);
        break;

    case 'PUT':
        $params = json_decode(file_get_contents('php://input'), true);

        if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])) {
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud de actualización'
            );

            Response::result(400, $response);
            exit;
        }

        $user->update($_GET['id'], $params);
        $auth->modifyToken($_GET['id'], $params["email"]);

        $response = array(
            'result' => 'ok'
        );

        Response::result(200, $response);
        break;

    case 'DELETE':
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $response = array(
                'result' => 'error',
                'details' => 'Error en la solicitud'
            );

            Response::result(400, $response);
            exit;
        }

        $user->delete($_GET['id']);

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
