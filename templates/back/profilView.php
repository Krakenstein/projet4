<?php $title = 'Profil'; ?>

    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn" href="index.php?action=episodes">Episodes</a>
            <a class="btn<?php if(($countcoms->comsNb)  < 1) echo 'Hidden'?><?php if(($sum->value_sum) > 0) echo 'Red'?>" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn active" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <form id="formProfil" action="index.php?action=resetAdmin" method="post">
                <h3>pseudo:</h3>
                <input title="pseudo" class="champ" type="text" name="pseudo" id="pseudo" placeholder="" size="25"/>
                <label id="labelPseudo" for="pseudo"></label>
                <h3>Changer de mot de passe:</h3>
                <input title="pass" type="password" name="pass" class="password" placeholder="" size="15"/>
                <label id="labelPass" for="pass"></label>
                <h3>Saisissez à nouveau le nouveau mot de passe:</h3>
                <input title="pass2" type="password" name="pass2" class="password" placeholder="" size="15"/>
                <label id="labelPass2" for="pass2"><?php echo $error ?></label>
                <input type="submit" name="resetAdmin" class="boutonVert" value="Valider">
            </form>
            <span id="profilChanges"><?php echo $message ?></span>
        </section>
    </div>
