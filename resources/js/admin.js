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

    startListeners();
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

window.startListeners = function ()
{
    let refusePost = $('.refuse-post');
    let approvePost = $('.approve-post');
    let restorePost = $('.restore-post');

    refusePost.unbind();
    approvePost.unbind();
    restorePost.unbind();

    refusePost.click(
        function () {
            let postId = $(this).data('post-id');
            let url = $(this).data('url');
            let refusalReason = $(`#refuse-reason-${postId}`);

            $.ajax(
                {
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: $('#_token').val(),
                        reason: refusalReason.val()
                    },
                    success: function (e) {
                        if (e.success === true) {
                            let offcanvasRefused = $('#refused-count-offcanvas');
                            let sidebarRefused = $('#refused-count-sidebar');
                            let offcanvasUnapproved = $('#unapproved-count-offcanvas');
                            let sidebarUnapproved = $('#unapproved-count-sidebar');

                            let currentRefused = parseInt(offcanvasRefused.html());
                            let currentUnapproved = parseInt(offcanvasUnapproved.html());

                            offcanvasRefused.html(currentRefused + 1);
                            sidebarRefused.html(currentRefused + 1);
                            offcanvasUnapproved.html(currentUnapproved - 1);
                            sidebarUnapproved.html(currentUnapproved - 1);

                            if (currentUnapproved - 1 === 0) {
                                offcanvasUnapproved.addClass('visually-hidden');
                                sidebarUnapproved.addClass('visually-hidden');
                            }

                            if (currentRefused === 0) {
                                offcanvasRefused.removeClass('visually-hidden');
                                sidebarRefused.removeClass('visually-hidden');
                            }

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

                            $(`#refuse-post-modal-${postId}`).modal('hide');
                            refusalReason.val('');
                        }
                    }
                }
            )
        }
    )

    restorePost.click(
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
                            let offcanvasRefused = $('#refused-count-offcanvas');
                            let sidebarRefused = $('#refused-count-sidebar');
                            let offcanvasUnapproved = $('#unapproved-count-offcanvas');
                            let sidebarUnapproved = $('#unapproved-count-sidebar');

                            let currentRefused = parseInt(offcanvasRefused.html());
                            let currentUnapproved = parseInt(offcanvasUnapproved.html());

                            offcanvasRefused.html(currentRefused - 1);
                            sidebarRefused.html(currentRefused - 1);
                            offcanvasUnapproved.html(currentUnapproved + 1);
                            sidebarUnapproved.html(currentUnapproved + 1);

                            if (currentRefused - 1 === 0) {
                                offcanvasRefused.addClass('visually-hidden');
                                sidebarRefused.addClass('visually-hidden');
                            }

                            if (currentUnapproved === 0) {
                                offcanvasUnapproved.removeClass('visually-hidden');
                                sidebarUnapproved.removeClass('visually-hidden');
                            }

                            let post = $(`#post-${postId}`);

                            post.fadeOut('slow');

                            let toast = createToast(
                                'Poszt visszaállítva!',
                                'Visszaállítottad a posztot! Lehet még el kell fogadni, de am zsíros',
                                'border-success'
                            );

                            let toastId = toast.id;
                            toast = toast.toast;

                            $('#toast-container').prepend(toast.outerHTML);

                            $(`#${toastId}`).toast('show');
                        }
                    }
                }
            );
        }
    );

    approvePost.click(
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
                            let offcanvasUnapproved = $('#unapproved-count-offcanvas');
                            let sidebarUnapproved = $('#unapproved-count-sidebar');

                            let currentUnapproved = parseInt(offcanvasUnapproved.html());

                            offcanvasUnapproved.html(currentUnapproved - 1);
                            sidebarUnapproved.html(currentUnapproved - 1);

                            if (currentUnapproved - 1 === 0) {
                                offcanvasUnapproved.addClass('visually-hidden');
                                sidebarUnapproved.addClass('visually-hidden');
                            }

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

}

