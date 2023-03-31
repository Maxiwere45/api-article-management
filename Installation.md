# Installation

* Si l'URL fournie ne fonctionne pas, c'est qu'il est toujours en maintenance, 
vous pouvez lancer l'API en local en suivant les instructions ci-dessous.

## Prérequis

* WAMP or LAMP stack
* PHP 7.1 ou supérieur
* MySQL 5.7 ou supérieur
* Git

## Installation

* Télecharger le projet
* Dézipper le projet dans le dossier `www` de votre serveur web
* Créer une base de données `api-articles-db` dans votre serveur MySQL
* Importer le fichier `api-articles-db.sql` dans votre base de données se trouvant dans le dossier `model/dao/sql_scripts/data/api-article-db.sql`
* Modifier les paramètres de connexion à la base de données dans le fichier `model/dao/Database.php` pour correspondre à votre configuration
* Lancer le serveur web et accéder à l'API via l'URL `http://localhost/api-article-management/`
  * Accéder à l'API via l'URL `http://localhost/api-article-management/controller/server-api.php`
  * Accéder à l'authentification JWT via l'URL `http://localhost/api-article-management/controller/jwt-auth.php`
  * Accéder à l'application cliente via l'URL `http://localhost/api-article-management/view/html/login.php`

---

J'espère que ça vous aidera. N'hésitez pas à me contacter si vous avez des questions.