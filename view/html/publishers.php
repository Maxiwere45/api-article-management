<?php
require_once __DIR__ . "/../../model/dao/requests/ArticleRequest.php";
require_once __DIR__ . "/../../model/dao/requests/UserRequest.php";
require_once __DIR__ . "/../../model/User.php";

use model\User;
use model\dao\requests\ArticleRequest;
use model\dao\requests\UserRequest;

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}
$articleRequest = new ArticleRequest();
$userRequest = new UserRequest();
if ($_SESSION['login'] == 'anonyme'){
    $user = new User('anonyme', 'anonyme', 'anonyme');
} else {
    $user = $userRequest->getUser($_SESSION['login']);
}

// Check if user is publisher
if (!$user->isPublisher()){
    header('Location: index.php');
    exit();
}

$articles = $articleRequest->getAllArticles();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tableau de bord</title>
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
                <li><hr class="dropdown-divider" /></li>
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
                    <div class="sb-sidenav-menu-heading">Core</div>
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
                    <div class="sb-sidenav-menu-heading">Master</div>
                    <a class="nav-link" href="adminpanel.php" >
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
                        Admin panel
                    </a>

                    <!--
                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link" href="charts.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Charts
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Tables
                    </a>
                    -->
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as :</div>
                <?php echo strtoupper($_SESSION['role']) ." ". $_SESSION['login']; ?>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Tableau de bord</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">DATA de l'API</li>
                </ol>
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
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>contenu</th>
                                <th>Date date de publication</th>
                                <th>Auteur</th>
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
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>
</html>