<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\PostService;

class PostController extends Controller
{
    private const RSS_URL = 'https://lifehacker.com/feed/rss';

    public function __construct(protected PostService $postService)
    {}

    /**
     * @return mixed
     */
    public function index(): JsonResponse
    {
        try {
            $posts = Post::paginate(3);
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching posts.'], 500);
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $posts = $this->postService->search($request);
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while searching for posts.'], 500);
        }
    }


    /**
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function store(PostRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $post = Post::create($validatedData);
            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the post.'], 500);
        }
    }


    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        try {
            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the post.'], 500);
        }
    }


    /**
     * @param PostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $validatedData = $request->validated();
        $post->update($validatedData);

        return response()->json($post);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }


    /**
     * Parse rss to db
     * @return JsonResponse
     */
    public function parseRss(): JsonResponse
    {
        try {
            $data = $this->postService->parseRss(self::RSS_URL);

            DB::beginTransaction();
            DB::table('posts')->insert($data);
            DB::commit();

            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while parsing and saving RSS feed.'], 500);
        }
    }
}
