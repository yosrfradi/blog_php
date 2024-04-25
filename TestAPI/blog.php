<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="blog.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<div class="banner">
    <div class="navbar">
        <ul>
            <li><a href="acceuil.php">Home</a></li>
            <li><a href="inscript.php">Sign In</a></li>
            <li><a href="inscript.php">Sign up</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="row">

            <?php
            // Inclure l'autoloader de Composer
            require 'C:/xampp/htdocs/ds2web/vendor/autoload.php';
            

            use jcobhams\NewsApi\NewsApi;

            // Votre clé d'API News API
            $your_api_key = '6a36bb950cb44552b60a85f45be4e0ed';

            // Initialiser l'objet NewsApi avec votre clé API
            $newsapi = new NewsApi($your_api_key);

            // Paramètres de recherche
            $q = 'a'; // Terme de recherche "palestine"
            $country = 'us'; // Restreindre les résultats aux actualités américaines (vous pouvez modifier cela en fonction de votre emplacement ou de vos préférences)

            // Obtenir les principales actualités sur la Palestine
            $top_headlines = $newsapi->getTopHeadlines($q, null, $country);

            // Afficher les résultats
            if (empty($top_headlines->articles)) {
                echo "No articles found for your search.";
              } else {
                foreach ($top_headlines->articles as $article) {
                    ?>
                    <div class="col-md-4">
                        <div class="card" style="width: 18rem;">
                            <img src="<?php echo $article->urlToImage; ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $article->title; ?></h5>
                                <p class="card-text"><?php echo $article->description; ?></p>
                                <a href="<?php echo $article->url; ?>" class="btn btn-primary">Read more</a>
                            </div>
                        </div>
                    </div>
                <?php
                }}
                ?>

        </div>
    </div>
</div>

</body>
</html>
