<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Entry;

class AuthorFollowController extends Controller
{   
    /**
     * Follow an author by adding the user ID to the author's followers list.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function follow(Request $request)
    {   
        // Get author ID and user ID from the request
        $authorId = $request->input('author_id');
        $userId = $request->input('user_id');

        // Validate input presence
        if (!$authorId || !$userId) {
            return response()->json(['error' => 'Invalid author or user.'], 400);
        }

        // Find the author entry by collection and ID
        $author = Entry::query()
            ->where('collection', 'authors')
            ->where('id', $authorId)
            ->first();

        // Return error if author not found or collection handle mismatches    
        if (!$author || $author->collection()->handle() !== 'authors') {
            return response()->json(['error' => 'Author not found.'], 404);
        }

        // Get current followers array from the author, or empty array if none
        $followers = $author->get('author_followers') ?? [];

        // Add user ID to followers if not already following
        if (!in_array($userId, $followers)) {
            $followers[] = $userId;
            // Update the author's followers field and save changes
            $author->set('author_followers', array_values($followers))->save();
        }

        // Return success response with updated followers count
        return response()->json(['success' => 'Followed', 'followers' => count($followers)]);
    }

    /**
    * Check if the current session user is following the given author.
    *
    * @return bool
    */
    public function unfollow(Request $request)
    {   
        // Get author ID from params (assuming a ParamBag or similar exists)
        $authorId = $request->input('author_id');

        // Get logged-in user ID from session (null if not logged in)
        $userId = $request->input('user_id');

        // Return false if missing IDs
        if (!$authorId || !$userId) {
            return response()->json(['error' => 'Invalid author or user.'], 400);
        }

        // Find the author entry by collection and ID
        $author = Entry::query()
            ->where('collection', 'authors')
            ->where('id', $authorId)
            ->first();

        // Return false if author not found or collection handle mismatches    
        if (!$author || $author->collection()->handle() !== 'authors') {
            return response()->json(['error' => 'Author not found.'], 404);
        }

        // Get current followers array or empty if none
        $followers = $author->get('author_followers') ?? [];

         // Filter out the user ID from followers list
        $updatedFollowers = array_filter($followers, fn($follower) => $follower !== $userId);

        // Update the followers field and save changes
        $author->set('author_followers', array_values($updatedFollowers))->save();

        // Return success response with updated followers count
        return response()->json(['success' => 'Unfollowed', 'followers' => count($updatedFollowers)]);
    }

    /**
     * Check if the current session user is following the given author.
     *
     * @return bool
     */
    public function isFollowing(){

        // Get author ID from params (assuming a ParamBag or similar exists)
        $authorId = $this->params->get('author_id');

        // Get logged-in user ID from session (null if not logged in)
        $userId = session()->get('author_id', null);

        // Return false if missing IDs
        if (!$authorId || !$userId) {
            return false;
        }

        // Find the author entry by collection and ID
        $author = Entry::query()
        ->where('collection', 'authors')
        ->where('id', $authorId)
        ->first();

        // Return false if author not found or collection handle mismatches
        if (!$author || !$author->collection()->handle() === 'authors') {
            return false;
        }

        // Get the list of authors the user is following (field named 'following')
        $following = $author->get('following', []);

        // Check if the author ID is in the following list
        return in_array($authorId, $following);
    }
}

