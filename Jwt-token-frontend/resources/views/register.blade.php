@include('header')

<style>
    .register-container {
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
    .login-link {
    margin-top: 20px;
    }

    .login-link a {
        text-decoration: none;
        color: #007bff;
    }
</style>

<div class="register-container">
    <div class="registration-form">
        <h1 class="text-center mb-4">User Registration</h1>
        <form id="register_form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                <span class="error name_err text-danger"></span>
            </div>
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
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm your password">
                <span class="error password_confirmation_err text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <div class="login-link">
            <a href="/login">Login Here</a>
        </div>
    </div>
</div>


    <script>
        $(document).ready(function(){
          $('#register_form').submit(function(e){
              e.preventDefault();

              var formData = $(this).serialize();

              $.ajax({
                  url:"http://127.0.0.1:8000/api/register",
                  type:'POST',
                  data:formData,
                  success:function(data){
                      if(data.status == 'error'){
                         printErrorMessage(data.message);
                      }else{
                         $('#register_form')[0].reset();
                         $('.error').text('');
                         alert(data.message);
                      }
                  }
              })

          });
        });

        function printErrorMessage(message){
            $(".error").text("");
           $.each(message,function(key,value){

            if(key == 'password'){
              if(value.length > 1){
                $(".password_err").text(value[0]);
                $(".password_confirmation_err").text(value[1]);
              }else{
                console.log(value[0]);
                if(value[0].includes('confirmation')){
                    $(".password_confirmation_err").text(value);
                }else{
                    $(".password_err").text(value);
                }
              }
            }else{
                $("."+key+"_err").text(value);
            }


           })
        }
      </script>



