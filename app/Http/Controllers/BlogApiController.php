<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Tags\AuthorSession;
use Statamic\Facades\Asset;
use Statamic\Facades\AssetContainer;
use Statamic\View\View;
use Statamic\Extend\Filter;
use Statamic\Facades\Term;
use Statamic\Facades\GlobalSet;

class BlogApiController extends Controller
{
    /**
     * Fetch paginated blog entries with optional filters: tab, category, and author followers.
     * Returns JSON response with blog data and pagination meta.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {   
        // Get pagination parameters, with defaults
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = $request->get('limit') ? $request->get('limit') : 2;

        // Optional filters from request
        $tab = $request->get('tab') ? $request->get('tab') : null;
        $category = $request->get('category') ? $request->get('category') : null;

        // Get current logged in author ID from session, if any
        $authorId = session()->get('author_id', null);

        $author_followers = [];
        $default_image = $user = '';

        // Fetch global settings, e.g. default image for fallback
        $globals = GlobalSet::findByHandle('setting');

        if ($globals) {
            // Get all data from the global set in the default site context
            $values = $globals->inDefaultSite()->data()->all();

            // Retrieve default image URL or path, if set
            $default_image = $values['default_image'] ?? null;
        }

        // Only proceed if tab or category filter is present
        if( $tab || $category ){
            // If author logged in, fetch their followers list
            if ($authorId) {
                $user = Entry::query()
                ->where('collection', 'authors')
                ->where('id', $authorId)
                ->first();

                $author_followers = $user->get('author_followers') ?? [];
            }

            // Start building the blog entry query
            $select = Entry::query()
            ->where('collection', 'blog');            

            // Filter for 'recommended' tab: blogs with 2 or more comments
            if( $tab && $tab == 'recommended' ){
                $select->where('comment_count','>=',2);
            }

            // Filter for 'following' tab: only blogs by authors followed by use
            if ($authorId && $tab && $tab == 'following') {
                $select->whereIn('author',$author_followers);
            }

            // Filter by category, if provided
            if ($category) {
                // Using whereJsonContains assuming 'category' is stored as JSON array
                $select->whereJsonContains('category', $category);
            }

            // Set ordering by create_date based on tab filter
            if( $tab && $tab == 'for-you' ){
                $select->orderBy('create_date', 'desc');

            }else{
                $select->orderBy('create_date', 'asc');
            }    

            // Paginate results according to page and limit parameters
            $entries = $select->paginate($limit, ['*'], 'page', $page);

            // Map each blog entry to a structured array for the API response
            $data = $entries->map(function ($entry) use ($default_image) {
                    
                // Get assets field (likely array of asset paths)
                $assets_field = $entry->get('assets_field');
                $show_videos = $entry->get('show_videos');

                // Fetch author entry by author ID in blog entry
                $authors = Entry::query()->where('collection', 'authors')->where('id',$entry->get('author'))->first();

                // Get author's avatar asset path; fallback to default image if missing
                $avatarField = $authors?->get('avatar');
                $avatarAsset = is_array($avatarField) ? 'assets/'.$avatarField[0] ?? $default_image : $avatarField;

                return [
                    'id'          => $entry->id(),
                    'title'       => $entry->get('title'),
                    'slug'        => $entry->slug(),
                    'description' => $entry->get('description'),
                    'create_date' => $entry->date()->format('Y-m-d'),
                    'author'      => [
                        'name'   => $authors->get('title'),
                        'slug'   => $authors->slug(),
                        'avatar' => url($avatarAsset),
                        'author_id' => $authors->id(),
                    ],
                    // Map each asset to full URL
                    'assets_field' =>  collect($assets_field)->map(function ($asset) {
                        return url('assets/'.$asset);
                    })->toArray(),
                    'show_videos'  => $show_videos,

                ];
            });

            // Return success JSON response with blog data and pagination meta
            return response()->json([
                'status' => true,
                'data' => $data,
                'meta' => [
                    'current_page' => $entries->currentPage(),
                    'next_page'    => $entries->hasMorePages() ? $entries->currentPage() + 1 : null,
                    'last_page'    => $entries->lastPage(),
                ],
            ]);
        }else {
            // Return failure response if no filter is provided
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ]);
        }
    }


    /**
     * Return a view snippet for author follow/unfollow buttons.
     *
     * @param Request $request
     * @return \Statamic\View\View
    */
    public function authorsFollowView( Request $request ) {
        $authorId = $request->get('author_id');

        // Fetch author entry by ID
        $author = Entry::find($authorId);

        // Return a view with author data to render follow buttons
        return (new View)
            ->template('author_follow_buttons')
            ->with([
                'author' => $author,
                'id' => $authorId,
            ]);
    }

    /**
     * Return a view snippet for social options (likes, shares) of a blog post.
     *
     * @param Request $request
     * @return \Statamic\View\View
    */
    public function socialOptionView( Request $request ) {
        $authorId = $request->get('author_id');
        $id = $request->get('id');

        // Find blog entry by collection and ID
        $entry = Entry::query()
        ->where('collection', 'blog')
        ->where('id', $id)
        ->first();

        // Get social data from entry or default empty arrays
        $likes = $entry?->get('likes') ?? [];
        $share = $entry?->get('share') ?? [];
        $url = $entry?->url() ?? [];
        $slug = $entry?->slug() ?? [];

        // Find author entry by ID
        $author = Entry::find($authorId);

        // Return a view with social data to render social options
        return (new View)
        ->template('social-option')
        ->with([
            'author' => $author,
            'id' => $id,
            'likes' => $likes,
            'share' => $share,
            'url' => url($url),
            'slug' => $slug,
        ]);
    }
}
