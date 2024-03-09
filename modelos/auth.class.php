<?php
require_once '../jwt/JWT.php';
require_once '../modelos-datos/authModel.php';
require_once '../respuestas/response.php';
use Firebase\JWT\JWT;

class Authentication extends AuthModel
{
	private $table = 'usuarios';
	private $key = 'clave_secreta_muy_discreta';
	private $idUser ='';

	public function signIn($user)
	{
		if(!isset($user['email']) || !isset($user['password']) || empty($user['email']) || empty($user['password'])){
			$response = array(
				'result' => 'error',
				'details' => 'Los campos password y email son obligatorios'
			);
			
			Response::result(400, $response);
			exit;
		}

		$result = parent::login($user['email'], hash('sha256' , $user['password']));
		$this->idUser = $result[0]['id'];

		if(sizeof($result) == 0){
			$response = array(
				'result' => 'error',
				'details' => 'El email y/o la contraseña son incorrectas'
			);

			Response::result(403, $response);
			exit;
		}

		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $result[0]['id'],
				'email' => $result[0]['email']
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($result[0]['id'], $jwt);

		return $jwt;
	}

	public function getIdUser(){
		return $this->idUser;
	}

	public function verify()
    {
        if(!isset($_SERVER['HTTP_API_KEY'])){ 
			echo "No existe HTTP_API_KEY";
            $response = array(
                'result' => 'error',
                'details' => 'Usted no tiene los permisos para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;
        }
		
        $jwt = $_SERVER['HTTP_API_KEY'];

        try {
            $data = JWT::decode($jwt, $this->key, array('HS256'));
			$user = parent::getById($data->data->id);
			$this->idUser = $data->data->id;

			if($user[0]['token'] != $jwt){
				throw new Exception();
			}
			
            return $data;
        } catch (\Throwable $th) {
            $response = array(
                'result' => 'error',
                'details' => 'No tiene los permisos para esta solicitud'
            );
			
            Response::result(403, $response);
            exit;
        }
    }

	public function getUser($id){
		$result = parent::devUserModel($id);
		return $result[0];
	}

	public function modifyToken($id, $email){
		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $id,
				'email' => $email
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($id, $jwt);

		return $jwt;
	}

	public  function igualesIdUser($id){
		return $id==$this->idUser;
	}

	public function insertarLog($milog){
		parent::insertarLog($milog);
	}
}
