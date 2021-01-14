
        <a href="<?= $router->generate('student-list'); ?>" class="btn btn-success float-right">Retour</a>
        <h2>Ajouter un étudiant</h2>
        <!-- Affichage des erreurs -->
        <?php if (isset($errors['autre'])) : ?>
        
        <div class="alert alert-danger" role="alert">
        <?= $errors['autre']; ?>
        </div>
        <?php endif; ?>                

        <form action="" method="POST" class="mt-5">
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
                <label for="teacher">Prof</label>
                <select name="teacher" id="teacher" class="form-control">
                    <option value="0">-</option>
                    <!-- Affichage dynamique des options des profs -->
                    <?php foreach ($teachers as $teacher) : ?>
                        
                    <option value="<?= $teacher->getId(); ?>"><?= $teacher->getFirstname(); ?> - <?= $teacher->getJob(); ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- Affichage des erreurs champ par champ -->
                <?php if (isset($errors['teacher'])) : ?>
                
                <div class="alert alert-danger" role="alert">
                <?= $errors['teacher']; ?>
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