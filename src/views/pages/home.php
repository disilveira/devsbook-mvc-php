<?= $render('header', ['title' => 'Home', 'loggedUser' => $loggedUser]); ?>

<section class="container main">

    <?= $render('sidebar'); ?>

    <section class="feed mt-10">

        <div class="row">
            <div class="column pr-5">

                <?php $render('feed-new', [
                    'user' => $loggedUser
                ]); ?>


                <?php foreach ($feed as $feedItem) : ?>
                    <?php $render('feed-item', [
                        'data' => $feedItem,
                        'loggedUser' => $loggedUser
                    ]); ?>
                <?php endforeach; ?>



            </div>

        </div>

    </section>
</section>

<?php $render('footer'); ?>