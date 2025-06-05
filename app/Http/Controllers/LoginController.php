<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Handle the login attempt for authors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|array
    */
    public function login(Request $request)
    {
        // Extract only 'email' and 'password' from the request
        $data = request()->only(['email', 'password']);

        // Validate the input data
        $validator = Validator::make($data, [
            'email'    => 'required|email',  // Email must be valid
            'password' => 'required',        // Password is required
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return [
                'status' => 'validation',
                'message' => 'Invalid credentials',
                'errors' => $validator->errors()
            ];
        }

        // Search for an author entry in the 'authors' collection using the provided email
        $author = Entry::query()
            ->where('collection', 'authors')
            ->where('email_address', $request->email)
            ->first();

        // If the author doesn't exist, return error message    
        if (!$author) {
           return response()->json([
                'status' => 'error',
                'message' => 'It looks like this account doesn’t exist. You can go ahead and register!',
            ]);
        }

        // Check if the password matches
        // Note: Replace this with Hash::check() for secure password storage and comparison

        if ($request->password == $author->get('password')) {
            // Set session variables after successful login
            session(['author_logged_in' => true]);
            session(['author_id' => $author->id()]);
            session(['author_slug' => $author->slug()]);
            session(['author_email' => $author->get('email')]);
            session(['author_job_title' => $author->get('job_title')]);
            session(['author_name' => $author->get('title')]);
            session(['author_avatar' => $author->get('avatar')[0]]);
            session(['author_background_image' => isset($author->get('background_image')[0]) ? $author->get('background_image')[0] : '']);
            session(['author_following' => $author->get('followers')]);

            

            // Return success response
            return [
                'status' => 'success',
                'message' => 'Login successful! Glad to have you back'
            ];
        }

        // If password does not match, return error message
        return [
            'status' => 'error',
            'message' => 'The password you entered is incorrect.',
        ];
    

    }

    /**
     * Handle the author logout process.
     *
     * @return \Illuminate\Http\RedirectResponse|array
    */
    public function logout()
    {   
        // Forget all author-related session variables
        session()->forget(['author_logged_in', 'author_id', 'author_email', 'author_name', 'author_avatar']);

        // Redirect to login page with a logout success message
        return redirect('/login')->with('success', 'You’ve been logged out. See you soon!');
    }
}
