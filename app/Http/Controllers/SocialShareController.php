<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Tags\AuthorSession;

class SocialShareController extends Controller
{   
    /**
     * Handle storing/updating the social share count for a blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {   
        
        // Get the blog post ID from the request, default to empty string if not provided    
        $blog_id = $request->get('blog_id') ? $request->get('blog_id') : '';

        // Retrieve the blog post entry by ID from the 'blog' collection
        $entry = Entry::query()
        ->where('collection', 'blog')
        ->where('id', $blog_id)
        ->first();

        // If blog post not found, return 404 Not Found response
        if (!$entry) {
            return response()->json([
                'status' => false, 
                'message' => 'Blog post not found'
            ], 404);
        }

        // If blog post found, increment the 'share' count by 1 and save
        if ($entry) {
            $count = $entry->get('share') ?? 0;
            $entry->set('share', $count + 1)->save();
        }

        // Return success response indicating share count was updated
        return response()->json(['status' => true,'message' => 'success']);
    }
}
