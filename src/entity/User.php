<?php
	
	namespace MyApp\Entity;

	use Symfony\Component\Validator\Mapping\ClassMetadata;
	use Symfony\Component\Validator\Constraints as Assert;

	class User{
		private $username;
		private $password;

		public function __construct($username="", $password=""){
			$this->username = $username;
			$this->password = $password;
		}

		public function getUserName(){
			return $this->username;
		}

		public function setUserName($username){
			$this->username = $username;
		}

		public function getPassword(){
			return $this->password;
		}

		public function setPassword($password){
			$this->password = $password;
		}

		public static function loadValidatorMetadata(ClassMetadata $metadata){
			$metadata->addPropertyConstraint("username", new Assert\Regex([
				"message" => "El nombre del usuario no es válido",
				"pattern" => "/^[a-zA-Z_0-9]+$/"
 			]));

			//testing non required field
			new Assert\Regex([
				"message" => "La contraseña del usuario no es válida",
				"pattern" => "/^[a-zA-Z_0-9]+$/"
 			]);
		}
	}