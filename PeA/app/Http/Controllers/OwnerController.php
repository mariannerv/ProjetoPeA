<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner; // Import the Owner model

class OwnerController extends Controller
{
     public readonly Owner $user;
    public function __construct()
    {
        $this->user = new Owner();
    }
    public function index() {
        $users = $this->user->all();
        return view('admin.users' , ['users' => $users]);
    }
    
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'addressId' => 'required|string',
            'civilId' => 'required|string',
            'taxId' => 'required|string',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]); 

        // Create a new Owner instance with the validated data
        $owner = new Owner();
        $owner->name = $validatedData['name'];
        $owner->gender = $validatedData['gender'];
        $owner->birthdate = $validatedData['birthdate'];
        $owner->addressId = $validatedData['addressId'];
        $owner->civilId = $validatedData['civilId'];
        $owner->taxId = $validatedData['taxId'];
        $owner->contactNumber = $validatedData['contactNumber'];
        $owner->email = $validatedData['email'];
        $owner->password = bcrypt($validatedData['password']);
        // Save the new owner to the database
        $owner->save();

        // Return a success response
        return response()->json(["result" => "ok"], 201);
    }

    public function getUserByCivilId($civilId)
    {
        // Query the MongoDB to find the owner by civilId
        $owner = Owner::where('civilId', $civilId)->first();

        if (!$owner) {
            return response()->json(['error' => 'Owner not found'], 404);
        }

        return response()->json($owner);
    }


}
