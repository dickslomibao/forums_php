$(document).ready(() => {

    
    $(document).on('keyup', '#search-category', () => {
        $.ajax({
            url: 'index.php',
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
    
});