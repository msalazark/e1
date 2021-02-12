<?php

class EsanContactFormResponse {

	public static function send (
		$http_status_code,
		$message = '',
		$headers = []
	) {

		http_response_code($http_status_code);

		foreach ($headers as $header) {
			header($header);
		}

		echo $message;

		exit;
	}


	public static function sendJson (
		$http_status_code,
		$data
	) {

		self::send(
			$http_status_code,
			json_encode($data),
			['Content-Type: application/json; charset=UTF-8']
		);
	}

}


class EsanContactFormException extends Exception {

	private $http_status_code = 500;

	public function setHttpStatusCode ($http_status_code) {
		$this->http_status_code = $http_status_code;
	}

	public function getHttpStatusCode () {
		return $this->http_status_code;
	}

	public static function throw ($http_status_code, $message) {
		$error = new self($message);
		$error->setHttpStatusCode($http_status_code);

		throw $error;
	}

}


class EsanContactFormPDO extends PDO {

	private $connection_parameters;

	private $is_connected = false;


	public function __construct (
		string $dsn,
		string $username = '',
		string $passwd = '',
		array  $options = []
	) {

		$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

		$this->connection_parameters = [$dsn, $username, $passwd, $options];
	}


	public function connect () : void {
		if ($this->is_connected) {
			return;
		}

		parent::__construct(...$this->connection_parameters);

		$this->is_connected = true;
	}


	public function execute (
		string $sql,
		array  $bindings = []

	) : PDOStatement {

		$this->connect();

		$sth = $this->prepare($sql);

		self::bindValues($sth, $bindings);

		$sth->execute();

		return $sth;
	}


	public function insert (
		string $table,
		array  $data

	) : int {

		$columns     = [];
		$placehoders = [];
		$bindings    = [];

		foreach ($data as $k => $v) {
			$columns[]     = $k;
			$placehoders[] = '?';
			$bindings[]    = $v;
		}

		$sql = sprintf(
			"INSERT INTO {$table} (%s) VALUES (%s)",
			implode(', ', $columns),
			implode(', ', $placehoders)
		);

		$sth = $this->execute($sql, $bindings);

		return $sth->rowCount();
	}


	public static function bindValues (
		PDOStatement $sth,
		array        $bindings = []

	) : void {

		$question_mark_position = 0;

		foreach ($bindings as $k => $v) {
			$parameter = is_int($k) ? ++$question_mark_position : $k;

			$value = $v;
			$data_type = null;

			if (is_array($v)) {
				$value = $v[0];
				$data_type = $v[1] ?? null;
			}

			if (is_null($data_type)) {
				$data_type = self::inferDataType(gettype($value));
			}

			$sth->bindValue($parameter, $value, $data_type);
		}
	}


	private static function inferDataType (string $type) : int {
		switch ($type) {
			case 'string'  : return PDO::PARAM_STR;
			case 'integer' : return PDO::PARAM_INT;
			case 'boolean' : return PDO::PARAM_BOOL;
			case 'double'  : return PDO::PARAM_STR;
			case 'NULL'    : return PDO::PARAM_NULL;

			default:
				throw new Exception("Invalid PDO data type: {$type}");
		}
	}


	public static function build ($config, $fabrik_conn_id = null) {
		$host = $config->host;
		$port = '3306';
		$user = $config->user;
		$pass = $config->password;
		$db   = $config->db;

		if (is_int($fabrik_conn_id)) {
			$db     = new self($config);
			$st     = $db->query("SELECT host,user,password,database FROM {$config->dbprefix}fabrik_connections WHERE id = {$fabrik_conn_id}");
			$config = $st->fetch(PDO::FETCH_OBJ);

			$host = $config->host;
			$user = $config->user;
			$pass = $config->password;
			$db   = $config->database;

			if (strpos($host, ':') !== false) {
				list ($host, $port) = explode(':', $host, 2);
			}
		}

		$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

		return new self($dsn, $user, $pass);
	}

}


class EsanContactForm {

	private $fields = [
		'nombre'                   => '',
		'apellido_paterno'         => '',
		'apellido_materno'         => '',
		'email'                    => '',
		'telefono'                 => '',
		'celular'                  => '',
		'documento_tipo'           => '',
		'documento_numero'         => '',
		'grado_academico'          => '',
		'acepto_tratamiento_datos' => '0',
		'como_nos_conocio'         => '',
		'empresa'                  => '',
		'cargo'                    => '',
		'es_exalumno'              => '0',
		'mensaje'                  => '',
		'extra'                    => '',
		'ciudad'                   => '',
		'modalidad'                => '',
		'sede'                     => '',
		'curso'                    => '',
		'curso_id'                 => '',
		'seccion_id'               => '',
		'convocatoria_codigo'      => '',
		'costo'                    => '',
		'moneda'                   => '',
		'formulario_tipo'          => '',
		'formulario_origen'        => '',
		'url_origen'               => '',
		'utm_source'               => '',
		'utm_medium'               => '',
		'utm_campaign'             => '',
		'utm_term'                 => '',
		'utm_content'              => '',
	];

	private $auth = 'YgGDyQCMMTncBmhnVkHd';

	private $pdo = null;


	public function __construct ($pdo) {
		$this->pdo = $pdo;
	}


	public function proccess () {
		$this->validate();
		$this->save();
	}


	private function validate () {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			EsanContactFormException::throw(405, 'Invalid request method');
		}

		$this->validateAuth();
		$this->validateFields();
	}


	private function validateAuth () {
		$headers = getallheaders();
		$auth = null;

		if (isset($headers['Authorization'])) {
			$auth = $headers['Authorization'];
		}

		if (isset($headers['authorization'])) {
			$auth = $headers['authorization'];
		}

		if ($auth !== $this->auth) {
			EsanContactFormException::throw(401, 'Invalid credentials');
		}
	}


	private function validateFields () {
		$has_values = false;

		foreach ($_POST as $k => $v) {
			if (isset($this->fields[$k])) {
				$this->fields[$k] = $v;

				if ($v !== '' && $v !== '0') {
					$has_values = true;
				}

			} else {
				EsanContactFormException::throw(400, "Missing field: {$k}");
			}
		}

		if (!$has_values) {
			EsanContactFormException::throw(400, 'Empty body');
		}
	}


	// -------------------------------------------------------------------------

	public function save () {
		$this->pdo->insert('formulario_contacto', $this->prepareData());
	}


	public function prepareData () : array {
		$data = $this->fields;

		$now = new DateTimeImmutable('now', new DateTimeZone('America/Lima'));

		$data['ip_origen']      = $_SERVER['REMOTE_ADDR'];
		$data['fecha_creacion'] = $now->format('Y-m-d H:i:s');

		if (empty($data['url_origen']) && isset($_SERVER['HTTP_REFERER'])) {
			$data['url_origen'] = $_SERVER['HTTP_REFERER'] ?: '';
		}

		return $data;
	}

}
