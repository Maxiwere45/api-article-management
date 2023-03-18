<?php
// Path: controller\server-api.php

// Identification du type de méthode HTTP envoyée par le client
use model\dao\requests\UserRequest;

$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method) {
    // Cas de la méthode GET
    case "GET":
        $bearer_token = get_bearer_token();
        if (is_jwt_valid($bearer_token)) {
            // Récupération des critères de recherche envoyés par le Client
            $matchingData = null;
            if (!empty($_GET['username'])) {
                // TODO
            }
            // Envoi de la réponse au Client
            deliverResponse(200, "Données récupérés avec succès", $matchingData);
        } else {
            deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette ressource", null);
        }
        break;
    /*
// Cas de la méthode POST
case "POST":
    $bearer_token = get_bearer_token();
    if (is_jwt_valid($bearer_token)) {
        // Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        // Traitement
        $data = json_decode($postedData, true);
        $data['date_ajout'] = date('Y-m-d H:i:s');
        $res = $request->insertData($linkpdo, $data);
        // Envoi de la réponse au Client
        deliverResponse(201, "Votre message", $data);
    } else {
        deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette ressource", null);
    }
    break;
// Cas de la méthode PUT
case "PUT" :
    if (!empty($_GET['id'])) {
        // Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        // Traitement
        $data = json_decode($postedData, true);
        $data['date_modif'] = date('Y-m-d H:i:s');
        $data['id'] = $_GET['id'];
        $res = $request->updateElement($linkpdo, $data);
        // Envoi de la réponse au Client
        deliverResponse(200, "Mise  jour OK !", $data);
    }
    break;
// Cas de la méthode DELETE
case 'DELETE':
    // Récupération de l'identifiant de la ressource envoyé par le Client
    if (!empty($_GET['id'])) {
        // Traitement
        $res = $request->deleteElement($linkpdo, $_GET['id']);
        // Envoi de la réponse au Client
        deliverResponse(200, "Votre message", "SUCCES");
    } else {
        deliverResponse(405, "Votre message", "ERROR");
    }
    break;
*/
    default:
        // Récupération de l'identifiant de la ressource envoyé par le Client
        if (!empty($_GET['id'])) {
            // Traitement
        }
        // Envoi de la réponse au Client
        deliverResponse(200, "Votre message", null);
        break;
}
// Envoi de la réponse au Client

function deliverResponse($status, $statusMessage, $data): void
{
    // Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $statusMessage");

    // Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $statusMessage;
    $response['data'] = $data;

    // Mapping de la réponse au format JSON
    $jsonResponse = json_encode($response);
    echo $jsonResponse;
}
/*
echo '<pre>';
print_r($request->getElement($linkpdo, 'chuckn_facts',5));
echo '</pre>';
*/



