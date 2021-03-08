<?=$render('header', [
    // Puxando o header e enviando informações do usuário logado
    'loggedUser' => $loggedUser
]);?>

<section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'search']);?>
    <section class="feed mt-10">
        <div class="row">
            <div class="column pr-5">
                <h1>Você pesquisou por: <?=$searchTerm;?></h1>
                <div class="full-friend-list">
                    <?php foreach($users as $item): ?>
                        <div class="friend-icon">
                            <a href="<?=$base;?>/perfil/<?=$item->id;?>">
                                <div class="friend-icon-avatar">
                                    <img src="<?=$base;?>/media/avatars/<?=$item->avatar;?>"/>
                                </div>
                                <div class="friend-icon-name">
                                    <?=$item->name;?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="column side pl-5">
                <?=$render('right-side');?>
            </div>
        </div>
    </section>
</section>
<?=$render('footer');?>