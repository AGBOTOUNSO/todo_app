<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "todo_app";

// DSN
$dsn = "mysql:host=$host;dbname=$dbname";

// créer une instance PDO
try{
    $pdo = new PDO($dsn, $user, $password);
} catch (Exception $e){
    die ('Erreur : ' . $e->getMessage());
}
// PDO::ERRMODE_EXCEPTION affiche une fatale error et stoppe le programme
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);