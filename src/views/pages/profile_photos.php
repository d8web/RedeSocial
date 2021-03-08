<?=$render('header', [
    // Puxando o header e enviando informações do usuário logado
    'loggedUser' => $loggedUser
]);?>

<section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'photos']);?>

    <section class="feed">

        <?=$render('perfil-header', [
            'user' => $user,
            'loggedUser' => $loggedUser,
            'isFollowing' => $isFollowing
        ]);?>

        <div class="row">
            <div class="column">    
                <div class="box">
                    <div class="box-body">
                        <div class="full-user-photos">

                            <?php if(count($user->photos) === 0): ?>
                                Este usuário não possui fotos.
                            <?php endif; ?>

                            <?php foreach($user->photos as $item): ?>
                                <div class="user-photo-item">
                                    <a href="#modal-<?=$item->id;?>" rel="modal:open">
                                        <img src="<?=$base;?>/media/uploads/<?=$item->body;?>"/>
                                    </a>
                                    <div id="modal-<?=$item->id;?>" style="display:none">
                                        <img src="<?=$base;?>/media/uploads/<?=$item->body;?>"/>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>  
                    </div>
                </div>
            </div> 
        </div>
    </section>
</section>
<?=$render('footer');?>