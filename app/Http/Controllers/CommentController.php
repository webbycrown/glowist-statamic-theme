<?php

namespace App\Http\Controllers;

use Statamic\Facades\Form;
use Statamic\Facades\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml; // For converting PHP arrays to YAML format

class CommentController extends Controller
{
    /**
     * Store a new comment as a YAML file and update the blog post's comment count.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
        // Collect comment data from the request
        $data = [
            'author_id' => $request->get('author_id'),   // ID of the commenting author
            'comment' => $request->get('comment'),       // The actual comment text
            'parent_id' => $request->get('parent_id'),   // If this is a reply, parent comment ID
            'post_id' => $request->get('post_id'),       // The blog post ID this comment belongs to
            'submitted_at' => now()->toDateTimeString(), // Timestamp of submission
        ];

        // Retrieve the blog post entry by collection and ID
        $entry = Entry::query()
        ->where('collection', 'blog')
        ->where('id', $request->get('post_id'))
        ->first();

        if ($entry) {
            // Get current comment count; default to 0 if missing
            $currentCount = $entry->get('comment_count') ?? 0;

            // If count is zero, set to 2, else increment by 1
            // (Check if this is intentional; typically you'd increment by 1)
            $currentCount == 0 ? $entry->set('comment_count', 2 ) : $entry->set('comment_count', $currentCount + 1);

            // Save updated entry with new comment count
            $entry->save();
        }
        
        // Convert the comment data array into YAML format string
        $yamlContent = Yaml::dump($data);

        // Generate a unique filename for the YAML file (using current timestamp)
        $fileName = time() . '.yaml';

        // Save the YAML content into the 'local' storage disk (storage/app directory)
        Storage::disk('local')->put($fileName, $yamlContent);

        // Return a JSON response confirming successful save
        return response()->json(['message' => 'Comment saved as YAML file!', 'file' => $fileName]);
    }
}
