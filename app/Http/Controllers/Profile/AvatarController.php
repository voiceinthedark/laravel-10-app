<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use App\Models\User;

class AvatarController extends Controller
{
    // Define a public function named 'update' that accepts a Request object as a parameter
    public function update(UpdateAvatarRequest $request)
    {
        // Find the user that is currently authenticated and update their avatar with the new one sent in the request
        $user = User::find(auth()->user()->id);

        // store the file in the public folder
        $file = $request->file('avatar')->store('avatars', 'public');
        // dd($file);
        $user->update(['avatar' => 'storage/' . $file ]);

        // Redirect back to the page that made the request with a message indicating that the avatar was successfully changed
        return back()->with('message', 'Avatar Successfully changed!');
    }

}
