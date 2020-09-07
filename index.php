<?php

$server_key = "SB-Mid-server-ONb9SmaPHKbT3nFF6VJtEnql";

$is_production = false;
$is_production = true;
$api_url = $is_production? 
		'https://app.midtrans.com/snap/v1/transactions' : 
		'https://app.sanbox.midtrans.com/snap/v1/transactions';

if (!strpos($_SERVER['REQUEST_URI'], '/charge')) {
	http_response_code(404);
	echo "Wrong path, make sure it's '/charge'";
	exit;

}

if ($_SERVER[REQUEST_METHOD] !== 'POST') {
	http_response_code(404);
	echo"Page not found or wrong HTTP request method is used"; 
	exit();
}

$request_body = file_get_contents('php://input');
header('Content-Type : application/json');

$charge_result = chargAPI($api_url, $server_key, $request_body);


http_response_code($charge_result['http_code']);

echo $charge_result ['body']


function chargerAPI($api_url, $server_key, $request_body){
	$ch = curl_init();
	$curl_option = array(
		CURLOPT_URL => $api_url,
		CURLOPT_RETURNTRANSFER => 1, 
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		// tambahkan header ke permintaan, termasuk otorisasi yang dihasilkan dari kunci server
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: basic' .base64_encode($server_key. '.')
		),
		CURLOPT_POSTFIELDS => $request_body
	);
	curl_setopt_array($ch, $url_options);
	$result = array(
		'body' => curl_exec($ch),
		'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
	);
	return $result;
}