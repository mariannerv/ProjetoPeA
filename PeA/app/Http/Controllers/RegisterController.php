<?php

namespace App\Http\Controllers;

 
        use Illuminate\Http\Request;
        use App\Http\Controllers\API\BaseController as BaseController;
        use App\Models\Owner;
        use Illuminate\Support\Facades\Auth;
        use App\Mail\WelcomeEmail;
        use Illuminate\Support\Facades\Mail;
        use Validator;
        use App\Http\Controllers\EmailController as EC;
 
        class RegisterController extends BaseController
        {
            /**
            * Register api
            *
            * @return \Illuminate\Http\Response
            */
            public function register(Request $request)
            {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'gender' => 'required|string',
                    'birthdate' => 'required|date',
                    'addressId' => 'required|string',
                    'civilId' => 'required|string',
                    'taxId' => 'required|string',
                    'contactNumber' => 'required|string',
                    'email' => 'required|string|email',
                    'password' => 'required|confirmed|string|min:6',
                    'c_password' => 'required|same:password',
                ]);

                
 
                if($validator->fails()){
                    $message = $this->sendError('Validation Error.', $validator->errors());
                    return $message;
                }
 
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = Owner::create($input);
                $success['token'] =  $user->createToken('Lost_and_Found_Management_System')->accessToken;
                $success['name'] =  $user->name;
                
                $toEmail = $user->email;
                $mail = new EC();
                $mail->sendWelcomeEmail($toEmail);
                // Mail::to($user->email)->send(new WelcomeEmail());
 
                return $this->sendResponse($success, 'User register successfully.');
            }
 
            /**
            * Login api
            *
            * @return \Illuminate\Http\Response
            */
            public function login(Request $request)
            {
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                    $user = Auth::user(); 
                    $success['token'] =  $user->createToken('Lost_and_Found_Management_System')-> accessToken; 
                    $success['name'] =  $user->name;
 
                    return $this->sendResponse($success, 'User login successfully.');
                } 
                else{ 
                    return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
                } 
            }
        }
    