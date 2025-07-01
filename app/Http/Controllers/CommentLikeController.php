<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Form;
use Statamic\Facades\Submission;


class CommentLikeController extends Controller
{   
    /**
     * Handle a like action on a comment submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id  The ID of the comment submission
     * @return \Illuminate\Http\JsonResponse
    */
    public function like(Request $request, $id)
    {
        // Find the 'comments' form
        $form = Form::find('comments');

        // Retrieve the specific submission by ID
        $submission = $form->submissions()->filter(function ($item) use ($id) {
            return $item->id() === $id;
        })->first();
         
        // If submission is not found, return 404 response    
        if (!$submission) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        // Get current like count or default to 0, then increment
        $likes = $submission->get('likes') ?? 0;
        $likes += 1;

        // Update and save the new like count
        $submission->set('likes', $likes);
        $submission->save();

        // Return updated like count
        return response()->json(['likes' => $likes]);
    }


    /**
     * Handle an unlike action on a comment submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id  The ID of the comment submission
     * @return \Illuminate\Http\JsonResponse
    */
    public function unlike(Request $request, $id)
    {   
        // Find the 'comments' form
        $form = Form::find('comments');

        // Retrieve the specific submission by ID
        $submission = $form->submissions()->filter(function ($item) use ($id) {
            return $item->id() === $id;
        })->first();

        // If submission is not found, return 404 response
        if (!$submission) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        // Decrease like count but ensure it doesn't go below 0
        $likes = $submission->get('likes') ?? 0;
        $likes = max(0, $likes - 1); 

        // Increase the unlike counter
        $unlike = $submission->get('unlike') ?? 0;
        $unlike += 1;

        // Save the updated counts
        $submission->set('likes', $likes);
        $submission->set('unlike', $unlike);
        $submission->save();

        // Return updated like count
        return response()->json(['likes' => $likes]);
    }

    /**
     * Delete a comment submission by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function delete(Request $request)
    {   
        // Find the 'comments' form
        $form = Form::find('comments');

        // Get the ID from the request (default to 0 if not provided)
        $id = $request->get('id') ? $request->get('id') : 0;
        
        // If form is not found, return 404 response
        if (!$form) {
            return response()->json(['error' => 'Form not found'], 404);
        }

        // Locate the specific submission by ID
        $submission = $form->submissions()->filter(function ($item) use ($id) {
            return $item->id() === $id;
        })->first();

        // If submission is not found, return 404 response
        if (!$submission) {
            return response()->json(['error' => 'Submission not found'], 404);
        }

        // Delete the submission
        $submission->delete();

        // Return success message
        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
