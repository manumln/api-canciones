<?php
require_once '../respuestas/response.php';
require_once '../modelos-datos/database.php';

class User extends Database
{
	private $table = 'usuarios';

	private $allowedConditions_get = array(
		'id',
		'nombre',
		'disponible',
		'imagen',
		'page'
	);

	private $allowedConditions_insert = array(
		'email',
		'password',
		'nombre',
		'imagen',
		'disponible'
	);

private $allowedConditions_update = array(
	'email',
	'password',
	'nombre',
	'imagen',
	'disponible'
	
);
	private function validateInsert($data){
		
		if(!isset($data['email']) || empty($data['email'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo email es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		if(!isset($data['nombre']) || empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		
		if(isset($data['disponible']) && !($data['disponible'] == "1" || $data['disponible'] == "0")){
			$response = array(
				'result' => 'error',
				'details' => 'El campo disponible debe ser del tipo boolean'
			);

			Response::result(400, $response);
			exit;
		}

		if (!isset($data['password'])  ||  empty($data['password'])) {
			$response = array(
				'result' => 'error',
				'details' => 'El password es obligatoria'
			);

			Response::result(400, $response);
			exit;
		}
		
		
		if (isset($data['imagen']) && !empty($data['imagen'])) {
			$img_array = explode(';base64,', $data['imagen']);
			$extension = strtoupper(explode('/', $img_array[0])[1]);
			if ($extension!='PNG' && $extension!='JPG'  && $extension!='JPEG') {
				$response = array('result'  => 'error', 'details' => 'Formato de la imagen no permitida, sólo PNG/JPE/JPEG');
				Response::result(400, $response);
				exit;
			}

			if(!isset($data['email']) || empty($data['email'])){
				$response = array(
					'result' => 'error',
					'details' => 'El campo email es obligatorio'
				);

				Response::result(400, $response);
				exit;
			}

			if(isset($data['disponible']) && !($data['disponible'] == "1" || $data['disponible'] == "0")){
				$response = array(
					'result' => 'error',
					'details' => 'El campo disponible debe ser del tipo boolean'
				);

				Response::result(400, $response);
				exit;
			}

			if (!isset($data['password'])  ||  empty($data['password'])){
				$response = array(
					'result' => 'error',
					'details' => 'El password es obligatoria'
				);

				Response::result(400, $response);
				exit;
			}

			if (isset($data['imagen']) && !empty($data['imagen'])) {
				$img_array = explode(';base64,', $data['imagen']);
				$extension = strtoupper(explode('/', $img_array[0])[1]);
				if ($extension!='PNG' && $extension!='JPG'  && $extension!='JPEG') {
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

			$usuarios = parent::getDB($this->table, $params);

			return $usuarios;
			}

			public function insert($params)
			{
			foreach ($params as $key => $param) {
				if(!in_array($key, $this->allowedConditions_insert)){
					unset($params[$key]);
					$response = array(
						'result' => 'error',
						'details' => 'Error en la solicitud. Parametro no permitido'
					);

					Response::result(400, $response);
					exit;
				}
			}

			if($this->validateInsert($params)){

				if (isset($params['imagen'])){
					$img_array = explode(';base64,', $params['imagen']);
					$extension = strtoupper(explode('/', $img_array[0])[1]);
					$datos_imagen = $img_array[1];
					$nombre_imagen = uniqid();
					$path = dirname(__DIR__, 1)."/public/img/".$nombre_imagen.".".$extension;
					file_put_contents($path, base64_decode($datos_imagen));
					$params['imagen'] = $nombre_imagen.'.'.$extension;
				}

				$password_encriptada = hash('sha256' , $params['password']);
				$params['password'] = $password_encriptada;

				return parent::insertDB($this->table, $params);
			}


			}

			public function update($id, $params)
			{
			foreach ($params as $key => $parm) {
				if(!in_array($key, $this->allowedConditions_update)){
					unset($params[$key]);
					echo $params[$key];
					$response = array(
						'result' => 'error',
						'details' => 'Error en la solicitud dentro del modelo datos'
					);

					Response::result(400, $response);
					exit;
				}
			}

			if($this->validateUpdate($params)){
				$password_encriptada = hash('sha256' , $params['password']);
				$params['password'] = $password_encriptada;

				if (isset($params['imagen'])){
					$usuarios = parent::getDB($this->table, $_GET);
					$usuario = $usuarios[0];
					$imagen_antigua = $usuario['imagen'];
					$path = dirname(__DIR__, 1)."/public/img/".$imagen_antigua;
					if (!unlink($path)){
						$response = array(
							'result' => 'warning',
							'details' => 'No se ha podido eliminar el fichero antiguo'
						);  
						Response::result(200, $response);
						exit;
						
					}

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

			$usuarios = parent::getDB($this->table, $_GET);
			$usuario = $usuarios[0];
			$imagen_antigua = $usuario['imagen'];
			if(!empty($imagen_antigua)){
				$path = dirname(__DIR__, 1)."/public/img/".$imagen_antigua;
				if (!unlink($path)){
					$response = array(
						'result' => 'warning',
						'details' => 'No se ha podido eliminar la imagen del usuario'
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
