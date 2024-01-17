$(document).ready(() => {

    let id = 0;

    const display = (() => {
        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: { display: "" },
            success: function (data) {
                $("#category-container").html(data);
            }
        });
    });

    $(document).on('click', '.edit', (e) => {
        id = $(e.target).data('id');

        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: {
                getsingledata: "",
                id: id
            },
            success: function (data) {
                const info = JSON.parse(data);
                $("#update-title").val(info[0]);
                $("#update-description").val(info[1]);
            }
        });
    });

    $(document).on('click', '.delete', (e) => {
        id = $(e.target).data('id');

    });

    $(document).on('click', '#add-button', () => {

        let title = $("#add-title").val();
        let description = $("#add-description").val();

        if (title === "") {
            $("#add-title").css("border", "1px solid #FF0000");
            $("#add-title").focus();
            return;
        }
        else {
            $("#add-title").css("border", "1px solid #9FA6B2");
        }

        if (description === "") {
            $("#add-description").css("border", "1px solid #FF0000");
            $("#add-description").focus();
            return;
        }
        else {
            $("#add-description").css("border", "1px solid #9FA6B2");
        }

        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: {
                add: "",
                title: title,
                description: description
            },
            success: function (data) {
                $("#add-title").val("");
                $("#add-description").val("");
                $("#add-modal-close").click();
                display();
                console.log(data);
            }
        });

    });

    $(document).on('click', '#update-button', () => {

        let title = $("#update-title").val();
        let description = $("#update-description").val();

        if (title === "") {
            $("#update-title").css("border", "1px solid #FF0000");
            $("#update-title").focus();
            return;
        }
        else {
            $("#update-title").css("border", "1px solid #9FA6B2");
        }

        if (description === "") {
            $("#update-description").css("border", "1px solid #FF0000");
            $("#update-description").focus();
            return;
        }
        else {
            $("#update-description").css("border", "1px solid #9FA6B2");
        }

        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: {
                update: "",
                id:id,
                title: title,
                description: description
            },
            success: function (data) {
                $("#update-title").val("");
                $("#update-description").val("");
                $("#update-modal-close").click();
                display();
            }
        });
    });
    
    $(document).on('click', '#delete', (e) => {
        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: {
                delete: "",
                id:id
            },
            success: function (data) {
                $("#delete-modal-close").click();
                display();
            }
        });
    });

    $(document).on('keyup', '#search-category', () => {
        $.ajax({
            url: 'category.php',
            method: 'POST',
            data: { 
                searchCategory: "", 
                value:$("#search-category").val()
            },
            success: function (data) {
                $("#category-container").html(data);
            }
        });

    });

    display();
});