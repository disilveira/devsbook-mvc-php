<?= $render('header', ['title' => 'Home', 'loggedUser' => $loggedUser]); ?>

<section class="container main">

    <?= $render('sidebar'); ?>

    <section class="feed mt-10">

        <div class="row">
            <div class="column pr-5">

                <?php $render('feed-new', [
                    'user' => $loggedUser
                ]); ?>


                <?php foreach ($feed['posts'] as $feedItem) : ?>
                    <?php $render('feed-item', [
                        'data' => $feedItem,
                        'loggedUser' => $loggedUser
                    ]); ?>
                <?php endforeach; ?>

                <div class="feed-pagination">
                    <?php for ($i = 0; $i < $feed['totalPages']; $i++) : ?>
                        <a class="<?= ($i == $feed['currentPage'] ? 'active' : ''); ?>" href="<?= $base . '?page=' . $i; ?>"><?= $i + 1; ?></a>
                    <?php endfor; ?>
                </div>

            </div>

        </div>

    </section>
</section>

<?php $render('footer'); ?>