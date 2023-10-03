<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Bot\Dao\Factory;
use Bot\Responder;

use function Bot\decodeInput;
use function Bot\getTelegramResponse;

$input = file_get_contents('php://input');
if (empty($input)) {
	exit;
}

try {
	$request = decodeInput($input);
} catch (\InvalidArgumentException $e) {
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
$response = getTelegramResponse(
	$response,
	$request->message->chat->id,
	$request->message->message_id,
);

header('Content-Type: application/json');
echo json_encode($response);
