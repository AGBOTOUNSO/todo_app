<?php
session_start();
require_once("./pdo.php");

if (isset($_POST["submit"])){
    $name = htmlspecialchars($_POST["name"]);
    $password = htmlspecialchars($_POST["password"]);

    // vérifier si l'utilisateur est deja dans la bdd avec une requete
    $check = $pdo->prepare('SELECT * FROM users where name = ? ');
    $check->execute(array($name));
    // on stocke les données dans data
    $data = $check->fetch();
    $row = $check->rowCount();

    // si la valeur de rowCount() == 1 ca fait que la personne existe
    if($row == 1){
        // hasher le mdp
        $password = hash('sha256', $password);
        if($data["password"] === $password){
            $_SESSION['users'] = $data["name"];
            $_SESSION['user_id'] = $data["user_id"];
            header("Location: app.php");

        }else header("Location: login.php?login_err=password");
    }else header("Location: login.php?login_err=already");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="./normalize.css">
</head>
<body>
    <div class="title">
        <h2>connectez-vous</h2>
        <div class="title-underline"></div>
    </div>

    <form method="POST" class="form">
            <?php
            if(isset($_GET['login_err'])){
                $err = htmlspecialchars($_GET['login_err']);

                switch($err){

                    case 'password' :
                        ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> mot de passe incorrect
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;

                    case 'already' :
                        ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> compte non existant
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;
                }
            }
            ?>

        <div class="form-row">
            <label for="name" class="form-label">nom d'utilisateur : </label>
            <input type="text" name="name" required="required" class="form-input">
        </div>
        <div class="form-row">
            <label for="age" class="form-label">mot de passe : </label>
            <input type="password" name="password" class="form-input">
        </div>
        <button type="submit" name="submit" class="btn btn-block">se connecter</button>
        <div class="cancel"><a href="./index.php">Annuler</a></div>
    </form>
</body>
</html>