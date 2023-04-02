<?php
require_once (__DIR__ . "/../../model/dao/requests/ArticleRequest.php");
use model\dao\requests\ArticleRequest;

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? die('Erreur : ID non défini');

if (isset($_POST['confirmo'])) {
    $articleRequest = new ArticleRequest();
    $article = $articleRequest->getArticle($id);
    $result = $articleRequest->deleteArticle($article);
    if ($result) {
        header("Location: index.php");
    } else {
        echo "Erreur de suppression";
    }
} elseif (isset($_POST['confirmn'])) {
    header("Location: index.php");
}

?>

<!--Page de confirmation de suppression-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppression</title>
    <link rel="icon" type="image/png" href="onglet_icon.png">
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<div class="container">
    <h1 class="mt-5">Suppression</h1>
    <p class="lead">Êtes-vous sûr de vouloir supprimer cet article ?</p>
    <form action="" class="form-control" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button type="submit" name="confirmo" class="btn btn-danger mr-2">Oui</button>
        <button type="submit" name="confirmn" class="btn btn-secondary">Non</button>
    </form>
</div>
</body>
</html>


