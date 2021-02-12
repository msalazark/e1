<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(400);
	die('Bad request params');
}

try {
	$req = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
} catch (Exception $error) {
	http_response_code(400);
	die('Bad request: invalid json data');
}

$request = [
	'method'  => null,
	'url'     => null,
	'headers' => null,
	'body'    => null,
];

foreach ($request as $k => $v) {
	if (isset($req[$k])) {
		$request[$k] = $req[$k];
	} else {
		http_response_code(400);
		die('Bad request: missing params');
	}
}


// -----------------------------------------------------------------------------

$ch = curl_init();

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request['method']);
curl_setopt($ch, CURLOPT_URL, $request['url']);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request['headers']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request['body']);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response_body = curl_exec($ch);

$curl_errno = curl_errno($ch);
$curl_info  = curl_getinfo($ch);

curl_close($ch);

if ($curl_errno) {
	http_response_code(500);
	die("curl error: {$curl_errno}");
}


// -----------------------------------------------------------------------------

http_response_code($curl_info['http_code']);
header('Content-Type: ' . $curl_info['content_type']);
echo $response_body;
