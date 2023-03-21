<?php
require_once(__DIR__ . "/../libs/jwt-utils.php");
require_once(__DIR__ . "/../model/dao/requests/ArticleRequest.php");
require_once(__DIR__ . "/../model/dao/requests/UserRequest.php");
require_once(__DIR__ . "/../model/dao/requests/MOPRequest.php");
require_once __DIR__ . "/../libs/functions_utils.php";
require_once(__DIR__ . "/../model/Article.php");
// Identification du type de méthode HTTP envoyée par le client
use model\Article;
use model\dao\requests\ArticleRequest;
use model\dao\requests\MOPRequest;
use model\dao\requests\UserRequest;
use model\User;
use function libs\deliverResponse;
use function libs\getJWTUser;

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
        $matchingData = null;
        if (is_jwt_valid($bearer_token)) {
            // Récupération des critères de recherche envoyés par le Client
            try {
                $user = getJWTUser($bearer_token, $userRequest);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            // Si le token n'est pas valide, on considère que l'utilisateur est anonyme
            $user = new User("anonymous", "none", "anonymous");
        }
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $article = $articleRequest->getArticle($id);
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

            if ($user->getRole() == "anonymous") {
                deliverResponse(200, "[Anonymous] L'article a ete recupere avec succes", $matchingData);
            } else {
                deliverResponse(200, "[{$user->getLogin()}] L'article a ete recupere avec succes", $matchingData);
            }
        } elseif (isset($_GET['users'])) {
            if (!$user->isModerator()) {
                die("ERROR 403 : Vous n'avez pas les droits pour acceder a cette ressource !");
            }
            $matchingData = $moderatorRequest->getNbUsers();
            deliverResponse(200, "All users", $matchingData);
        } elseif (isset($_GET['my-articles'])) {
            if (!$user->isPublisher()) {
                die("ERROR 403 : Vous n'avez pas les droits pour acceder a cette ressource !");
            }
            $matchingData = $articleRequest->getMyOwnArticles($user);
            deliverResponse(200, "My articles", $matchingData);
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

            // Si l'utilisateur est un éditeur
            if ($user->isPublisher()) {
                $data['auteur'] = $user->getLogin();
                $data['date_de_publication'] = date('Y-m-d H:i:s');
                $article = new Article($data['id'], $data['contenu'], $data['date_de_publication'], $data['auteur']);
                $res = $articleRequest->insertArticle($article);
                // Envoi de la réponse au Client
                deliverResponse(201, "L'article a bien ete ajoutee", $data);
            // Si l'utilisateur est un modérateur
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
                            deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
                        }
                    }
                    // Envoi de la réponse au Client
                    deliverResponse(201, "L'utilisateur a bien été ajouté", $data);
                } else {
                    deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
                }
            } else {
                deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
            }
        } else {
            deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette ressource", null);
        }
        break;
    // Cas de la méthode PUT
    case "PUT" :
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
                $res = $articleRequest->updateArticle($data, $user);
                // Envoi de la réponse au Client
                deliverResponse(200, "L'article a bien ete modifie", $data);
            } else {
                deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette fonction", null);
            }
        } else {
            deliverResponse(403, "Vous n'êtes pas autorisé à accéder à cette ressource", null);
        }
        break;
        /*
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

/*
echo '<pre>';
print_r($request->getElement($linkpdo, 'chuckn_facts',5));
echo '</pre>';
*/



