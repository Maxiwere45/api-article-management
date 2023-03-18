<?php
namespace controller;

use model\dao\requests\UserRequest;

require_once '../libs/jwt-utils.php';

// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
if ($http_method == 'POST') {
    $data = (array) json_decode(file_get_contents('php://input'), true);
    if (isValidUser($data['username'], $data['password'])) {
        // Traitement
        $username = htmlspecialchars($data['username']);
        $headers = array(
            'typ' => 'JWT',
            'alg' => 'HS256'
        );

        $payload = array(
            'username' => $username,
            'exp' => (time() + 60)
        );
        $jwt = generate_jwt($headers, $payload);
        deliverResponse(200, "Vous êtes connecté en tant que $username avec le role", $jwt);
    } else {
        deliverResponse(401, "Login ou mot de passe incorrect, veuillez reessayer de nouveau !", null);
    }
}

function deliverResponse($status, $statusMessage, $data)
{
    // Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $statusMessage");

    // Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $statusMessage;
    $response['jeton'] = $data;

    // Mapping de la réponse au format JSON
    $jsonResponse = json_encode($response);
    echo $jsonResponse;
}

function isValidUser($username, $password): bool
{
    $userRequest = new UserRequest();
    // Protection contre les injections SQL et XSS
    $user = htmlspecialchars($username);
    $pass = htmlspecialchars($password);
    if ($user == $userRequest->getUser($user)->getLogin()
        && $pass == $userRequest->getUser($user)->getPassword()) {
        return true;
    }
    return false;
}
