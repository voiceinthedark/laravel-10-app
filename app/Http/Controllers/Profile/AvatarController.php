<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AvatarController extends Controller
{
    // Define a public function named 'update' that accepts a Request object as a parameter
    public function update(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // If not validated, return back with an error message
        if (!$validated) {
            return back()->withErrors($validated)->withInput();
        }

        // Find the user that is currently authenticated and update their avatar with the new one sent in the request
        $user = User::find(auth()->user()->id);
        $user->avatar = $request->avatar;
        $user->save();

        // Redirect back to the page that made the request with a message indicating that the avatar was successfully changed
        return back()->with('message', 'Avatar Successfully changed!');
    }

}
