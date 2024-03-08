
@include('header')
@include('navbar')

<style>
    .profile-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
</style>

<div class="profile-container">
    <h1>Hello , <span id="user_name_to_show"></span></h1>
      <div class="email_verified">
        <p><b>Email:- <span id="user_email_to_show"></span> &nbsp;<span id="email_verified"></span></b></p>
      </div>
    <hr>
    <h2>Update Profile</h2>
    <form id="update_profile_form">
        <input type="hidden" value="" id="user_id_hidden" name="id">
        <div class="form-group">
            <label for="new-name">Name:</label>
            <input type="text" id="user_name" name="name" placeholder="Enter new name">
            <span class="error name_err text-danger"></span>
        </div>
        <div class="form-group">
            <label for="new-email">Email:</label>
            <input type="email" id="user_email" name="email" placeholder="Enter new email">
            <span class="error name_err text-danger"></span>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <p class="result"></p>
</div>


<script>
    $(document).ready(function(){
        $('#user_logout').click(function(){

            $.ajax({
                url:"http://127.0.0.1:8000/api/logout",
                type:"Post",
                headers:{'Authorization': localStorage.getItem('user_token')},
                success:function(data){
                    if(data.status == 'success'){
                        localStorage.removeItem('user_token');
                        window.open('/login','_self');
                    }else{
                        alert(data.message);
                    }
                }
            })
        });

        $.ajax({
            url:'http://127.0.0.1:8000/api/profile',
            type:'POST',
            headers:{'Authorization':localStorage.getItem('user_token')},
            success:function(data){
                  if(data.status == 'success'){
                     $('#user_name').val(data.data.name);
                     $('#user_email').val(data.data.email);
                     $('#user_name_to_show').text(data.data.name);
                     $('#user_id_hidden').val(data.data.id);
                     $('#user_email_to_show').text(data.data.email);
                     if(data.data.is_verifyed == 0){
                          $('#email_verified').html(`<button class="btn btn-primary" id="verify_email" data-id="${data.data.email}">please Verfied</button>`);
                     }else{
                        $('#email_verified').text('Verfied')
                     }
                  }else{
                    alert(data.message);
                  }
            }
        });

        $('#update_profile_form').submit(function(e){
            e.preventDefault();

            var formData = $(this).serialize();
            console.log(formData);

            $.ajax({
                url:'http://127.0.0.1:8000/api/update-profile',
                type:'POST',
                headers:{'Authorization': localStorage.getItem('user_token')},
                data:formData,
                success:function(data){
                    $('.error').text('');
                    if(data.status == 'error'){
                        if($('#user_name').val() == '' || $('#user_email').val() == ''){
                       $.each(data.message,function(key,value){
                        $('.'+key+'_err').text(value);
                       });
                    }
                    }else{
                    $('#user_name').val(data.data.name);
                     $('#user_email').val(data.data.email);
                     $('#user_name_to_show').text(data.data.name);
                     $('#user_email_to_show').text(data.data.email);
                     $('.email').text(data.data.email);
                     if(data.data.is_verifyed == 0){
                          $('#email_verified').html(`<button class="btn btn-primary" id="verify_email" data-id="${data.data.email}">please Verfied</button>`);
                     }else{
                        $('#email_verified').text('Verfied')
                     }
                    }
                }
            })
        });
        $(document).on('click' ,'#verify_email',function(){
            var email = $(this).attr('data-id');

            $.ajax({
                url:"http://127.0.0.1:8000/api/send-verify-mail/"+email,
                type:"POST",
                headers:{'Authorization': localStorage.getItem('user_token')},
                success:function(data){
                    $('.result').text(data.message);
                    setTimeout(() => {
                        $('.result').text(data.message);
                    }, 1000);
                }
            });
        })
    });
</script>



