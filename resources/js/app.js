require('./bootstrap');

import 'bootstrap';

window.bootstrap = require('bootstrap');

window.$ = window.jQuery = require('jquery');

window.loadFile = function(event) {
    let output = $('#image-preview');

    output.attr('src',  URL.createObjectURL(event.target.files[0]));
    output.removeClass('visually-hidden');
    output.onLoad = function () {
        URL.revokeObjectURL(output.src)
    }
}

window.makeId = function (length) {
    let result           = '';
    let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;

    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }

    return result;
}

window.createToast = function (title, content) {
    let template = document.querySelector('#toast-template');

    let clone = template.content.cloneNode(true);
    let toast = clone.querySelector('.toast');

    let id = 'toast-' + makeId(16);

    toast.setAttribute('id', id);

    let body = toast.querySelector('.toast-body');
    let header = toast.querySelector('strong');

    body.innerHTML = content;
    header.innerHTML = title;

    return {
        toast: toast,
        id: id
    };
}

window.submitNewPostForm = function (event) {
    let form = $('#new-post-form');
    let saveButton = $('#new-post-save-button');

    saveButton.html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Feltöltés...
    `);
    saveButton.attr('disabled', 'disabled');

    let postTitle = $('#post-title');
    let postFile = $('#post-file');

    let fd = new FormData();
    let files = postFile[0].files;

    fd.set('title', postTitle.val());
    fd.set('_token', $('#post-csrf-token').val());

    if (files.length > 0) {
        fd.append('file', files[0]);
    }

    $.ajax(
        {
            type: 'POST',
            url: form[0].action,
            data: fd,
            contentType: false,
            processData: false,
            success: function (e) {
                postTitle.removeClass('is-invalid');
                postTitle.removeClass('is-valid');
                postFile.removeClass('is-invalid');
                postFile.removeClass('is-valid');

                postTitle.val(null);
                postFile.val(null);
                $('#image-preview').addClass('visually-hidden');

                $('#new-post-modal').modal('hide');
                saveButton.html('Mentés');
                saveButton.removeAttr('disabled');

                let toast = createToast(
                    'Kép feltöltve!',
                    'Sikeresen feltöltöttél egy memét! Kratulálog :)'
                );

                let toastId = toast.id
                toast = toast.toast;

                $('#toast-container').prepend(toast.outerHTML);

                $(`#${toastId}`).toast('show');

                $('#post-container').prepend(e.post);
            },
            error: function (e) {
                let title = e.responseJSON.title;
                let file = e.responseJSON.file;

                console.log(file, title);

                if (title === false) {
                    postTitle.addClass('is-invalid');
                    postTitle.removeClass('is-valid');
                    $('#post-title-invalid-feedback').html('Kérlek adj címet a posztnak!');
                }

                if (title === true) {
                    postTitle.removeClass('is-invalid');
                    postTitle.addClass('is-valid');
                    $('#post-title-invalid-feedback').html('');
                }

                if (file === false) {
                    postFile.addClass('is-invalid');
                    postFile.removeClass('is-valid');
                    $('#post-file-invalid-feedback').html('Válassz ki egy fájlt!');
                }

                if (file === true) {
                    postFile.addClass('is-valid');
                    postFile.removeClass('is-invalid');
                }

                if (file === 'mimeType') {
                    postFile.addClass('is-invalid');
                    postFile.removeClass('is-valid');
                    $('#post-file-invalid-feedback').html('Érvénytelen formátum! Megengedett kiterjesztések: png, jpeg, gif')
                }

                if (title === 'length') {
                    postTitle.addClass('is-invalid');
                    postTitle.removeClass('is-valid');
                    $('#post-title-invalid-feedback').html('A poszt címe maximum 255 karakter lehet!');
                }

                saveButton.html('Mentés');
                saveButton.removeAttr('disabled');
            }
        }
    )
}

$('.upvote-post').click(
    function () {
        let postId = $(this).data('post-id');
        let url = $(this).data('url');

        $.ajax(
            {
                url: url,
                type: 'PATCH',
                data: {
                    type: 'up',
                    _token: $('#post-csrf-token').val()
                },
                success: function (e) {
                    let upvoteButton = $(`#post-${postId}-upvote-button`);
                    let downvoteButton = $(`#post-${postId}-downvote-button`);

                    downvoteButton.removeClass('bi-arrow-down-circle-fill');
                    downvoteButton.addClass('bi-arrow-down-circle');

                    if (e.vote === null) {
                        upvoteButton.removeClass('bi-arrow-up-circle-fill');
                        upvoteButton.addClass('bi-arrow-up-circle');
                    } else {
                        upvoteButton.removeClass('bi-arrow-up-circle');
                        upvoteButton.addClass('bi-arrow-up-circle-fill');
                    }

                    $(`#post-${postId}-vote-count`).html(e.score);
                }
            }
        )
    }
)

$('.downvote-post').click(
    function () {
        let postId = $(this).data('post-id');
        let url = $(this).data('url');

        $.ajax(
            {
                url: url,
                type: 'PATCH',
                data: {
                    type: 'down',
                    _token: $('#post-csrf-token').val()
                },
                success: function (e) {
                    let upvoteButton = $(`#post-${postId}-upvote-button`);
                    let downvoteButton = $(`#post-${postId}-downvote-button`);

                    upvoteButton.removeClass('bi-arrow-up-circle-fill');
                    upvoteButton.addClass('bi-arrow-up-circle');

                    if (e.vote === null) {
                        downvoteButton.removeClass('bi-arrow-down-circle-fill');
                        downvoteButton.addClass('bi-arrow-down-circle');
                    } else {
                        downvoteButton.addClass('bi-arrow-down-circle-fill');
                        downvoteButton.removeClass('bi-arrow-down-circle');
                    }

                    $(`#post-${postId}-vote-count`).html(e.score);
                }
            }
        )
    }
);
