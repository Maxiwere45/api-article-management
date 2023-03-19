<?php
require_once(__DIR__ . "/../libs/jwt-utils.php");
require_once(__DIR__ . "/../model/dao/requests/ArticleRequest.php");
require_once(__DIR__ . "/../model/dao/requests/UserRequest.php");
require_once(__DIR__ . "/../model/dao/requests/MOPRequest.php");
require_once(__DIR__ . "/../model/Article.php");
// Identification du type de méthode HTTP envoyée par le client
use model\Article;
use model\dao\requests\ArticleRequest;
use model\dao\requests\MOPRequest;
use model\dao\requests\UserRequest;
use model\User;

// Initialisation du fichier de log
//ini_set("error_log", "../../logs/journal.log");

$http_method = $_SERVER['REQUEST_METHOD'];
$articleRequest = new ArticleRequest();
$userRequest = new UserRequest();
$moderatorRequest = new MOPRequest();

switch ($http_method) {
    // Cas de la méthode GET
    case "GET":
        $bearer_token = get_bearer_token();
        if (is_jwt_valid($bearer_token)) {
            // Récupération des critères de recherche envoyés par le Client
            $matchingData = null;
            try {
                $user = getJWTUser($bearer_token, $userRequest);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            $user = new User("anonymous", "none", "anonymous");
        }
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $article = $articleRequest->getArticle($id, $user);
            $matchingData = $article->toArray();
            // Si l'utilisateur est un modérateur, on lui envoie les données supplémentaires
            if ($user->isModerator()) {
                $matchingData['likes_count'] = $moderatorRequest->getNbLikesFromArticle($article);
                $matchingData['users_who_liked'] = $moderatorRequest->getUsersWhoLiked($article);
                $matchingData['dislikes_count'] = $moderatorRequest->getNbDislikesFromArticle($article);
                $matchingData['users_who_disliked'] = $moderatorRequest->getUsersWhoDisliked($article);
            } elseif ($user->isPublisher()) {
                $matchingData['likes_count'] = $moderatorRequest->getNbLikesFromArticle($article);
                $matchingData['dislikes_count'] = $moderatorRequest->getNbDislikesFromArticle($article);
            }
            deliverResponse(200, "L'article a ete recupere avec succes", $matchingData);
        } elseif (isset($_GET['users'])) {
            if (!$user->isModerator()) {
                die("ERROR 403 : Vous n'avez pas les droits pour acceder a cette ressource !");
            }
            $matchingData = $moderatorRequest->getNbUsers();
            deliverResponse(200, "All users", $matchingData);
        } else {
            $matchingData = $articleRequest->getAllArticles($user);
            deliverResponse(200, "All articles", $matchingData);
        }
        break;
    // Cas de la méthode POST
    case "POST":
        $bearer_token = get_bearer_token();
        if (is_jwt_valid($bearer_token)) {
            try {
                $user = getJWTUser($bearer_token, $userRequest);
            } catch (Exception $e) {
                die($e->getMessage());
            }
            // Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            // Traitement
            if ($user->isPublisher()) {
                $data['auteur'] = $user->getLogin();
                $data['date_de_publication'] = date('Y-m-d H:i:s');
                $article = new Article($data['id'], $data['contenu'], $data['date_de_publication'], $data['auteur']);
                $res = $articleRequest->insertArticle($article);
                // Envoi de la réponse au Client
                deliverResponse(201, "L'article a bien ete ajoutee", $data);
            } elseif ($user->isModerator()) {
                if (isset($data['insert-user'])) {
                    if ($data['role'] == "moderator" && $user->isMaster()) {
                        $user_to_insert = new User($data['login'], $data['password'], $data['role']);
                        $res = $userRequest->insertUser($user_to_insert);
                    } else {
                        if ($data['role'] == "publisher") {
                            $user_to_insert = new User($data['login'], $data['password'], $data['role']);
                            $res = $userRequest->insertUser($user_to_insert);
                        } else {
                            deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
                        }
                    }

                    // Envoi de la réponse au Client
                    deliverResponse(201, "L'utilisatuer a bien été ajouté", $data);
                } else {
                    deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
                }
            } else {
                deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
            }
        } else {
            deliverResponse(401, "Vous n'êtes pas autorisé à accéder à cette ressource", null);
        }
        break;
        /*
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
            deliverResponse(405, "Votre message", "ERROR");
        }
        // Envoi de la réponse au Client
        deliverResponse(200, "Votre message", null);
        break;
}

/**
 * Cette fonction permet de retourner une réponse au client
 * @param int $status
 * @param string $statusMessage
 * @param $data
 */
function deliverResponse(int $status, string $statusMessage, $data): void
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

/**
 * Cette fonction permet de récupérer le token JWT envoyé par le client et retourne l'utilisateur correspondant
 * @param string $bearer_token
 * @param UserRequest $userRequest
 * @return User|null
 * @throws Exception
 */
function getJWTUser(string $bearer_token, UserRequest $userRequest): ?User
{
    $jwt = str_replace('Bearer ', '', $bearer_token);
    $payload = decode_jwt($jwt);
    return $userRequest->getUser($payload['username']);
}

/*
echo '<pre>';
print_r($request->getElement($linkpdo, 'chuckn_facts',5));
echo '</pre>';
*/



