        <a href="<?= $router->generate('teacher-add'); ?>" class="btn btn-success float-right">Ajouter</a>

        <h2>Liste des Profs</h2>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Titre</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>

                <!-- Boucle foreach qui va parcourir le tableau $teachers et afficher une ligne par teacher -->
                <?php foreach ($teachers as $teacher) : ?>

                <tr>
                    <th scope="row"><?= $teacher->getId(); ?></th>
                    <td><?= $teacher->getFirstname(); ?></td>
                    <td><?= $teacher->getLastname(); ?></td>
                    <td><?= $teacher->getJob(); ?></td>
                    <td class="text-right">
                        <a href="<?= $router->generate('teacher-edit', ['id' => $teacher->getId()]); ?>" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= $router->generate('teacher-delete', ['id' => $teacher->getId(), 'token' => $_SESSION['csrfToken']])?>">Oui, je veux supprimer</a>
                                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>