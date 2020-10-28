# Blog Challenge

Réaliser un système de blog simplissime en une journée !

## Objectif

- Réaliser une page d'administration des articles
    - Ajout/Modification/Suppression d'un article
    - Un article contient les informations suivantes :
        - Title (varchar(255))
        - Content (text)
        - Status (enum publish/draft)
        - Created (date)
        - Updated (date)
- Page d'accueil avec la liste des articles publiés contenant le titre, l'extrait du contenu et un lien vers la page de l'article
- Page article contenant le titre, le contenu, la date de publication et de modification

Il est impératif de respecter le schéma d'adresse suivant :
```
Admin: /admin{/create|update|delete}{/post_id}
Home: /
Article: /article/{post_id}
```

## Contraintes

- PHP >= 7.4
- MySQL/MariaDB ou SQLite
- Aucun framework/vendor
- Aucun .htaccess ou rewrite via le serveur web

> Utilisez le [serveur web interne de PHP](https://www.php.net/manual/fr/features.commandline.webserver.php) avec la commande suivante : `php -S localhost:8000` 

> L'aboutissement du projet n'est pas l'objectif principal, seule la qualité du code, le respect des normes et la maîtrise des différents paradigmes en vigueur dans l'industrie sera apprécié.

## Bonus

- Faire pointer le DocumentRoot dans le répertoire `/public` du projet
- Utiliser un Makefile et des fixtures afin de déployer le projet
- Réaliser la partie front responsive avec [Bootstrap](https://getbootstrap.com/)
- Afficher les 4 premiers articles dans un carousel sur la page d'accueil
- Utilisation de [WebPack](https://webpack.js.org/)