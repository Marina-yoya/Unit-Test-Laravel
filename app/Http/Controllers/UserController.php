<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\UserHelper;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('users.dashboard', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = UserHelper::validateUserRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->route('admin.index')->with('success', 'User was created successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $validator = UserHelper::validateUserRequest($request, $id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::find($id);
        $user->name = $request->name;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->phone_number = $request->phone_number;

        $user->email = strtolower($request->email);

        $user->save();

        return redirect()->route('admin.index')->with('success', 'User was updated successfully');
    }


    public function destroy(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('admin.index')->with('success', 'User was deleted successfully');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('admin.index')->with('error', 'Cannot delete user. It has associated car records.');
            }
            return redirect()->route('admin.index')->with('error', 'An error occurred while deleting the user.');
        }
    }

}
