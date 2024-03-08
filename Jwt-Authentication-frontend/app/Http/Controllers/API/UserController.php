<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:2|max:100',
            'email'=> 'required|string|email|max:100|unique:users',
            'password'=> 'required|min:6|string|confirmed',
        ]);

        if($validator->fails()){

            return response()->json([
                'status'=> 'error',
                'message'=> $validator->errors(),
            ]);
        }else{
           $user = User::create([
                'name'=> $request->name,
                'email' => $request->email,
                'password'=> Hash::make($request->password),
            ]);

            return response()->json([
                'message'=>'User Register Successfully !',
                'status'=> 'success',
                'data' => $user,
            ]);
        }
    }

    public function login(Request $request)
    {
       $validator = Validator::make($request->all(),[
          'email'=> 'required|string|email',
          'password'=> 'required|string|min:6',
       ]);

       if($validator->fails()){
          return response()->json([
            'status'=> 'error',
            'message'=> $validator->errors(),
          ]);
       }else{

        if(!$token = auth()->attempt($validator->validated())){

            return response()->json(['status'=>'error','message'=>'Email & password are incorrect']);

        }else{
            return $this->responseWithToken($token);
        }

       }
    }

    protected function responseWithToken($token)
    {
      return response()->json([
        'status'=> 'success',
        'access_token'=> $token,
        'token_type'=> 'Bearer',
        'expires_in'=> auth()->factory()->getTTL()*60
      ]);
    }

    public function logout(Request $request)
    {
        try{
            auth()->logout();
            return response()->json(['status'=> 'success','message'=>'Logout successfully !']);
        }catch(\Exception $e){
            return response()->json(['status'=> 'error','message'=> $e->getMessage()]);
        }
    }
    //pfofile method

    public function profile(Request $request)
    {
        try{
            return response()->json([
                'status'=> 'success',
                'data'=> auth()->user(),
                'message'=> 'Data Get successfully !',
            ]);

        }catch(\Exception $e){
          return response()->json([
            'status'=> 'error',
            'message' => $e->getMessage(),
          ]);
        }
    }

    // updateProfile method

    public function updateProfile(Request $request)
    {
       try{
        if(auth()->user()){
            $validation = Validator::make($request->all(),[
                'id'=> 'required',
                'name'=> 'required|string',
                'email'=> 'required|email|string',
            ]);

            if($validation->fails()){
                return response()->json(['status'=> 'error','message'=> $validation->errors()]);
            }else{
                $user = User::find($request->id);
                $user->name = $request->name;
                if($user->email != $request['email']){
                    $user->is_verifyed = 0;
                }
                $user->email = $request->email;
                $user->save();
                return response()->json(['status'=>'success','data'=> $user,'message'=>'User Updated Successfully !']);
            }
        }else{
            return response()->json(['status'=> 'error','message'=> 'User not found!']);
        }
       }catch(\Exception $e){
        return response()->json(['status'=> 'error','message'=> $e->getMessage()]);
       }
    }

    //sendVerifyMail

    public function sendVerifyMail(Request $request)
    {
       if(auth()->user()){

        $user = User::where('email',$request['email'])->first();
        if(!empty($user)){

            $random = Str::random(40);
            $domain = URL::to('/');
            $url = $domain.'/verify-email'.'/'.$random;
            $data['url'] = $url;
            $data['email'] = $request['email'];
            $data['title'] = "Email Verification !";
            $data['body'] = "Please Click here to verify your mail !";

            Mail::send('verifyMail',['data'=> $data],function($message) use ($data){
                $message->to($data['email'])->subject($data['title']);
            });

            $user->remember_token = $random;
            $user->save();
            return response()->json(['status'=>'success','message'=> 'Email Send Successfully !']);
        }
       }else{
        return response()->json(['status'=> 'error','message'=>'User is not Authenticated']);
       }
    }

    public function VerifyEmail($token)
    {
        $user = User::where('remember_token' ,$token)->first();
        if(!empty($user)){
         $datetime = Carbon::now()->format('Y-m-d H:i:s');
         $user->remember_token = '';
         $user->is_verifyed = 1;
         $user->email_verified_at = $datetime;
         $user->save();

         return "<h1>Email Verified Successfully !</h1>";
        }else{
            return view('404');
        }
    }

    public function refreshToken(Request $request)
    {
        if(auth()->user()){
            return $this->responseWithToken(auth()->refresh());

        }else{
            return response()->json(['status'=> 'error','message'=> 'User not Authenticated!']);
        }
    }
}
