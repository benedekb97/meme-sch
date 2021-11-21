require('./bootstrap');

import 'bootstrap';

window.Popper = require('popper.js');
window.bootstrap = require('bootstrap');

window.$ = window.jQuery = require('jquery');

// enable tooltips
$(document).ready(function() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

window.createToast = function (title, content, border = null) {
    let template = document.querySelector('#toast-template');

    let clone = template.content.cloneNode(true);
    let toast = clone.querySelector('.toast');

    let id = 'toast-' + makeId(16);

    toast.setAttribute('id', id);

    if (border !== null) {
        toast.setAttribute('class', `toast hide ${border}`);
    }

    let body = toast.querySelector('.toast-body');
    let header = toast.querySelector('strong');

    body.innerHTML = content;
    header.innerHTML = title;

    return {
        toast: toast,
        id: id
    };
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

$('.delete-post').click(
    function () {
        let postId = $(this).data('post-id');
        let url = $(this).data('url');

        $.ajax(
            {
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('#_token').val()
                },
                success: function (e) {
                    if (e.success === true) {
                        let post = $(`#post-${postId}`);

                        post.fadeOut('fast');

                        let toast = createToast(
                            'Poszt törölve!',
                            'Törölted a psoztot! Megtalálod (majd) a törölt posztok között.',
                            'border-danger'
                        )

                        let toastId = toast.id;
                        toast = toast.toast;

                        $('#toast-container').prepend(toast.outerHTML);

                        $(`#${toastId}`).toast('show');
                    }
                }
            }
        )
    }
)

$('.approve-post').click(
    function () {
        let postId = $(this).data('post-id');
        let url = $(this).data('url');

        $.ajax(
            {
                url: url,
                type: 'PATCH',
                data: {
                    _token: $('#_token').val()
                },
                success: function (e) {
                    if (e.success === true) {
                        let post = $(`#post-${postId}`);

                        post.fadeOut('slow');

                        let toast = createToast(
                            'Poszt elfogadva!',
                            'Elfogadtad a posztot! Már látható lesz a főoldalon.',
                            'border-success'
                        );

                        let toastId = toast.id;
                        toast = toast.toast;

                        $('#toast-container').prepend(toast.outerHTML);

                        $(`#${toastId}`).toast('show');
                    }
                }
            }
        )

    }
)
