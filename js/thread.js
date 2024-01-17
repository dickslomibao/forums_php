var add = new Quill('#editor-container-add-thread', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: 'Enter your content...',
    theme: 'snow' // or 'bubble'
});

var edit = new Quill('#editor-container-edit', {
    modules: {
        toolbar: [

            ['bold', 'italic', 'underline'],
            ['link']
        ]
    },
    placeholder: 'Enter your edit...',
    theme: 'snow' // or 'bubble'
});

//use when operation is click and hold the value of data-id
let thread_id = "";

$(document).ready(() => {

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

    $(document).on('click', '#add-thread', () => {

        if ($("#title").val() == "") {
            alert("Title is required!");
        }
        else if ($('#editor-container-add-thread .ql-editor').html() == "<p><br></p>") {
            alert("Content is required!");
        } else {
            $.ajax({
                url: 'threads.php',
                method: 'POST',
                data: {
                    addThread: "",
                    title: $("#title").val(),
                    category: $('#category').val(),
                    descriprion: $('#editor-container-add-thread .ql-editor').html()

                },
                success: function (data) {
                    if (data == "505") {
                        window.location.href = '../error.php';
                    } else {
                        window.location.href = 'threads.php';
                    }
                }
            });
        }

    });

    $(document).on('click', '.operation', (e) => {
        thread_id = $(e.target).data('id');

        $.ajax({
            url: 'threads.php',
            method: 'POST',
            data: {
                getEditData: "",
                thread_id: thread_id

            },
            success: function (data) {
                if (data != "404") {
                    let info = JSON.parse(data)
                    $("#edit-title").val(info[0]);
                    $("#edit-category").val(info[1]);
                    $("#editor-container-edit .ql-editor").html(info[2]);
                } else {
                    window.location.href = "threads.php"
                }
            }
        });
    });

    $(document).on('click', '#edit-thread', () => {

        if ($("#edit-title").val() == "") {
            alert("Title is required!");
        }
        else if ($('#editor-container-edit .ql-editor').html() == "<p><br></p>") {
            alert("Content is required!");
        } else {

            $.ajax({
                url: 'threads.php',
                method: 'POST',
                data: {
                    editThread: "",
                    thread_id: thread_id,
                    title: $("#edit-title").val(),
                    category: $('#edit-category').val(),
                    descriprion: $('#editor-container-edit .ql-editor').html()

                },

                success: function (data) {
                    if (data == "505") {
                        window.location.href = '../error.php';
                    } else {
                        window.location.href = "discussion.php?id=" + thread_id;
                    }
                }
            });
        }

    });
    $(document).on('click', '.delete-thread', () => {

        $.confirm({
            icon: 'fa fa-warning',
            type: 'green',
            theme: 'dark',
            title: 'Delete Thread!',
            draggable: false,
            content: 'Are you sure you want to delete this thread?',
            buttons: {
                Delete: {
                    text: 'Delete',
                    btnClass: 'btn-danger',
                    action: function () {
                        $.ajax({
                            url: 'threads.php',
                            method: 'POST',
                            data: {
                                deleteThread: "",
                                thread_id: thread_id,

                            },
                            success: function (data) {
                                if (data == "505") {
                                    window.location.href = '../error.php';
                                } else {
                                    window.location.href = "threads.php";
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

    let display_thread = true;

    $('#thread-display').scroll(() => {
        if ($('#thread-display').scrollTop() == 0) {
            display_thread = true;
        } else {
            display_thread = false;
        }
    });
    let size_thread = 0;
    const displayThread = (() => {
        $.ajax({
            url: 'threads.php',
            method: 'POST',
            data: {
                displayThread: "",
            },
            success: function (data) {
                if (size_thread != data.trim().length) {
                    $("#threads-container-data").html(data);
                    size_thread = data.trim().length;
                }
            }
        });
    });

    $(".search-thread-title").keyup(() => {
        $.ajax({
            url: 'threads.php',
            method: 'POST',
            data: {
                displayThreadSearch: "",
                thread_title: $(".search-thread-title").val()
            },
            success: function (data) {
                $("#threads-container-data").html(data);
            }
        });
    });

    const displayThreadRuntime = (() => {
        if (display_thread && $(".search-thread-title").val() == "") {
            if (!$('ul').hasClass('show')) {
                displayThread();
            }
        }
    });

    setInterval(displayThreadRuntime, 1000);
});
