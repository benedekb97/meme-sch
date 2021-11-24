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

window.startFilterListeners = function ()
{
    let userFilter = $('.user-filter');
    let groupFilter = $('.group-filter');

    let selectedGroups = [];
    let selectedUsers = [];

    let allUsers = window.allUsers;
    let allGroups = window.allGroups;

    groupFilter.click(
        function () {
            let groupId = $(this).data('select-group-id') !== null ? $(this).data('select-group-id') : 'null';

            if (!selectedGroups.includes(groupId)) {
                selectedGroups.push(groupId);
            } else {
                let newSelectedGroups = [];

                selectedGroups.forEach(
                    function (item) {
                        if (item !== groupId) {
                            newSelectedGroups.push(item);
                        }
                    }
                )

                selectedGroups = newSelectedGroups;
            }

            updateSelections();
        }
    );

    userFilter.click(
        function () {
            let userId = $(this).data('select-user-id');

            if (!selectedUsers.includes(userId)) {
                selectedUsers.push(userId);
            } else {
                let newSelectedUsers = [];

                selectedUsers.forEach(
                    function (item) {
                        if (item !== userId) {
                            newSelectedUsers.push(item);
                        }
                    }
                )

                selectedUsers = newSelectedUsers;
            }

            updateSelections();
        }
    );

    function updateSelections() {
        // fix buttons for selected items
        allUsers.forEach(
            function (item) {
                let button = $(`*[data-select-user-id="${item}"]`);

                if (selectedUsers.includes(item)) {
                    button.addClass('bg-primary');
                    button.addClass('text-white');
                } else {
                    button.removeClass('bg-primary');
                    button.removeClass('text-white');
                }
            }
        )
        allGroups.forEach(
            function (item) {
                let button = $(`*[data-select-group-id="${item ?? 'null'}"]`);

                if (selectedGroups.includes(item)) {
                    button.addClass('bg-primary');
                    button.addClass('text-white');
                } else {
                    button.removeClass('bg-primary');
                    button.removeClass('text-white');
                }
            }
        )

        if (selectedUsers.length === 0 && selectedGroups.length === 0) {
            $('.post').removeClass('visually-hidden');
        } else if (selectedUsers.length === 0) {
            $('.post').removeClass('visually-hidden');

            allGroups.forEach(
                function (item) {
                    if (!selectedGroups.includes(item)) {
                        $(`*[data-group-id="${item}"]`).addClass('visually-hidden');
                    }
                }
            );
        } else if (selectedGroups.length === 0) {
            $('.post').removeClass('visually-hidden');

            allUsers.forEach(
                function (item) {
                    if (!selectedUsers.includes(item)) {
                        $(`*[data-user-id="${item}"]`).addClass('visually-hidden');
                    }
                }
            )
        } else {
            $('.post').addClass('visually-hidden');

            selectedUsers.forEach(
                function (item) {
                    $(`*[data-user-id="${item}"]`).removeClass('visually-hidden');
                }
            )
            selectedGroups.forEach(
                function (item) {
                    $(`*[data-group-id="${item}"]`).removeClass('visually-hidden');
                }
            )
        }

        $('#user-filters-selected-count').html(selectedUsers.length === 0 ? '' : selectedUsers.length);
        $('#group-filters-selected-count').html(selectedGroups.length === 0 ? '' : selectedGroups.length);
    }
}

window.startListeners = function ()
{
    startFilterListeners();

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

