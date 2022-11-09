<!DOCTYPE html>
<html>

<?php $render('header-login', ['title' => 'Cadastro']); ?>

<body>
    <header>
        <div class="container">
            <a href=""><img src="<?= $base; ?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?= $base ?>/signup">

            <?php if (!empty($flash)) : ?>
                <div class="flash"><?php echo $flash; ?></div>
            <?php endif; ?>

            <input placeholder="Digite seu nome" class="input" type="text" name="name" />
            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />
            <input placeholder="Digite sua senha" class="input" type="password" name="password" />
            <input placeholder="Digite sua data de nascimento" class="input" type="date" name="birthdate" />
            <input class="button" type="submit" value="Cadastrar" />

            <a href="<?= $base ?>/signin">Já possui conta? Faça o login</a>
        </form>
    </section>
</body>

</html>