<?= $render('header', ['title' => 'Meu Perfil', 'loggedUser' => $loggedUser]); ?>

<section class="container main">

    <?= $render('sidebar', ['activeMenu' => 'search']); ?>

    <section class="feed mt-10">

        <h1>Você pesquisou por: <?= $searchTerm; ?></h1>

        <div class="full-friend-list">

            <?php foreach ($users as $user) : ?>
                <div class="friend-icon">
                    <a href="<?= $base; ?>/profile/<?= $user->id; ?>">
                        <div class="friend-icon-avatar">
                            <img src="<?= $base; ?>/media/avatars/<?= $user->avatar; ?>" />
                        </div>
                        <div class="friend-icon-name">
                            <?= $user->name; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>

    </section>

</section>

<?php $render('footer'); ?>