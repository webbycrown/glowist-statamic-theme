<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Tags\AuthorSession;

class StoreLikesController extends Controller
{   
    /**
     * Handle liking a blog post (store).
     * If the user already liked it, no action is taken.
     * Otherwise, add the user to likes and remove from unlikes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function like(Request $request)
    {
        // Get logged-in author ID from session
        $authorId = session()->get('author_id', null);

        // Return error if user is not logged in
        if (!$authorId) {
            return response()->json(['status' => false, 'message' => 'User not logged in'], 403);
        }

        // Find the logged-in user author entry
        $user = Entry::query()
            ->where('collection', 'authors')
            ->where('id', $authorId)
            ->first();

        // Get blog post ID from request, default to empty string   
        $blog_id = $request->get('blog_id') ? $request->get('blog_id') : '';

        // Find the blog post (store) entry by ID
        $store = Entry::query()
        ->where('collection', 'blog')
        ->where('id', $blog_id)
        ->first();

        // Return error if store or user not found
        if (!$store || !$user) {
            return response()->json(['status' => false,'message' => 'Store or user not found'], 404);
        }

        // Retrieve current arrays of likes and unlikes
        $likes = $store->get('likes', []);
        $unlikes = $store->get('unlikes', []);

        if (in_array($authorId, $likes)) {
            // User already liked the store - no action taken, return empty message
            return response()->json(['status' => false,'message' => '']);
        } else {
            // Add user to likes array
            $likes[] = $authorId;

            // Remove user from unlikes array if present
            $unlikes = array_filter($unlikes, fn($id) => $id !== $authorId);
            $message = 'Liked successfully';
        }

        // Update the store entry with new likes and unlikes arrays
        $store->set('likes', array_values($likes))->set('unlikes', array_values($unlikes))->save();

        // Return success response with message
        return response()->json(['status' => true,'message' => $message]);
    }

    /**
     * Handle unliking a blog post (store).
     * If the user already unliked it, no action is taken.
     * Otherwise, add the user to unlikes and remove from likes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function unlike(Request $request)
    {   
        // Get logged-in author ID from session
        $authorId = session()->get('author_id', null);

        // Return error if user not logged in
        if (!$authorId) {
            return response()->json(['status' => false, 'message' => 'User not logged in'], 403);
        }

        // Find the logged-in user author entry
        $user = Entry::query()
            ->where('collection', 'authors')
            ->where('id', $authorId)
            ->first();

        // Get blog post ID from request, default to empty string    
        $blog_id = $request->get('blog_id') ? $request->get('blog_id') : '';

        // Find the blog post (store) entry by ID
        $store = Entry::query()
            ->where('collection', 'blog')
            ->where('id', $blog_id)
            ->first();

        // Return error if store or user not found    
        if (!$store || !$user) {
            return response()->json(['status' => false,'message' => 'Store or user not found'], 404);
        }

        // Retrieve current arrays of likes and unlikes
        $likes = $store->get('likes', []);
        $unlikes = $store->get('unlikes', []);

        if (in_array($authorId, $unlikes)) {
            // User already unliked the store - no action taken, return empty message
           return response()->json(['status' => false,'message' => '']);

        } else {
            // Add user to unlikes array
            $unlikes[] = $authorId;

            // Remove user from likes array if present
            $likes = array_filter($likes, fn($id) => $id !== $authorId);
            
            $message = 'Unliked successfully';
        }

        // Update the store entry with new unlikes and likes arrays
        $store->set('unlikes', array_values($unlikes))->set('likes', array_values($likes))->save();

        // Return success response with message
        return response()->json(['status' => true,'message' => $message]);
    }
}
