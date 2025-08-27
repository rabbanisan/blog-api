<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        // Mengambil semua post yang sudah di-publish, diurutkan dari yang terbaru
        $posts = Post::whereNotNull('published_at')
            ->with(['user', 'category'])
            ->latest()
            ->get();

        return response()->json($posts);
    }

    public function show(Post $post)
    {
        // Menggunakan Route Model Binding untuk mengambil post berdasarkan slug
        $post->load(['user', 'category']);
        return response()->json($post);
    }

    public function store(Request $request)
    {
        // Periksa autentikasi
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048', // Maksimal 2MB
            'published_at' => 'nullable|date',
        ]);

        // Siapkan data post
        $postData = [
            'user_id' => Auth::id(), // Otomatis mengisi user_id dari pengguna yang login
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? now(),
        ];

        // Tangani upload gambar jika ada
        if ($request->hasFile('image')) {
            $postData['image'] = $request->file('image')->store('posts', 'public');
        }

        try {
            // Buat post
            $post = Post::create($postData);

            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post->load(['user', 'category']),
            ], 201);
        } catch (\Throwable $e) {
            // Error 1062 = Duplicate entry (MySQL)
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry: data already exists',
                    'error'   => $e->getMessage()
                ], 409); // 409 Conflict
            }

            // Error database lain
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Post $post)
    {
        // Cek otorisasi: hanya admin atau pemilik post (author) yang bisa update
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // Siapkan data untuk update
        $postData = [
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? $post->published_at,
        ];

        // Tangani upload gambar jika ada
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $postData['image'] = $request->file('image')->store('posts', 'public');
        }

        // Update post
        $post->update($postData);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load(['user', 'category']),
        ]);
    }

    public function destroy(Post $post)
    {
        // Cek otorisasi: hanya admin atau pemilik post (author) yang bisa hapus
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus gambar jika ada
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // Hapus post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
