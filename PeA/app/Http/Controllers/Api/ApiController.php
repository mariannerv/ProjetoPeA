<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LostObject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Emails\SendMailController;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use App\Http\Controllers\VerificationCodeController;

class ApiController extends Controller
{
    public function index()
    {
        $users = User::all();

        $numberUsers = User::count();
        $numberActive = User::where('account_status', 'active')->count();
        $deactivated = User::where('account_status', 'deactivated')->count();

        return view('admin.users', [
            'users' => $users,
            'numberusers' => $numberUsers,
            'numberactive' => $numberActive,
            'deactivated' => $deactivated
        ]);
    }

    public function register(Request $request)
    {
        try {
            $val = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'gender' => 'required|string',
                'birthdate' => 'required|date',
                'address' => 'required|string',
                'codigo_postal' => ['required', 'string', 'regex:/^\d{4}-\d{3}$/'],
                'localidade' => 'required|string',
                'civilId' => 'required|integer|unique:users',
                'taxId' => 'required|string|unique:users',
                'contactNumber' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => ['required', Rules\Password::defaults()],
            ]);

            if ($val->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($val)
                    ->withInput();
            }

            $uuid = (string) Str::uuid();

            $user = User::create([
                "account_id" => $uuid,
                "name" => $request->input('name'),
                "gender" => $request->input('gender'),
                "birthdate" => $request->input('birthdate'),
                "address" => $request->input('address'),
                "codigo_postal" => $request->input('codigo_postal'),
                "localidade" => $request->input('localidade'),
                "civilId" => $request->input('civilId'),
                "taxId" => $request->input('taxId'),
                "contactNumber" => $request->input('contactNumber'),
                "email" => $request->input('email'),
                "password" => Hash::make($request->input('password')),
                "account_status" => 'active',
                "token" => '',
                "email_verified" => false,
                "email_verified_at" => null,
                "bid_history" => [],
                "lost_objects" => [],
                "admin" => false,
            ]);

            event(new Registered($user));

            app(SendMailController::class)->sendWelcomeEmail(
                $request->input('email'),
                "Bem vindo ao PeA!",
                "Bem vindo!"
            );

            app(VerificationCodeController::class)->createCode($request->input('email'));

            return redirect()->route('register.registerSuccess');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    if ($message === 'Número de contribuinte já associado a outra conta.') {
                        return response()->json([
                            "status" => false,
                            "message" => "Número de contribuinte já associado a outra conta.",
                            "code" => 400,
                        ], 400);
                    } elseif ($message === 'Email já associado a outra conta.') {
                        return response()->json([
                            "status" => false,
                            "message" => "Email já associado a outra conta.",
                            "code" => 400,
                        ], 400);
                    } elseif ($message === 'Cartão de cidadão já associado a outra conta.') {
                        return response()->json([
                            "status" => false,
                            "message" => "Cartão de cidadão já associado a outra conta.",
                            "code" => 400,
                        ], 400);
                    } elseif ($message === 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.') {
                        return response()->json([
                            "status" => false,
                            "message" => $message,
                            "code" => 400,
                        ], 400);
                    }
                }
            }
            throw $e;
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $email = $request->input('email');
        $user = User::where("email", $email)->first();

        if ($user) {
            if ($user->account_status == 'active') {
                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::loginUsingId($user->id);

                    return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
                } else {
                    return redirect()->back()->withErrors(['password' => 'Credenciais inválidas'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['account_status' => 'Esta conta está desativada.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Utilizador não encontrado'])->withInput();
        }
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $user->account_status = 'deactivated';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Conta desativada com sucesso",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta inválida.",
            ], 404);
        }
    }

    public function activate(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $user->account_status = 'active';
            $user->save();

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Conta reativada com sucesso.",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "code" => 404,
                "message" => "Conta inválida.",
            ], 404);
        }
    }

    public function profile()
    {
        $data = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "user" => $data,
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return view('home');
    }

    public function report(Request $request)
    {
        $request->validate([
            'textreport' => 'required|string',
        ], [
            'textreport.required' => 'Insira o seu report',
        ]);

        if ($request->input('textreport') != "") {
            app(SendMailController::class)->sendWelcomeEmail(
                'projetopea1@gmail.com',
                $request->input('textreport'),
                'Bug na aplicação'
            );
        }

        return redirect()->back()->with('success', 'E-mail enviado com sucesso!');
    }

    public function update2(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $request->validate([
                'name' => 'string',
                'gender' => 'string',
                'birthdate' => 'date',
                'address' => 'string',
                'civilId' => 'string',
                'taxId' => 'string',
                'contactNumber' => 'string',
                'email' => 'email|unique:users',
                'password' => 'confirmed',
            ]);

            $user->update($request->all());

            return response()->json([
                "status" => true,
                "message" => "Dados atualizados com sucesso",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Utilizador não encontrado",
            ]);
        }
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->address = $request->address;
        $user->codigo_postal = $request->codigo_postal;
        $user->localidade = $request->localidade;
        $user->civilId = $request->civilId;
        $user->taxId = $request->taxId;
        $user->contactNumber = $request->contactNumber;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Dados atualizados com sucesso');
    }
}
