<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {            

        // How to order it by title
        //$posts = Post::orderBy('title','desc')->get();

        // How to get individual record
        //$posts = Post::where('title','First Post w/ Tinker')->get();

        // Add Use at top DB to do straight mysql queries
        // $posts = DB::select('SELECT * FROM posts');

        // Load all info
        $posts = Post::all();
        
        // Load view (folder/index.blade.php)
        $posts = Post::orderBy('created_at','desc')->get();
        return view('posts.index')->with('posts', $posts);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle file upload
        if($request->hasFile('cover_image')){
            // Get filename with extention
            $filenameWithExt = $request->file('cover_image')->getClientOriginalImage();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getOriginalClientExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Images stored in web-inaccessable folder /storage/app/public
            // Need to create symlink to public folder
            // php artisan storage:link

        }else{
            // Set default image value
            $fileNameToStore = 'noimage.jpg';
        }
        
        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $path;

        $post->save();

        return redirect('/posts')->with('success', 'Post Created');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get from DB
        // holy fuck that is it?
        $post = Post::find($id);
        return view('posts.single')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Bring in the post
        $post = Post::find($id);
        // Check user ID for ownership

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'That doest\'t belong to you');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
        
        // Create Post
        $post = Post::find($id);
        // Check user ID for ownership

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'That doest\'t belong to you');
        }
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        // Check user ID for ownership

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'That doest\'t belong to you');
        }
        $post->delete();
        return redirect('/posts')->with('success', 'Post Deleted');
    }
}
