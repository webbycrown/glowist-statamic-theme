<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

class SearchController extends Controller
{   
    /**
     * Perform a search on blog entries based on a query string.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function search(Request $request)
    {   
        // Retrieve search query from request, default to 'popular' if not provided
        $query = $request->get('query') ? $request->get('query') :'popular';

        // Retrieve limit from request, default to 0 (no limit)
        $limit = $request->get('limit') ? $request->get('limit') : 0;

        $default_image = null;

        // Fetch global settings, especially for default image
        $globals = GlobalSet::findByHandle('setting');

        if ($globals) {
            // Get all global setting values as a collection
            $values = $globals->inDefaultSite()->data()->all();

            // Extract the default image URL or path if set
            $default_image = $values['default_image'] ?? null;
        }
        
        // Retrieve unique categories from the 'blog' collection entries
        $categories = Entry::query()
        ->where('collection', 'blog')
        ->pluck('category')      // Extract category field (which may be an array)
        ->flatten()              // Flatten nested arrays
        ->unique()               // Get unique categories
        ->values();              // Reindex collection

        // Build the search query on the 'blog' collection
        $select = Entry::query()
        ->where('collection', 'blog')
        ->where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
            ->orWhereJsonContains('category', $query);
        });

        // Apply limit if specified and not zero
        if( isset($limit) &&  $limit != 0 ){
           $select->limit($limit);
        }   

        // Fetch matching entries and map them into structured response format
        $results = $select->get()
        ->map(function ($result) use ($limit, $default_image) {
            // Get assets field (assumed to be an array of asset filenames or IDs)
            $assets_field = $result->get('assets_field');

            // Fetch author entry associated with this blog post
            $authors = Entry::query()->where('collection', 'authors')->where('id',$result->get('author'))->first();

            // Retrieve avatar field from the author, if any
            $avatarField = $authors?->get('avatar');
            
            // Determine avatar asset path: if array, use first element; fallback to default image
            $avatarAsset = is_array($avatarField) ? 'assets/'.$avatarField[0] ?? '/'.$default_image : $avatarField;

            // Return a formatted array representing a blog search result
            return [
                'id' => $result->id(),
                'title' => $result->get('title') ?? 'Untitled',
                'url' => $result->url(),
                'category' => $result->get('category'),
                'description' => $result->get('description'),
                'date' => ($limit == 0) ? $result->date()->format('M, j') : $result->date()->format('F j, Y'),
                'assets_field' =>  url('assets/' . ($assets_field[0] ?? $default_image)),
                'show_videos' => $result->get('show_videos'),
                'author'      => [
                    'name'   => $authors->get('title'),
                    'slug'   => $authors->slug(),
                    'avatar' => url($avatarAsset),
                    'author_id' => $authors->id(),
                ],
            ];
        }) 
        // Group the results by their category for easier categorization on the frontend
        ->groupBy('category');

        // Return JSON response with grouped results and list of categories
        return response()->json([
            'results' => $results,
            'categories' => $results->keys()->values(),
        ]);
    }
}
