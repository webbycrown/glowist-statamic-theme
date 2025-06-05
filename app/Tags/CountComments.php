<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\Facades\Form;
use Statamic\Facades\Entry;

class CountComments extends Tags
{
    protected static $handle = 'count_comments';

    /**
     * Handle the {{ count_comments }} tag
     */
    public function index()
    {
        // Get the post_id from the tag parameters
        $postId = $this->params->get('post_id');
        
        $commentsForm = Form::find('comments'); 

        if (!$commentsForm) {
            return 0; 
        }

        $submissionsCount = $commentsForm->submissions()
        ->filter(function ($submission) use ($postId) {
            return $submission->get('post_id') == $postId;
        })
        ->count();

        return $submissionsCount;
    }
}
