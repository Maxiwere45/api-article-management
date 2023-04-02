<?php
require_once __DIR__ . "/../../model/dao/requests/ArticleRequest.php";
require_once __DIR__ . "/../../model/dao/requests/UserRequest.php";
require_once __DIR__ . "/../../model/User.php";
require_once __DIR__ . "/../../model/Article.php";
require_once __DIR__ . "/../../model/dao/requests/MOPRequest.php";

use model\dao\requests\MOPRequest;
use model\dao\requests\ArticleRequest;
use model\dao\requests\UserRequest;

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

$session_duration = time() - $_SESSION['start_time'];
if ($session_duration > 3600) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['login'] == 'anonyme') {
    header('Location: index.php');
    exit();
} else {
    $userRequest = new UserRequest();
    $user = $userRequest->getUser($_SESSION['login']);
    if (!$user->isModerator() && !$user->isMaster()) {
        header('Location: index.php');
        exit();
    }
}

$articleRequest = new ArticleRequest();
$articles = $articleRequest->getAllArticles();
$moderatorRequest = new MOPRequest();
$article = null;
$display = "none";

// RECHERCHE
if (isset($_GET['btnSubmit']) && strlen($_GET['searchID']) > 0) {
    $search = $_GET['searchID'];
    $article = $articleRequest->getArticle($search);
    $display = "block";
    $cookieValue = serialize(array("idArticle" => $search, "displayStat" => $display));
    setcookie("search", $cookieValue, time() + 3600, "/");
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
    <title>Moderateur panel</title>
    <link rel="icon" type="image/png" href="onglet_icon.png">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">Accueil</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="login.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">PRINCIPALE</div>
                    <a class="nav-link" href="index.php" >
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-fire"></i></div>
                        Tableau de bord
                    </a>
                    <a class="nav-link" href="publishers.php" >
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-pen-to-square"></i></i></div>
                        Publishers
                    </a>
                    <a class="nav-link" href="moderators.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-shield"></i></div>
                        Moderators
                    </a>
                    <div class="sb-sidenav-menu-heading">GESTION</div>
                    <a class="nav-link" href="adminpanel.php" >
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
                        Admin Panel
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Connecté en tant que :</div>
                <?php echo strtoupper($_SESSION['role']) ." ". $_SESSION['login']; ?>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Moderator panel</h1>
                <div class="separator-breadcrumb border-top"></div>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php" style="text-decoration: none">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Modérateur</li>
                </ol>
                <!--Barre de recherche-->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="input-group">
                                <input type="text" id="searchArticle" class="form-control" placeholder="Rechercher un article" name="searchID">
                                <button class="btn btn-primary" id="searchbtn" type="submit" name="btnSubmit">Rechercher</button>
                            </div>
                        </form>
                        <br style="display: <?php echo $display ?>">
                        <div class="card" id="result_search" style="width: 100%; display: <?php echo $display ?>">
                            <?php
                            if ($article != null) {
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title" id="titleAR">Article</h5>';
                                echo '<h6 class="card-subtitle mb-2 text-muted" id="auteurAR">'.$article->getAuthor().'</h6>';
                                echo '<p class="card-text" id="contentAR">'.$article->getContent().'</p>';
                                echo '<i class="card-text" id="dateAR"> Publié le : '.$article->getDate_add().'</i>';
                                echo '<p class="card-text" id="likes">Likes : '.$moderatorRequest->getNbLikesFromArticle($article).'</p>';
                                echo '<p class="card-text" id="dislikes">Dislikes : '.$moderatorRequest->getNbDislikesFromArticle($article).'</p>';
                                echo '<p class="card-text" id="idAR">Utilisateurs qui ont likés :</p>';
                                echo '<div class="separator-breadcrumb border-top"></div>';
                                foreach ($moderatorRequest->getUsersWhoLiked($article) as $index => $item) {
                                    for ($i = 0; $i < count($item); $i++) {
                                        echo '<code>'.$item['id_username'].'</code>';
                                    }
                                }
                                echo '<div class="separator-breadcrumb border-top"></div>';
                                echo '<p class="card-text" id="idAR">Utilisateurs qui ont dislikés :</p>';
                                echo '<div class="separator-breadcrumb border-top"></div>';
                                foreach ($moderatorRequest->getUsersWhoDisliked($article) as $user) {
                                    echo '<code>'.implode(' ', $user).'</code>';
                                }
                                echo '<div class="separator-breadcrumb border-top"></div>';
                                echo '<br>';
                                echo '<a href="deleteconfirm.php?id='.$article->getId().'" id="link-del" class="link-danger">Supprimer l\'article</a>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Articles
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>contenu</th>
                                <th>Date date de publication</th>
                                <th>Auteur</th>
                                <th>Edition</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>contenu</th>
                                <th>Date date de publication</th>
                                <th>Auteur</th>
                                <th>Edition</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            foreach ($articles as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$value['article_id']."</td>";
                                echo "<td>".$value['content']."</td>";
                                echo "<td>".$value['date_de_publication']."</td>";
                                echo "<td>".$value['author']."</td>";
                                echo "<td><a href='deleteconfirm.php?id=".$value['article_id']."' id='link-del'>supprimer</a></td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Tableau-->

            </div>
        </main>
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple.js"></script>
</body>
</html>
