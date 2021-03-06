require('./bootstrap');

import 'bootstrap';

window.Popper = require('popper.js');
window.bootstrap = require('bootstrap');

window.$ = window.jQuery = require('jquery');

window.currentOffset = 0;

const offsetSize = 5;

// enable tooltips
$(document).ready(function() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    startListeners();
});

window.loadFile = function(event) {
    let output = $('#image-preview');

    output.attr('src',  URL.createObjectURL(event.target.files[0]));
    output.removeClass('visually-hidden');
    output.onLoad = function () {
        URL.revokeObjectURL(output.src)
    }
}

window.loadProfilePicture = function (event) {
    let output = $('#profile-picture-preview');

    output.attr('src', URL.createObjectURL(event.target.files[0]));
    output.removeClass('visually-hidden');
    output.onLoad = function () {
        URL.revokeObjectURL(output.src);
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

window.submitProfileForm = function (event) {
    let form = $('#profile-form');
    let saveButton = $('#profile-save-button');

    saveButton.html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        T??lt??s...
    `);
    saveButton.attr('disabled', 'disabled');

    let nickname = $('#nickname');
    let file = $('#profile-picture');

    let fd = new FormData();
    let files = file[0].files;

    fd.set('_token', $('#post-csrf-token').val());
    fd.set('nickname', nickname.val());

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
                saveButton.removeAttr('disabled');
                saveButton.html('Ment??s');

                let toast = createToast('Profil mentve!', 'A profilod el lett mentve!');

                let toastId = toast.id;
                toast = toast.toast;

                $('#toast-container').prepend(toast.outerHTML);

                $(`#${toastId}`).toast('show');
            }
        }
    )
}

window.submitReportForm = function (event) {
    let postId = event.target.getAttribute('data-post-id');

    let reason = $(`input[type='radio'][name="reason-${postId}"]:checked`).val();

    let url = $(`#report-post-form`).attr('action');

    if (reason === null) {
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: $('#post-csrf-token').val(),
            post: postId,
            reason: reason
        },
        success: function (e) {
            let flagIcon = $(`#flag-post-${postId}`);
            let flagButton = $(`#flag-post-button-${postId}`);

            flagIcon.removeClass('bi-flag');
            flagIcon.addClass('bi-flag-fill');

            flagButton.removeAttr('data-bs-target');
            flagButton.removeAttr('data-bs-toggle');

            let toast = createToast('Poszt jelentve!', 'A poszt jelentve lett! Ha t??r??lve lesz a poszt kapsz r??la majd egy emailt, addig meg l??gy t??relmes :)');

            let toastId = toast.id;
            toast = toast.toast;

            $('#toast-container').prepend(toast.outerHTML);

            $(`#${toastId}`).toast('show');

            $(`#report-post-${postId}`).modal('hide');
        },
        error: function () {

        }
    })
}

window.submitNewPostForm = function (event) {
    let form = $('#new-post-form');
    let saveButton = $('#new-post-save-button');

    saveButton.html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Felt??lt??s...
    `);
    saveButton.attr('disabled', 'disabled');

    let postTitle = $('#post-title');
    let postFile = $('#post-file');
    let postAnonymous = $('#post-anonymous');
    let postGroup = $('#post-group');

    let fd = new FormData();
    let files = postFile[0].files;

    fd.set('title', postTitle.val());
    fd.set('_token', $('#post-csrf-token').val());
    fd.set('anonymous', postAnonymous[0].checked);
    fd.set('groupId', postGroup.val() ?? null);

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
                saveButton.html('Ment??s');
                saveButton.removeAttr('disabled');

                let toastContent;

                if (e.post === null) {
                    toastContent = 'Sikeresen felt??lt??tt??l egy mem??t! Kratul??log :)<br>Elb??r??l??s ut??n kiker??l az oldalra ;)';
                } else {
                    toastContent = 'Sikeresen felt??lt??tt??l egy mem??t! Kratul??log :)';
                }

                let toast = createToast('Poszt felt??ltve!', toastContent)

                let toastId = toast.id
                toast = toast.toast;

                $('#toast-container').prepend(toast.outerHTML);

                $(`#${toastId}`).toast('show');

                if (e.post !== null) {
                    $('#post-container').prepend(e.post);
                }

                startListeners();
            },
            error: function (e) {
                let title = e.responseJSON.title;
                let file = e.responseJSON.file;

                console.log(file, title);

                if (title === false) {
                    postTitle.addClass('is-invalid');
                    postTitle.removeClass('is-valid');
                    $('#post-title-invalid-feedback').html('K??rlek adj c??met a posztnak!');
                }

                if (title === true) {
                    postTitle.removeClass('is-invalid');
                    postTitle.addClass('is-valid');
                    $('#post-title-invalid-feedback').html('');
                }

                if (file === false) {
                    postFile.addClass('is-invalid');
                    postFile.removeClass('is-valid');
                    $('#post-file-invalid-feedback').html('V??lassz ki egy f??jlt!');
                }

                if (file === true) {
                    postFile.addClass('is-valid');
                    postFile.removeClass('is-invalid');
                }

                if (file === 'mimeType') {
                    postFile.addClass('is-invalid');
                    postFile.removeClass('is-valid');
                    $('#post-file-invalid-feedback').html('??rv??nytelen form??tum! Megengedett kiterjeszt??sek: png, jpeg, gif')
                }

                if (title === 'length') {
                    postTitle.addClass('is-invalid');
                    postTitle.removeClass('is-valid');
                    $('#post-title-invalid-feedback').html('A poszt c??me maximum 255 karakter lehet!');
                }

                saveButton.html('Ment??s');
                saveButton.removeAttr('disabled');
            }
        }
    )
}

window.sendComment = function (event) {
    let replyTo = event.target.getAttribute('data-comment-id') ?? null;
    let postId = event.target.getAttribute('data-post-id');
    let comment = event.target.value;
    let url = $('#create-comment-url').val();

    $.ajax(
        {
            url: url,
            type: 'POST',
            data: {
                replyTo: replyTo,
                post: postId,
                comment: comment,
                _token: $('#post-csrf-token').val()
            },
            success: function (e) {
                if (replyTo !== null) {
                    $(`#comment-${replyTo}-reply-form-section`).addClass('visually-hidden');
                    $(`#comment-${replyTo}-comment`).val('');
                    $(`#comment-${replyTo}-replies`).append(e.comment.html);
                } else {
                    $(`#post-${postId}-comments`).prepend(e.comment.html);
                    $(`#post-${postId}-comment`).val('');
                }

                startListeners();
            }
        }
    )
}

window.startListeners = function () {
    let replyComment = $('.reply-comment');
    let upvoteComment = $('.upvote-comment');
    let downvoteComment = $('.downvote-comment');
    let upvotePost = $('.upvote-post');
    let downvotePost = $('.downvote-post');

    let loadMoreButton = $('.load-more');
    let autoScroll = $('#scroll-auto').val() === 'true';

    replyComment.unbind();
    upvoteComment.unbind();
    downvoteComment.unbind();
    upvotePost.unbind();
    downvotePost.unbind();
    loadMoreButton.unbind();
    $(window).unbind();

    replyComment.click(
        function () {
            let commentId = $(this).data('comment-id');
            let commentFormSection = $(`#comment-${commentId}-reply-form-section`);

            if (commentFormSection.hasClass('visually-hidden')) {
                commentFormSection.removeClass('visually-hidden');
                $(`#comment-${commentId}-comment`).focus();
            } else {
                commentFormSection.addClass('visually-hidden');
            }
        }
    )

    upvoteComment.click(
        function () {
            let commentId = $(this).data('comment-id');
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
                        let upvoteButton = $(`#comment-${commentId}-upvote-button`);
                        let downvoteButton = $(`#comment-${commentId}-downvote-button`);

                        downvoteButton.removeClass('bi-arrow-down-circle-fill');
                        downvoteButton.addClass('bi-arrow-down-circle');

                        if (e.vote === null) {
                            upvoteButton.removeClass('bi-arrow-up-circle-fill');
                            upvoteButton.addClass('bi-arrow-up-circle');
                        } else {
                            upvoteButton.removeClass('bi-arrow-up-circle');
                            upvoteButton.addClass('bi-arrow-up-circle-fill');
                        }

                        $(`#comment-${commentId}-vote-count`).html(e.score);
                    }
                }
            )
        }
    )

    downvoteComment.click(
        function () {
            let commentId = $(this).data('comment-id');
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
                        let upvoteButton = $(`#comment-${commentId}-upvote-button`);
                        let downvoteButton = $(`#comment-${commentId}-downvote-button`);

                        upvoteButton.removeClass('bi-arrow-up-circle-fill');
                        upvoteButton.addClass('bi-arrow-up-circle');

                        if (e.vote === null) {
                            downvoteButton.removeClass('bi-arrow-down-circle-fill');
                            downvoteButton.addClass('bi-arrow-down-circle');
                        } else {
                            downvoteButton.addClass('bi-arrow-down-circle-fill');
                            downvoteButton.removeClass('bi-arrow-down-circle');
                        }

                        $(`#comment-${commentId}-vote-count`).html(e.score);
                    }
                }
            )
        }
    )

    upvotePost.click(
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

    downvotePost.click(
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

    let loadMore = function () {
        window.currentOffset += offsetSize;

        let url = $('#offset-url').val() + `/offset/${window.currentOffset}`;
        let groupId = $('#group-id').val() ?? null;

        $.ajax(
            {
                url: url,
                type: 'GET',
                data: {
                    groupId: groupId
                },
                success: function (e) {
                    e.forEach(
                        function (element) {
                            $('#post-container').append(element);
                        }
                    )

                    if (e.length !== offsetSize) {
                        window.currentOffset -= (offsetSize - e.length);
                    }

                    if (e.length === 0) {
                        loadMoreButton.addClass('visually-hidden');

                        startListeners();

                        loadMoreButton.unbind();
                        $(window).unbind();
                    } else {
                        startListeners();
                    }
                },
                error: function () {
                    window.currentOffset -= offsetSize;
                }
            }
        );
    }

    let scrollFunction = function (e) {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            loadMore();
        }
    }

    if (autoScroll) {
        $(window).scroll(scrollFunction);
        loadMoreButton.click(loadMore);
    }
}

