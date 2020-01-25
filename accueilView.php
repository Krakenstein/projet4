<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css" />
        <title>accueil</title>
    </head>

    <body>
        <nav class="navigation-bar">
            <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
            <div id="navbar_accueil">
                <a class="btn active" href="accueil.html">Accueil</a>
                <a class="btn" href="episodes.html">Episodes</a>
            </div>
        </nav>
        <header>
            <img id="imgHeader" src="images/header.jpg" alt="old man by mari lezhava"/>
            <h1 id="titre">Billet simple pour l'Alaska</h1>
            <h3 id="slogan">"Un feuilleton numérique et littéraire de Jean Forteroche"</h3>
        </header>
        <section>
            <h2>Dernier épisode mis en ligne</h2>
            <h3>Episode n°1</h3>
            <h2>Titre de l'épisode</h2>
        
            <p id="chapitre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel ligula ac mi tincidunt condimentum. Aliquam id lacus in nulla vehicula pulvinar. Integer venenatis sagittis sollicitudin. Donec sed massa id enim luctus egestas. Fusce pellentesque augue sed augue varius, eget posuere velit cursus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum vulputate, odio ut pretium faucibus, nibh arcu porttitor turpis, id ultricies sem lorem ut enim. Nulla dignissim nulla eget neque vestibulum efficitur. Morbi a odio libero. Mauris metus metus, interdum in placerat vel, vehicula in nisl. In id elementum velit, at tristique orci. Mauris eu sagittis nibh. Sed ullamcorper ex semper justo vulputate, eu varius sapien blandit.
        Aliquam bibendum leo vitae mi porttitor, sed tempus eros vulputate. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Quisque dolor orci, consectetur id leo eget, molestie faucibus metus. Mauris laoreet lacinia cursus. Nam id volutpat nisi. Praesent nec augue fringilla tortor congue ullamcorper eu ut metus. In semper eros non posuere cursus. Morbi iaculis, felis ac euismod aliquet, orci est ultrices magna, vel gravida nisl est sit amet justo. Vestibulum at massa sit amet nisi finibus luctus ut nec eros. Morbi mattis, massa eu pellentesque rhoncus, felis dui fermentum velit, quis pellentesque ligula leo pharetra ligula. Sed fringilla, mi sit amet fermentum pretium, eros urna ultrices arcu, eu feugiat orci tortor et est. Proin eu facilisis tellus, id aliquam tortor.</p>
        </section>
        <section class="comments">
            <h2>Commentaires</h2>
            <div class="comment">
                <span>date</span><span>par <b>pseudo</b></span>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel ligula ac mi tincidunt condimentum.</p>
                <input type="submit" class="bouton" value="signaler">  
            </div>
            <div class="comment">
                <span>date</span><span>par <b>pseudo</b></span>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel ligula ac mi tincidunt condimentum.</p>
                <input type="submit" class="bouton" value="signaler">  
            </div>
            <div class="comment">
                <span>date</span><span>par <b>pseudo</b></span>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel ligula ac mi tincidunt condimentum.</p>
                <input type="submit" class="bouton" value="signaler">  
            </div>
            <form id="makeComment">
                <h2>Laissez moi un commentaire</h2>
                <input title="pseudo" class="champ" type="text" name="pseudo" id="pseudo" placeholder=" Votre pseudo" size="15"/>
                <label id="labelPseudo" for="pseudo"></label>
                <p>Votre commentaire</p>
                <textarea title="comment" name="comment" id="comment" cols="40" rows="5"></textarea>
                <label id="labelComment" for="comment"></label>
                <input type="submit" class="bouton" value="envoyer">
            </form>
        </section>
        <footer id="foot">
            <a class="btn" href="connection.html">Administrateur</a>
        </footer>
    </body>
</html>