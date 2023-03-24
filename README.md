# ARTICLE MANAGER

## Description
ARTICLE MANAGER une solution pour la gestion d’articles de blogs. Cette solution est orientée ressources, c’est-à-dire sur une API REST.


## Fonctionnalités
Il existe 3 fonctions principales :
* La publication, la consultation, la modification et la suppression des articles de blogs. 
  * Un article
  est caractérisé, a minima, par sa date de publication, son auteur et son contenu.
* L’authentification des utilisateurs souhaitant interagir avec les articles.
  * Cette fonctionnalité s'appuie sur les JSON Web Token (JWT). 
  * Un utilisateur est caractérisé par un nom d’utilisateur, un mot de passe et un rôle (moderator, publisher ou anonyme).
* La possibilité de liker/disliker un article.
    * Un utilisateur peut liker ou disliker un article. 
    * Un utilisateur ne peut liker qu’une seule fois un article. 
    * Un utilisateur ne peut disliker qu’une seule fois un article. 
    * Un utilisateur ne peut pas liker et disliker un article en même temps. 
    * Un utilisateur peut changer son opinion sur un article en likant ou dislikant à nouveau l’article.

# Crédits

<img  style="float:center; margin: 0 10px 0;" alt="" src="./ressources/logo_iut_info.png" width=120>

Ce programme a été réalisé par des étudiants de l'**IUT informatique de Toulouse** dans le cadre d'un mini projet de groupe en programmation PHP. 
Pour en savoir un peu plus, vous pouvez contacter les développeurs du programme
* [**Amdjad Anrifou**](https://github.com/maxiwere45)
* [**Carl Premi**](https://github.com/otsubyo)

* Professeurs de la ressource :
  * Micheau Paul
  * Choquet Mathias
  * Broisin Julien
