let user_id;

$(document).ready(() => {
    $(document).on('click', '.operation', (e) => {
        user_id = $(e.target).data('id');

        $('#username').val(user_id);
    });
    $(document).on('click', '.unbanned', () => {
        $.confirm({
            icon: 'fa fa-warning',
            type:'green',
            theme:'dark',
            title: 'Unbanned',
            draggable: false,
            content: 'Are you sure you want to Unbanned this user?',
            buttons: {
                Delete: {
                    text: 'Yes',
                    btnClass: 'btn-danger',
                    action: function(){
                        $.ajax({
                            url: 'user.php',
                            method: 'POST',
                            data: {
                                unbanned:"",
                                userid:user_id
                                                
                            },
                            success: function(data) {
                                window.location.href = "user.php";    
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'No',
                        action: function () {                        
                    }
                }
            }
        });
    });
});