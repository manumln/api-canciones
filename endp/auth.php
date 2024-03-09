<?php
require_once '../modelos/user.class.php';
require_once '../modelos/auth.class.php';
require_once '../respuestas/response.php';

$auth = new Authentication();  

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$user = json_decode(file_get_contents('php://input'), true);


		$token = $auth->signIn($user);  //ya tenemos el token.

		$id_user = $auth->getIdUser();
		$user = $auth->getUser($id_user);
		$nombre = $user['nombre'];
		$imagen = $user['imagen'];
		$email = $user['email'];
		$url_raiz_img="http://".$_SERVER['HTTP_HOST']."/api-canciones/public/img";

		$imagen = $url_raiz_img."/".$imagen;
		

		$response = array(
			'result' => 'ok',
			'token' => $token,
			'id' => $id_user,
			'nombre' => $nombre,
			'email' => $email,
			'imagen' => $imagen
		);
		Response::result(201, $response);

		break;
}