<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Statamic\Facades\AssetContainer;
use Str;

class ProfileController extends Controller
{
    /**
     * Update the profile of an author entry.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request)
    {   
        // Find the author entry by collection and ID from the request
        $author = Entry::query()
            ->where('collection', 'authors')
            ->where('id', $request->id)
            ->first();

        // Update basic profile fields from the request
        $author->set('title', $request->first_name . ' ' . $request->last_name);
        $author->set('email', $request->email);
        $author->set('job_title', $request->position);
        $author->set('description', $request->description);

        // Handle avatar file upload if an avatar file is present in the request
        if ($request->hasFile('avatar')) {

            // Get the uploaded avatar file
            $file = $request->file('avatar');

            // Store the uploaded file temporarily in the 'assets' disk root
            $path = $file->store('/', 'assets');

            // Find the 'assets' container to hold media assets
            $container = AssetContainer::find('assets');

            // Return with error if the asset container is not found
            if (!$container) {
                return redirect()->back()->withErrors(['error' => 'Authors asset container not found.']);
            }
            
            // Generate a unique filename using UUID and original extension
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Define the path inside the container where avatar will be stored
            $assetPath = "avatar/{$filename}";

            // Create a new asset with the defined path
            $asset = $container->makeAsset($assetPath);

            // Upload the file contents to the asset and save it
            $asset->upload($file)->save();

            // Associate the uploaded asset's ID with the author's 'avatar' field
            $author->set('avatar', $asset->id());
        }

        // Handle background image file upload if present
        if ($request->hasFile('background_image')) {

            // Get the uploaded background image file
            $file = $request->file('background_image');

            // Store the uploaded file temporarily in the 'assets' disk root
            $path = $file->store('/', 'assets');

            // Find the 'assets' container to hold media assets
            $container = AssetContainer::find('assets');

            // Return with error if the asset container is not found
            if (!$container) {
                return redirect()->back()->withErrors(['error' => 'Authors asset container not found.']);
            }
            
            // Generate a unique filename for the background image
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Define the path inside the container for background images
            $assetPath = "background_image/{$filename}";

            // Create a new asset with the given path
            $asset = $container->makeAsset($assetPath);

            // Upload and save the background image asset
            $asset->upload($file)->save();

            // Set the background image asset ID on the author entry
            $author->set('background_image', $asset->id());
        }

        // Save all changes to the author entry
        $author->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
