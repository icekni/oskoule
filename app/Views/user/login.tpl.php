
        <div class="card card--signin">
            <div class="card-header">
                Connexion
            </div>
            <!-- Affichage des erreurs -->
            <?php if (isset($errors['autre'])) : ?>
            
            <div class="alert alert-danger" role="alert">
            <?= $errors['autre']; ?>
            </div>
            <?php endif; ?>      

            <div class="card-body">
                <form action="<?= $router->generate('user-login'); ?>" method="post">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Saisissez votre adresse email" value="">
                        <!-- Affichage des erreurs champ par champ -->
                        <?php if (isset($errors['email'])) : ?>
                        
                        <div class="alert alert-danger" role="alert">
                        <?= $errors['email']; ?>
                        </div>
                        <?php endif; ?>
                
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Saisissez votre mot de passe" value="">
                        <!-- Affichage des erreurs champ par champ -->
                        <?php if (isset($errors['password'])) : ?>
                        
                        <div class="alert alert-danger" role="alert">
                        <?= $errors['password']; ?>
                        </div>
                        <?php endif; ?>
                
                    </div>

                    <!-- Protection anti CSRF -->
                    <input type="hidden" value="<?= $_SESSION['csrfToken']; ?>" name="token">

                    <button type="submit" class="btn btn-primary btn-block mt-4">se connecter</button>
                </form>
            </div>
        </div>