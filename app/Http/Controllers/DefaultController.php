<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('list')->with('page', 'list');
    }

    public function topIndex()
    {
        $query = DB::table('authors')
            ->join('books', 'authors.id', '=', 'books.author_id')
            ->join('book_ratings', 'books.id', '=', 'book_ratings.book_id')
            ->where('book_ratings.rating', '>', 5)
            ->select('authors.name', DB::raw('AVG(book_ratings.rating) as average_rating'), DB::raw('COUNT(book_ratings.id) as total_ratings'))
            ->groupBy('authors.id', 'authors.name')
            ->orderBy('total_ratings', 'desc')
            ->limit(10)
            ->get();

        $data = $query->map(function ($item, $index) {
            return [
                'name' => $item->name,
                'total_ratings' => $item->total_ratings,
            ];
        });

        return view('top')->with(['page' => 'top', 'data' => $data]);
    }

    public function ratingIndex()
    {
        return view('rating')->with('page', 'rating');
    }

    public function ratingSubmit(Request $request)
    {
        $validated = $request->validate([
            'book' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
        ]);

        DB::table('book_ratings')->insert([
            'book_id' => $validated['book'],
            'rating' => $validated['rating'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('index.rating')->with('success', 'Rating submitted successfully!');
    }

    public function dataBooks(Request $request)
    {
        $search = $request->input('search')['value'] ?? '';
        $length = $request->input('length');
        $start = $request->input('start', 0);

        $query = Book::with('author')
            ->with('category')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating');

        if (strlen($search) > 0) {
            $query->where('title', 'like', "%$search%")
                  ->orWhereHas('author', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
        }

        $totalRecords = Book::count();
        $filteredRecords = $query->count();

        $books = $query->skip($start)->take($length)->orderBy('ratings_avg_rating', 'desc')->get();

        $data = $books->map(function ($book, $index) use ($start) {
            return [
                'DT_RowIndex' => $start + $index + 1,
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author->name,
                'category' => $book->category->category,
                'average_rating' => number_format($book->ratings_avg_rating ?? 0, 2),
                'total_votes' => $book->ratings_count,
            ];
        });

        return response()->json([
            'draw' => $request->input('draw'),
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ]);
    }

    // select2
    public function listAuthors(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = Author::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $total = $query->count();

        $results = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn($author) => ['id' => $author->id, 'text' => $author->name]);

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total],
        ]);
    }

    public function listBooks(Request $request)
    {
        $search = $request->input('q');
        $authorId = $request->input('author_id');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = Book::query();

        if ($authorId) {
            $query->where('author_id', $authorId);
        }
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $total = $query->count();

        $results = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn($book) => ['id' => $book->id, 'text' => $book->title]);

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total],
        ]);
    }
}
