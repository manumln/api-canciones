<?php
require_once '../respuestas/response.php';
require_once '../modelos/user.class.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $params = json_decode(file_get_contents('php://input'), true);

	if(!isset($params)){
		$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud de creación usuario'
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

}
else{  
    $response = array(
        'result' => 'error'
    );

    Response::result(404, $response);

}
?>