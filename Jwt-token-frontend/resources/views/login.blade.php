@include('header')

<style>
    .container1 {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .registration-form {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .register-link {
    margin-top: 20px;
    }

    .register-link a {
        text-decoration: none;
        color: #007bff;
    }
</style>

   <div class="container1">
    <div class="registration-form">
        <h1 class="text-center mb-4">User Login</h1>
        <form id="login_form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                <span class="error email_err text-danger"></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                <span class="error password_err text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="register-link">
            <a href="/">Register Here</a>
        </div>
    </div>
</div>


   <script>
    $(document).ready(function(){
        $('#login_form').submit(function(e){
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:'http://127.0.0.1:8000/api/login',
                type:'POST',
                data:formData,
                success:function(data){
                    if(data.status == 'error'){
                        $('.error').text('');
                       $.each(data.message,function(key,value){
                          $("."+key+"_err").text(value);
                       })
                    }else{
                       console.log(data);
                       localStorage.setItem("user_token",data.token_type+" "+data.access_token);
                       window.open('/profile',"_self");
                    }
                }
            })
        })
    })
   </script>

