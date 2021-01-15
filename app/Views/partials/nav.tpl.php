<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.html">Skoule</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <?php if (isset($_SESSION['userObject'])) : ?>

                    <li class="nav-item<?= $currentPage === 'main/home' ? ' active' : '' ?>">
                        <a class="nav-link" href="<?= $router->generate('main-home'); ?>">Accueil</a>
                    </li>
                    <li class="nav-item<?= $currentPage === 'teacher/list' ? ' active' : '' ?>">
                        <a class="nav-link" href="<?= $router->generate('teacher-list'); ?>">Profs</a>
                    </li>
                    <li class="nav-item<?= $currentPage === 'student/list' ? ' active' : '' ?>">
                        <a class="nav-link" href="<?= $router->generate('student-list'); ?>">Etudiants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'user/list' ? ' active' : '' ?>" href="<?= $router->generate('user-list'); ?>">Utilisateurs</a>
                    </li>
                    <li class="nav-item<?= $currentPage === 'user/logout' ? ' active' : '' ?>">
                        <a class="nav-link" href="<?= $router->generate('user-logout'); ?>">Se déconnecter</a>
                    </li>
                    <?php else : ?>

                    <li class="nav-item<?= $currentPage === 'user/logout' ? ' active' : '' ?>">
                        <a class="nav-link" href="<?= $router->generate('user-logout'); ?>">Se connecter</a>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>