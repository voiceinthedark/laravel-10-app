<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class AvatarController extends Controller
{
    // Define a public function named 'update' that accepts a Request object as a parameter
    public function update(UpdateAvatarRequest $request)
    {
        // Find the user that is currently authenticated and update their avatar with the new one sent in the request
        $user = User::find(auth()->user()->id);

        // store the file in the public folder
        $path = $request->file('avatar')->storeAs('avatars', $request->user()->id . '.png', 'public');
        $user->update(['avatar' => 'storage/' . $path]);

        // Redirect back to the page that made the request with a message indicating that the avatar was successfully changed
        return back()->with('message', 'Avatar Successfully changed!');
    }

    public function generate()
    {
        $url = OpenAI::images()->create([
            'prompt' => 'A laravel programmer avatar for user: ' . auth()->user()->name,
            'n' => 1,
            'size' => '256x256',
            'response_format' => 'url',
        ]);

        // get the content of the avatar ai generation
        $contents = file_get_contents($url->data[0]->url);

        // store the file in the public folder
        Storage::disk('public')->put('avatars/' . auth()->user()->id . '.png', $contents);
        // update the user avatar
        $user = auth()->user();
        $user->update(['avatar' => 'storage/avatars/' . $user->id . '.png']);

        // redirect back to the profile page
        return redirect()->route('profile.edit');
    }
}
