<?php
require_once("./pdo.php");

if (isset($_POST["submit"])){
    if(strlen($_POST["name"]) > 0 && strlen($_POST["password"]) > 0 && strlen($_POST["password_retype"]) > 0){
        $name = htmlspecialchars($_POST["name"]);
        $password = htmlspecialchars($_POST["password"]);
        $password_retype = htmlspecialchars($_POST["password_retype"]);

        // on vérifie s'il existe deja dans la base de données
        $check = $pdo->prepare('SELECT name, password FROM users where name = ?');
        $check->execute(array($name));
        // on stocke les données dans data
        $data = $check->fetch();
        $row = $check->rowCount();
        
        if($row == 0){
            if($password == $password_retype){
                $password = hash('sha256', $password);
        
                // on fait une requête pour ajouter le nouvel utilisateur dans la bdd qu'on stocke dans une variable(facultatif)
                $sql = 'INSERT INTO users (name, password) VALUES (:name, :password)';
                $insert = $pdo->prepare($sql);
        
                $insert->execute([
                    'name' => $name,
                    'password' => $password
                ]);
        
                header("Location: register.php?reg_err=success");
            }else header("Location: register.php?reg_err=password");
        }else header("Location: register.php?reg_err=already");
    }else header("Location: register.php?reg_err=name");

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'enregistrer</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="./normalize.css">
</head>
<body>
    <div class="title">
        <h2>enregistrez-vous</h2>
        <div class="title-underline"></div>
    </div>

    <form method="POST" class="form">
        <?php
            if(isset($_GET['reg_err'])){
                $err = htmlspecialchars($_GET['reg_err']);

                switch($err){

                    case 'success' :
                        ?>
                        <div class="alert alert-success">
                            <strong>Succès</strong> inscription réussie
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;

                    case 'password' :
                        ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> mot de passe différent
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;
                        
                        case 'already' :
                            ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> compte déjà existant
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;

                        case 'name' :
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> veuillez remplir le(s) champ(s) vide(s)
                            </div>
                            <?php
                            exit; // On arrete le script
                            break;
                }
            }
            ?>

        <div class="form-row">
            <label for="name" class="form-label">nom d'utilisateur : </label>
            <input type="text" name="name" class="form-input">
        </div>
        <div class="form-row">
            <label for="password" class="form-label">mot de passe : </label>
            <input type="password" name="password" class="form-input">
        </div>
        <div class="form-row">
            <label for="password-retype" class="form-label">confirmer le mot de passe : </label>
            <input type="password" name="password_retype"  class="form-input">
        </div>
        <button type="submit" name="submit" class="btn btn-block">s'enregistrer</button>
        <a href="./index.php">Annuler</a>
    </form>
</body>
</html>