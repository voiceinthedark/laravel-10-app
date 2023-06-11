<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    // Define a public function named 'update' that accepts a Request object as a parameter
    public function update(UpdateAvatarRequest $request)
    {
        // Find the user that is currently authenticated and update their avatar with the new one sent in the request
        $user = User::find(auth()->user()->id);

        // Check if avatar was already uploaded by this user and delete the avatar if so
        // TODO: delete the file
        if ($request->user()->avatar) {
            // dd( url('/') . '/' . $request->user()->avatar);
            Storage::delete(url('/') . '/' . $request->user()->avatar);
        }

        // store the file in the public folder
        $file = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => 'storage/' . $file ]);

        // Redirect back to the page that made the request with a message indicating that the avatar was successfully changed
        return back()->with('message', 'Avatar Successfully changed!');
    }

}
