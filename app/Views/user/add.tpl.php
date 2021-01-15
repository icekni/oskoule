
        <a href="<?= $router->generate('user-list'); ?>" class="btn btn-success float-right">Retour</a>
        <h2>Ajouter un utilisateur</h2>
        <!-- Affichage des erreurs -->
        <?php if (isset($errors['autre'])) : ?>
        
        <div class="alert alert-danger" role="alert">
        <?= $errors['autre']; ?>
        </div>
        <?php endif; ?>      

        <form action="" method="POST" class="mt-5">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="" value="<?= isset($user) ? $user->getEmail() : ''; ?>">
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['email'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['email']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="" value="<?= isset($user) ? $user->getName() : ''; ?>">
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['nom'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['nom']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="" value="" aria-describedby="passwordHelp">
                <!-- Et bien sur on ne pre-remplit pas le champs password, de toutes facons avec le hash, on ne pourrait pas :-) -->
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['password'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['password']; ?>
                </div>
                <?php endif; ?>

                <small id="passwordHelp" class="form-text text-muted">Minimum 8 caracteres, dont au moins 1 majuscule, 1 minuscule et 1 caractere special                    
                </small>
                
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="user"<?= isset($user) && $user->getRole() == 'user' ? ' selected' : ''; ?>>Utilisateur</option>
                    <option value="admin"<?= isset($user) && $user->getRole() == 'admin' ? ' selected' : ''; ?>>Administrateur</option>
                </select>
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['role'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['role']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="0">-</option>
                    <option value="1"<?= isset($user) && $user->getStatus() == '1' ? ' selected' : ''; ?>>actif</option>
                    <option value="2"<?= isset($user) && $user->getStatus() == '2' ? ' selected' : ''; ?>>désactivé</option>
                </select>
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['status'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['status']; ?>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Protection anti CSRF -->
            <input type="hidden" value="<?= $_SESSION['csrfToken']; ?>" name="token">

            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>