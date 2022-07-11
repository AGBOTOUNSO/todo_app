<?php
session_start();
require_once("./pdo.php");
$success = $_SESSION["success"] ?? false;
$error = $_SESSION["error"] ?? false;
$task_id = $_GET["task_id"] ?? '';

$sql = "SELECT * FROM tasks WHERE task_id = :task_id";
$query = $pdo->prepare($sql);
$query->execute([
    ":task_id" => $_GET["task_id"]
]);
$result = $query->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['edit'])){
    if(strlen($_POST['task']) > 0){
    $title = $_POST["title"];
    $updateQuery = "UPDATE tasks SET title = :title WHERE task_id = :task_id";
    $query = $pdo->prepare($updateQuery);
    $query->execute([
        ":task_id" => $task_id,
        ":title" => $title
    ]);
    $_SESSION["success"] = "Tache modifiée avec succes";
    header("Location: app.php");
    return;
    }else header('Location: edit.php?edit_err=task' . $task_id);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="./normalize.css">
</head>
<body>
    <?php
  if($success) {
    echo "<div class='alert alert-success'>$success</div>";
    unset($_SESSION["success"]);
  }

  if($error) {
    echo "<div class='alert alert-danger'>$error</div>";
    unset($_SESSION["error"]);
  }
  ?>

    <div class="form">
        <?php
            if(isset($_GET['edit_err'])){
                $err = htmlspecialchars($_GET['edit_err']);

                switch($err){

                    case 'task' :
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
            <h4>éditer une tâche</h4>
            <div class="taches_input">
                <input type="text" name="task" id="inserer" value="<?php echo $result['title'] ?>">
                <input type="submit" name="edit" value="éditer" class="btn">
            </div>
        </form>
        
        <a href="./app.php">Annuler</a>
    </div>
</body>
</html>