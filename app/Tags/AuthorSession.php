<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;

class AuthorSession extends Tags
{
    protected static $handle = 'author_session';

    /**
     * Handle the {{ author_session }} tag
     */
    public function index()
    {
        // Get session data
        $sessionData = $this->getSessionData();

        // If there's content wrapped inside the tag, process it
        $content = $this->parseContent($this->content());

        // Combine session data and wrapped content
        return $sessionData . $content;
    }

    /**
     * Handle {{ author_session:check }}
     */
    public function check()
    {
        return session()->has('author_logged_in') ? true : false;
    }

    /**
     * Handle {{ author_session:name }}
     */
    public function name()
    {
        return session()->get('author_name', 'Guest');
    }

    /**
     * Handle {{ author_session:email }}
     */
    public function email()
    {
        return session()->get('author_email', 'No email found');
    }

    /**
     * Handle {{ author_session:id }}
     */
    public function id()
    {
        return session()->get('author_id', 'No ID found');
    }

    /**
     * Handle {{ author_session:avatar }}
     */
    public function avatar()
    {
        return session()->get('author_avatar', '/assets/default-avatar.png');
    } 

    /**
     * Handle {{ author_session:job_title }}
     */
    public function job_title()
    {
        return session()->get('author_job_title', null);
    }

    /**
     * Handle {{ author_session:avatar }}
     */
    public function background_image()
    {
        return session()->get('author_background_image', '/assets/default-avatar.png');
    } 

    /**
     * Handle {{ author_session:avatar }}
     */
    public function slug()
    {
        return session()->get('author_slug', 'No slug found');
    }

    /**
     * Handle {{ author_session:following }}
     */
    public function isFollowing()
    {
        $userId = $this->params->get('author_id');
        $authorId = session()->get('author_id', null);

        if (!$authorId || !$userId) {
            return false;
        }

        // Get the logged-in author's entry
        $author = Entry::query()
        ->where('collection', 'authors')
        ->where('id', $authorId)->first();

        if (!$author) {
            return false;
        }

        // Get the 'following' field from the logged-in author
        $following = $author->get('author_followers', []);

        return in_array($userId, $following);
    }
    /**
     * Get all author session data
     */
    protected function getSessionData()
    {
        return [
            'author_logged_in' => session()->has('author_logged_in'),
            'author_id' => session()->get('author_id', null),
            'author_slug' => session()->get('author_slug', null),
            'author_job_title' => session()->get('author_job_title', null),
            'author_name' => session()->get('author_name', 'Guest'),
            'author_email' => session()->get('author_email', 'No email found'),
            'author_avatar' => session()->get('author_avatar', '/assets/default-avatar.png'),
            'author_background_images' => session()->get('author_background_images', '/assets/default-avatar.png'),
            'author_following' => session()->get('author_following', []),
        ];
    }


    public function authorData()
    {
        $authorId = session()->get('author_id');

        if (!$authorId) {
            return '';
        }

        $author = Entry::query()
        ->where('collection', 'authors')
        ->where('id', $authorId)
        ->first();
// dd($author);
        if (!$author) {
            return '';
        }

        $field = $this->params->get('field', 'first_name');

        if ($field === 'first_name' || $field === 'last_name') {
            $fullName = $author->get('title');
            $nameParts = explode(' ', $fullName, 2);

            if ($field === 'first_name') {
                return $nameParts[0] ?? '';
            }

            if ($field === 'last_name') {
                return $nameParts[1] ?? '';
            }
        }
       if($field == 'avatar' ||$field == 'background_image' ){

        $avatarAssetId = $author->get($field);

        if (is_array($avatarAssetId)) {
            $assetId = $avatarAssetId[0] ?? null;
        } else {
            $assetId = $avatarAssetId;
        }
        

        if ($assetId) {
            // $asset = Asset::find('assets/'.$assetId);
            $assetId = 'assets::' . ltrim($assetId, '/');
            $asset = \Statamic\Facades\Asset::find($assetId);
            if ($asset) {
                return $asset->url();
            }
        }
    // For other fields like email, just return the field value directly
       }
        return $author->get($field) ?? '';
    }

}
