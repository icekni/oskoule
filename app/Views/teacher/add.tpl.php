
        <a href="<?= $router->generate('teacher-list'); ?>" class="btn btn-success float-right">Retour</a>
        <h2>Ajouter un prof</h2>
        <!-- Affichage des erreurs -->
        <?php if (isset($errors['autre'])) : ?>
        
        <div class="alert alert-danger" role="alert">
        <?= $errors['autre']; ?>
        </div>
        <?php endif; ?>      

        <!-- Pas besoin de specifier l'adresse d'envoi du form car il l'envoi a lui meme -->
        <form method="POST" class="mt-5">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="" value="">
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['prenom'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['prenom']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="" value="">
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['nom'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['nom']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="job">Titre</label>
                <input type="text" class="form-control" name="job" id="job" placeholder="" value="">
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['job'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['job']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="0">-</option>
                    <option value="1">actif</option>
                    <option value="2">désactivé</option>
                </select>
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['status'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['status']; ?>
                </div>
                <?php endif; ?>
                
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>