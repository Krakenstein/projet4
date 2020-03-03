<?php $title = 'Connection'; ?>

    <section>
        <h2>Connectez-vous à l'espace administrateur</h2>
        <form id="formConnect" action="index.php?action=admConnect" method="post">
            <input type="hidden" name="csrf" value="<?php echo $token ?>">
            <input title="nom" class="champ" type="text" name="nom" id="nom" placeholder=" Votre identifiant" size="15"/>
			<label id="labelNom" for="nom"></label>
            <input title="pass" class="champ" type="password" name="password" id="password" placeholder=" Votre mot de passe" size="15"/>
            <label id="labelPass" for="password"><?php if(isset($error)) echo $error ?></label>
            <input type="submit" class="bouton" value="Connection">
        </form>
    </section>
