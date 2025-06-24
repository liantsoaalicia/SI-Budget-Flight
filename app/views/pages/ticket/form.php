<h1>Création d'un ticket</h1>
<form action="/ticket/ajout" method="post" enctype="multipart/form-data">
    <label for="titre">Sujet / Titre du probleme <input type="text" name="titre" id="titre"></label><br>
    <label for="description">Description du probleme <textarea name="description" id="description"></textarea></label><br>
    <label for="priorite">Priorite 
        <select name="priorite" id="priorite">
            <option value="basse">Basse</option>
            <option value="normale">Normale</option>
            <option value="haute">Haute</option>
        </select>
    </label><br>
    <label for="file">Joindre un fichier <input type="file" name="file" id="file"></label>
    <label for="tier">Tiers 
        <select name="tiers" id="tiers">
            <?php foreach($tiers as $t) { ?>
                <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
            <?php } ?>
        </select>
    </label>

    <button type="submit">Creer</button>
    <?php if(isset($_GET['success'])) { ?>
        <h1 style="color: green;">Ticket créé</h1>
    <?php } ?>
</form>