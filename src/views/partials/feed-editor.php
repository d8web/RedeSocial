<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>"/>
                <input type="file" name="photo" class="feed-new-file" accept="image/png,image/jpg,image/jeg" />
            </div>
            <div class="feed-new-input-placeholder">
                O que você está pensando, <?=$user->name ?? 'Visitante'?>?
            </div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-photo">
                <img src="<?=$base;?>/assets/images/photo.png"/>
            </div>
            <div class="feed-new-send">
                <img src="<?=$base;?>/assets/images/send.png"/>
            </div>

            <form action="<?=$base;?>/post/new" method="post" class="feed-new-form">
                <input type="hidden" name="body"/>
            </form>

        </div>
    </div>
</div>

<script>
    /*
        Quando o usuário clicar no botão send, o algoritmo vai pegar o valor do campo feed-new-input[contenteditable=true]
        e inserir no formulário, mais especificamente no input name body.
        Em seguida envia o formulário, form.submit
    */
    let feedInput = document.querySelector('.feed-new-input');
    let feedSubmit = document.querySelector('.feed-new-send');
    let feedForm = document.querySelector('.feed-new-form');
    let feedPhoto = document.querySelector('.feed-new-photo');
    let feedFile = document.querySelector('.feed-new-file');

    feedPhoto.addEventListener('click', function() {
        feedFile.click();
    });

    feedFile.addEventListener('change', async function() {
        let photo = feedFile.files[0];

        let formData = new FormData();
        formData.append('photo', photo);

        let req = await fetch(BASE+'/ajax/upload', {
            method: 'POST',
            body: formData
        });

        let json = await req.json();
        if(json.error != '') {
            alert(json.error);
        }

        window.location.href = window.location.href;
    });

    // Adicionar evento de click no botão enviar
    feedSubmit.addEventListener('click', function() {
        // Pegamos o texto do campo feedInput [contenteditable]
        let value = feedInput.innerText.trim();

        if(value != '') {
            // Jogamos o valor no input e depois enviamos o formulário.
            feedForm.querySelector('input[name=body]').value = value;
            feedForm.submit();
        }
    });
</script>