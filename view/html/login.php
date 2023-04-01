<?php
require_once(__DIR__ . "/../../model/dao/requests/UserRequest.php");
require_once(__DIR__ . "/../../libs/jwt-utils.php");
use model\dao\requests\UserRequest;

session_start();
if (isset($_GET['login'])) {
    session_destroy();
    exit();
}

# Variables
$username = null;
$password = null;
$userRequest = new UserRequest();
$URL = "http://localhost/api-article-management/controller/jwt-auth.php";

if (isset($_POST['btn-validate'])) {
    $username = $_POST['inputEmail'];
    //$password = hash('sha256', $_POST['inputPassword']);
    if ($username == 'anonyme') {
        $_SESSION['login'] = $username;
        $_SESSION['role'] = 'anonyme';
        $_SESSION['start_time'] = time();
        header("Location: index.php");
        exit();
    } else {
        $user = $userRequest->getUser($username);
        // POST AUTH
        $data = array("username" => $username, "password" => $_POST['inputPassword']);
        $data_string = json_encode($data);
        $result = file_get_contents(
            $URL,
            false,
            stream_context_create(array(
                'http' => array('method' => 'POST',
                    'content' => $data_string,
                    'header' => array('Content-Type: application/json'."\r\n"
                        .'Content-Length: '.strlen($data_string)."\r\n"))))
        );
        $receveid_data = json_decode($result, true);

        if ($receveid_data['status'] == 200) {
            $_SESSION['login'] = $user->getLogin();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['start_time'] = time();
            $_SESSION['token'] = get_bearer_token();
            header("Location: index.php");
            exit();
        } else {
            echo "Erreur de connexion";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Connexion</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" name="inputEmail" type="text" placeholder="name@example.com" required />
                                                <label for="inputEmail">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Password" required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Se souvenir</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" name="btn-validate" class="btn btn-primary">Login</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Article Manager 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
