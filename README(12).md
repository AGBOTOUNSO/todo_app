# Todo App

Dans ce projet nous allons construire une application web pour suivre les données sur les tâches à faire. Nous n'autoriserons que les utilisateurs connectés à gérer leur liste.

## Spécifications Générales

- Toutes les données provenant de l'utilisateur doivent être proprement échappées en utilisant la fonction PHP htmlentities(). Vous n'avez pas besoin d'échapper le texte généré par votre programme

- Vous devez suivre le motif POST-Redirect-GET pour toutes les requêtes POST.

- N'utilisez pas la validation HTML5 dans le navigateur (ex: type="number") pour les champs dans ce projet car on veut être sûr que vous avez proprement fait la validation côté serveur. En général, même si vous faîtes la validation de donnéescôté client, vous devez tout de même valider les données côté serveur dans le cas où l'utilisateur utilise un navigateur non-HTML5.

## Création de la Base de Données

```
CREATE DATABASE todo_app;
USE todo_app;
```

## Création de la Table User

```
CREATE TABLE users (
user_id INTEGER NOT NULL KEY AUTO_INCREMENT,
name VARCHAR(255),
password VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Création de la Table Tasks

```
CREATE TABLE tasks (
task_id INTEGER NOT NULL KEY AUTO_INCREMENT,
title VARCHAR(255),
user_id INTEGER,
CONSTRAINT FOREIGN KEY(user_id) REFERENCES users(user_id)
ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Protection de la page app.php et edit.php

Pour empêcher la base de données d'être modifiée sans que l'utilisateur ne soit connecté, les pages app.php et edit.php doivent d'abord vérifier la session pour voir si l'utilisateur est défini et, si son nom n'est pas défini dans la session, s'arrêter immédiatement en utilisant la fonction PHP die() :

```
die("ACCES REFUSÉ");
```

Pour tester, naviguer vers la page app.php manuellement sans connecter - cela devrait échouer avec "ACCES REFUSÉ".

## Enregistrement

Si l'utilisateur n'est pas enregistré, il lui sera présenté un écran de bienvenue avec un lien vers la page register.php.

L'écran d'enregistrement doit avoir des vérifications d'erreurs sur ses données. Si le nom ou le mot de passe est vide, vous devez afficher un message de la forme :

```
Tous les champs sont requis
```

Si les mots de passe ne correspondent pas, vous devez afficher un message de la forme :

```
Les mots de passe ne correspondent pas
```

## Connexion

Si l'utilisateur n'est pas connecté, il lui sera présenté un écran de bienvenue avec un lien vers la page login.php - il ne doit pas voir la liste de tâche

L'écran de connexion doit avoir des vérifications d'erreurs sur ses données. Si le nom ou le mot de passe est vide, vous devez afficher un message de la forme :

```
Le Nom d'utilisateur et le mot de passe sont requis
```

Si le mot de passe est non-vide et incorrect, devez afficher unmessage de la forme :

```
Mot de passe incorrect
```

## Todo List

Une fois l'utilisateur connecté, il devrait être redirigé vers la page app.php où il verra sa liste de tâches de la base de données.

Il pourra administrer sa liste et se déconnecter.

Si le bouton de déconnexion est appuyé, l'utilisateur serait envoyé vers la page logout.php qui videra les variables de la session et le redirigera vers index.php.

## Ajout de Tâches

Lors du traîtement d'un POST entrant, les données doivent être validées. Tous les champs sont requis, si il en manque (ex: vide), émettez un message :

```
Tous les champs sont requis
```

S'il y a une erreur, n'ajoutez pas la tâche dans la base de données. Redirigez l'utilisateur vers app.php et afficher le message "flash" d'erreur :

```
if (... un champ est vide ...) {
$_SESSION['error'] = "Tous les champs sont requis";
header("Location: app.php");
return;
}
```

```
if (isset($_SESSION['error'] ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
```

Si les données sont validées et ajoutées, redirigez vers app.php avec un message flashde succès.

```
Tâche ajoutée
```

## Édition des Tâches et Validation d'Erreurs

Lors d'une édition de tâche, la donnée doit être montrée et proprement échappée. Toute la validation de données doit être effectuée comme lors de l'ajout. Assurez-vous d'inclure le paramètre "id" dans la redirection dans la page edit.php quand une erreur est détectée :

```
if ( ... un champ est manquant ... ) {
  $_SESSION['error'] = "Tous les champs sont requis";
  header("Location: edit.php?todos_id=".$_REQUEST['id']);
  return;
}
```

Si la validation de données et l'édition sont un succès, redirigez vers app.php avec un message flash de succès :

```
Tâche modifiée
```

## Suppression des tâches

Lorsque l'utilisateur sélectionne le bouton "Supprimer" dans la liste de tâches, vous devez afficher un formulaire de confirmer avc les options "Supprimer" et "Annuler";

Si le bouton "Supprimer" est pressé, la tâche est supprimées et l'utilisateur est redirigé vers app.php avec un message de succès :

```
Tâche supprimée
```
