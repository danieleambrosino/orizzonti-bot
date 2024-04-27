<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Bot\Dao\Factory;
use Bot\Request;
use Bot\Responder;

$input = file_get_contents('php://input');
if (empty($input)) {
	exit;
}

try {
	$request = Request::fromJsonString($input);
} catch (InvalidArgumentException $e) {
	http_response_code(400);
	echo $e->getMessage();

	exit;
}
$dao = Factory::create($_SERVER['DAO']);
$responder = new Responder($dao);
$response = $responder->respond($request);
if (null === $response) {
	exit;
}

header('Content-Type: application/json');
echo json_encode($response->toTelegramResponse(
	$request->chatId,
	$request->messageId,
));
