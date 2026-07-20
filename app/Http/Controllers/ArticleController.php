<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with('author');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        // Filter Category
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        // Filter Status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $allowedSorts = ['title', 'category', 'status', 'featured', 'views', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $articles = $query->paginate(10)->withQueryString();

        // Statistics
        $totalArticles = Article::count();
        $publishedCount = Article::where('status', 'Published')->count();
        $draftCount = Article::where('status', 'Draft')->count();
        $featuredCount = Article::where('featured', true)->count();

        return view('admin.articles.index', compact(
            'articles', 'totalArticles', 'publishedCount', 'draftCount', 'featuredCount', 'sortBy', 'sortDir'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'status' => 'required|string|in:Draft,Published',
            'featured' => 'required|string|in:Yes,No',
            'thumbnail' => 'nullable|string', // Base64
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Article::create([
            'title' => $request->title,
            'slug' => $slug,
            'summary' => $request->summary,
            'content' => $request->content,
            'category' => $request->category,
            'author_id' => auth()->id(),
            'status' => $request->status,
            'featured' => $request->featured === 'Yes',
            'thumbnail' => $request->input('thumbnail'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'views' => 0,
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        // Increment view count simple trace
        $article->increment('views');

        // Related articles by category
        $relatedArticles = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->where('status', 'Published')
            ->limit(3)
            ->get();

        return view('admin.articles.show', compact('article', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'status' => 'required|string|in:Draft,Published',
            'featured' => 'required|string|in:Yes,No',
            'thumbnail' => 'nullable|string', // Base64
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $article->title = $request->title;
        $article->slug = $slug;
        $article->summary = $request->summary;
        $article->content = $request->content;
        $article->category = $request->category;
        $article->status = $request->status;
        $article->featured = $request->featured === 'Yes';
        
        if ($request->filled('thumbnail')) {
            $article->thumbnail = $request->input('thumbnail');
        }

        $article->meta_title = $request->meta_title;
        $article->meta_description = $request->meta_description;
        $article->meta_keywords = $request->meta_keywords;
        
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus');
    }
}
