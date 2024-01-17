var comment = new Quill('#editor-container-comments', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: 'Enter your comment...',
    theme: 'snow'
});

var reply = new Quill('#editor-container-reply', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: 'Enter your reply...',
    theme: 'snow'
});
var edit_reply = new Quill('#editor-container-edit-post', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: '',
    theme: 'snow'
});
var edit_reply = new Quill('#editor-container-edit-reply', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: '',
    theme: 'snow'
});
$(document).ready(() => {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('id')

    let postid = "";
    let old_id = "";
    let postid_reply = "";
    //flag for runtime reply if the reply_section is still open for specific post
    let flag = false;



    $(document).on('keyup', '#search-category', () => {
        $.ajax({
            url: 'threads.php',
            method: 'POST',
            data: {
                searchCategory: "",
                value: $("#search-category").val()
            },
            success: function (data) {
                $("#category-container").html(data);
            }
        });

    });

    $(document).on('click', '#add-post', () => {
        if ($('.ql-editor').html() == "<p><br></p>") {
            alert('Content is required');
        } else {
            $.ajax({
                url: 'discussion.php',
                method: 'POST',
                data: {
                    addPost: "",
                    id: id,
                    content: $('.ql-editor').html()

                },
                success: function (data) {
                    if (data == '200') {
                        window.location.href = 'discussion.php?id=' + id;
                    } else {
                        window.location.href = '../error.php';
                    }
                }
            });
        }
    });

    $(document).on('click', '.show-reply', (e) => {

        if (postid != "") {
            $("#" + postid).css("display", "none");
        }
        postid = $(e.target).data('id');

        if (postid != old_id) {
            $("#btn-" + old_id).text("Show replies");

        }

        if ($(e.target).text() == "Hide replies") {
            $("#" + postid).css("display", "none");
            $(e.target).text("Show replies");
            flag = false;
        }
        else {
            $("#" + postid).css("display", "block");
            $(e.target).text("Hide replies");
            flag = true;
            displayReply();
        }

        old_id = postid;
    });


    //get postid from button of modal
    $(document).on('click', '.add-reply', (e) => {
        postid_reply = $(e.target).data('id');
    });

    $(document).on('click', '#add-reply', (e) => {
        if ($('#editor-container-reply .ql-editor').html() == "<p><br></p>") {
            alert('Content is required');
        } else {
        $.ajax({
            url: 'discussion.php',
            method: 'POST',
            data: {
                addReply: "",
                id: postid_reply,
                content: $('#editor-container-reply .ql-editor').html()
            },
            success: function (data) {
                if (data == "200") {
                    $(".btn-close").click();
                    $('#editor-container-reply .ql-editor').html("");
                    displayReply();
                } else {
                    window.location.href = '../error.php';
                }
            }
        });
    }

    });

    const displayReply = (() => {
        $.ajax({
            url: 'discussion.php',
            method: 'POST',
            data: {
                displayReply: "",
                postid: postid
            },
            success: function (data) {
                $("#" + postid + " .main-content-reply").html(data);
            }
        });
    });

    const runtimeDisplayReply = (() => {
        if (flag == true) {
            if (!$('ul').hasClass('show')) {
                displayReply();
            }
        }
    });

    let post_id_edit_delete = "";
    let reply_id_edit_delete = "";

    $(document).on('click', '.operation-post', (e) => {
        post_id_edit_delete = $(e.target).data('id');
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                getEditDataPost: "",
                post_id: post_id_edit_delete
            },
            success: function (data) {
                if (data != "404") {
                    let info = JSON.parse(data)
                    $("#editor-container-edit-post .ql-editor").html(info[0]);
                } else {
                    window.location.href = "threads.php";
                }
            }
        });
    });

    $(document).on('click', '#edit-post', () => {
        if ($("#editor-container-edit-post .ql-editor").html() == "<p><br></p>") {
            alert('Content is required');
        } else {
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                editDataPost: "",
                post_id: post_id_edit_delete,
                content: $("#editor-container-edit-post .ql-editor").html()
            },
            success: function (data) {
                if (data == '200') {
                    window.location.href = 'discussion.php?id=' + id;
                } else {
                    window.location.href = '../error.php';
                }
            }
        });
    }
    });

    $(document).on('click', '.operation-reply', (e) => {
        reply_id_edit_delete = $(e.target).data('id');
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                getEditDataReply: "",
                reply_id: reply_id_edit_delete
            },
            success: function (data) {

                if (data != "404") {
                    let info = JSON.parse(data)
                    $("#editor-container-edit-reply .ql-editor").html(info[0]);
                } else {
                    window.location.href = "threads.php";
                }
            }
        });
    });
    $(document).on('click', '#edit-reply', () => {
        if ($("#editor-container-edit-reply .ql-editor").html() == "<p><br></p>") {
            alert('Content is required');
        } else {
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                editDataReply: "",
                reply_id: reply_id_edit_delete,
                content: $("#editor-container-edit-reply .ql-editor").html()
            },
            success: function (data) {
                if (data == "200") {
                    $(".btn-close").click();
                    $("#editor-container-edit-reply .ql-editor").html("");
                    displayReply();
                } else {
                    window.location.href = '../error.php';
                }

            }
        });
    }
    });
    $(document).on('click', '.delete-post', () => {

        $.confirm({
            icon: 'fa fa-warning',
            type: 'green',
            theme: 'dark',
            title: 'Delete Post!',
            draggable: false,
            content: 'Are you sure you want to delete this post?',
            buttons: {
                Delete: {
                    text: 'Delete',
                    btnClass: 'btn-danger',
                    action: function () {
                        $.ajax({
                            url: '',
                            method: 'POST',
                            data: {
                                deleteDataPost: "",
                                post_id: post_id_edit_delete,
                            },
                            success: function (data) {
                                if (data == '200') {
                                    window.location.href = 'discussion.php?id=' + id;
                                } else {
                                    window.location.href = '../error.php';
                                }
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    action: function () {

                    }
                }
            }
        });
    });
    $(document).on('click', '.delete-reply', () => {

        $.confirm({
            icon: 'fa fa-warning',
            type: 'green',
            theme: 'dark',
            title: 'Delete Reply!',
            draggable: false,
            content: 'Are you sure you want to delete this reply?',
            buttons: {
                Delete: {
                    text: 'Delete',
                    btnClass: 'btn-danger',
                    action: function () {
                        $.ajax({
                            url: '',
                            method: 'POST',
                            data: {
                                deleteDatareply: "",
                                reply_id: reply_id_edit_delete,
                            },
                            success: function (data) {
                                if (data == '200') {
                                    displayReply();
                                } else {
                                    window.location.href = '../error.php';
                                }
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    action: function () {

                    }
                }
            }
        });
    });

    let display_post = true;

    $('#post-data-content').scroll(() => {
        if ($('#post-data-content').scrollTop() == 0) {
            display_post = true;
        } else {
            display_post = false;
        }
    });


    let size_reply = 0;
    const displayPost = (() => {
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                displayPost: "",
                thread_id: id
            },
            success: function (data) {
                if (size_reply != data.trim().length) {
                    $("#post-data-container").html(data);
                    size_reply = data.trim().length;
                    console.log("running");
                }
            }
        });
    });

    const displayPostRuntime = (() => {
        if (display_post && flag == false) {
            if (!$('ul').hasClass('show')) {
                displayPost();
            }
        }
    });
    setInterval(runtimeDisplayReply, 1000);
    setInterval(displayPostRuntime, 1000);
});
