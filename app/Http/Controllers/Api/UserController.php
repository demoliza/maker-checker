<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\User;
use App\Enums\RequestTypes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\RequestCreated;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $userCreateRequest = RequestModel::create([
            'request_by' => auth()->user()->id,
            'request_type' => RequestTypes::CREATE->value,
            'meta' => $validatedData,
        ]);

        Mail::to(['admin01@gmail.com','admin02@gmail.com'])->send(new RequestCreated());

        return response()->json([
            'success' => true,
            'message' => "Request to create a new user has been received"
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findorFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes','required','string','email','max:255', Rule::unique('users')->ignore($id)],
            'password' => 'sometimes|required|string|min:6',
        ]);

        $validatedData['id'] = $id;

        $userUpdateRequest = RequestModel::create([
            'request_by' => auth()>user()->id,
            'request_type' => RequestTypes::UPDATE,
            'meta' => $validatedData,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Request to update user has been received"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findorFail($id);

        $validatedData['id'] = $id;

        $userUpdateRequest = RequestModel::create([
            'request_by' => auth()->user()->id,
            'request_type' => RequestTypes::DELETE,
            'meta' => $validatedData,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Request to delete user has been received"
        ]);
    }
}
