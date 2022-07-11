<?php
session_start();
require_once("./pdo.php");

if (!isset($_SESSION["user_id"])){
    die("ACCES REFUSÉ");
  }

if(isset($_POST['add'])){
    if(strlen($_POST['task']) > 0){
        $task_id = $_SESSION['task_id'];
        $task = htmlspecialchars($_POST['task']);
        $user_id = $_SESSION['user_id'];

        // on vérifie si la tâche existe déjà dans la base de données
        $check = $pdo->prepare('SELECT title, user_id FROM tasks where title = ? and user_id = ?');
        $check->execute(array($task, $user_id));
        // on stocke les données dans data en reliant les 2 tableaux
        $data = $check->fetchAll(PDO::FETCH_ASSOC);
        var_dump($data);
        $row = $check->rowCount();

        if($row == 0){
            $sql = 'INSERT INTO tasks (task_id, title, user_id) VALUES (:task_id, :title, :user_id)';
            $insert = $pdo->prepare($sql);

            $insert->execute([
                    ':task_id' => $task_id,
                    ':title' => $task,
                    ':user_id' => $user_id
                ]);

            header('Location: app.php');
        }else header('Location: app.php?app_err=tasks');
    }else header('Location: app.php?app_err=title');
}

if (isset($_POST['delete'])) {
    echo "delete ";
    $task_id = $_POST["task_id"];
    $deleteQuery = "DELETE FROM tasks WHERE task_id = :task_id";
    $query = $pdo->prepare($deleteQuery);
    $query->execute([
        ":task_id" => $task_id
    ]);
    $_SESSION["success"] = "Tache supprimée avec succes";
    header("Location: app.php");
    return;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="./normalize.css">
</head>
<body>
    <div class="form">
        <?php
            if(isset($_GET['app_err'])){
                $err = htmlspecialchars($_GET['app_err']);

                switch($err){

                    case 'tasks' :
                        ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> Vous possédez déjà cette tâche
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;

                    case 'title' :
                        ?>
                        <div class="alert alert-danger">
                            <strong>Erreur</strong> Veuillez remplir le champ
                        </div>
                        <?php
                        exit; // On arrete le script
                        break;
                }
            }
        ?>
        <form method="POST">
            <div class="alert alert-success">Utilisateur connecté</div>
            <h4>Tâches à faire de azerty</h4>
            <div class="taches_input">
                <input type="text" name="task" id="inserer">
                <input type="submit" name="add" value="ajouter" class="btn">
            </div>
        </form><br>
        <div>
            <table class="taches">
                <?php
                // requete pour récupérer les taches dans la bdd
                $result = $pdo->query('SELECT * FROM tasks');
                while ($task = $result->fetch()){
                    ?>
                    <tr>
                        <td><?php echo $task['user_id'] ?></td>
                        <td><?php echo $task['title'] ?></td>
                        <td><a class="btn" href="edit.php?task_id=<?= $task['task_id']?>">éditer</a></td>
                        <td><form method="POST"><input type="hidden" name="task_id" value="<?php echo $task['task_id']?>"><button type="submit" name="delete" class="btn">supprimer</button></form></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <a href="./logout.php">Se déconnecter</a>
    </div>
</body>
</html>