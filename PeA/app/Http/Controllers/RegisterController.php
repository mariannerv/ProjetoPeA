<?php

namespace App\Http\Controllers;

 
        use Illuminate\Http\Request;
        use App\Http\Controllers\API\BaseController as BaseController;
        use App\Models\Owner;
        use Illuminate\Support\Facades\Auth;
        use Validator;
 
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
                    'password' => 'required|string',
                    'c_password' => 'required|same:password',
                ]);

                
 
                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }
 
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = Owner::create($input);
                $success['token'] =  $user->createToken('Lost_and_Found_Management_System')->accessToken;
                $success['name'] =  $user->name;
 
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
    