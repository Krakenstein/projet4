<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="public/css/style.css" />
    <title>Episodes</title>
</head>
<body>
    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
            <a class="btn" href="index.php">Accueil</a>
            <a class="btn" href="index.php?action=listEpisodes">Episodes</a>
        </div>
    </nav>
    <section>
        <h2>Connectez-vous à l'espace administrateur</h2>
        <form id="formConnect" action="index.php?action=admConnect" method="post">
            <input title="nom" class="champ" type="text" name="nom" id="nom" placeholder=" Votre identifiant" size="15"/>
			<label id="labelNom" for="nom"></label>
            <input title="pass" class="champ" type="password" name="password" id="password" placeholder=" Votre mot de passe" size="15"/>
            <label id="labelPass" for="pass"></label>
            <input type="submit" class="bouton" value="Connection">
        </form>
    </section>
    <footer id="foot">
    <a class="btn active" href="index.php?action=connection">Administrateur</a>
    </footer>
</body>
</html>