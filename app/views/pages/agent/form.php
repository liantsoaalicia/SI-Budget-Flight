<form method="POST" action="/agent/add">
  <div>
    <label for="login">Nom d'utilisateur (login)</label>
    <input type="text" id="login" name="login" required>
  </div>

  <div>
    <label for="lastname">Nom</label>
    <input type="text" id="lastname" name="lastname" required>
  </div>

  <div>
    <label for="firstname">Prénom</label>
    <input type="text" id="firstname" name="firstname" required>
  </div>

  <div>
    <label for="email">Adresse e-mail</label>
    <input type="email" id="email" name="email" required>
  </div>

  <div>
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required>
  </div>

  <div>
    <label for="admin">
      <input type="checkbox" id="admin" name="admin">
      Administrateur
    </label>
  </div>

  <div>
    <button type="submit">Ajouter</button>
  </div>
</form>
