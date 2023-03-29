<?php
require_once (__DIR__ . "/../../model/dao/requests/ArticleRequest.php");
use model\dao\requests\ArticleRequest;

$id = $_POST['id'] ?? null;

if (isset($_POST['confirmo'])) {
    $articleRequest = new ArticleRequest();
    $result = $articleRequest->deleteArticle($id);
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
</head>

<body>
    <h1>Suppression</h1>
    <p>Êtes-vous sûr de vouloir supprimer cet élément ?</p>
    <form action="" class="form-control" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="confirmo" value="Oui">
        <input type="submit" name="confirmn" value="Non">
    </form>


