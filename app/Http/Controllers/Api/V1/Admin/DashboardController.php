<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        return response()->json([
            'books_info' => $this->booksInfo(),
            'loans_info' => $this->loansInfo(),
        ]);

    }
    public function booksInfo(): JsonResponse
    {
        $cacheKey = 'books_info';

        $booksInfo = Cache::remember($cacheKey, now()->addHour(), function () {
            $booksAvailable = Book::where('status', 'available')->count();
            $booksUnavailable = Book::where('status', 'unavailable')->count();

            return [
                'total_books_available' => $booksAvailable,
                'total_books_unavailable' => $booksUnavailable,
            ];
        });

        return response()->json($booksInfo);
    }

    public function loansInfo():JsonResponse
    {
        $cacheKey = 'loans_info';

        $loansInfo = Cache::remember($cacheKey, now()->addHour(), function () {
            $loansOpen = Loan::where('status', 'open')->count();
            $loansClosed = Loan::where('status', 'closed')->count();

            return [
                'total_loans_open' => $loansOpen,
                'total_loans_closed' => $loansClosed,
            ];
        });

        return response()->json($loansInfo);

    }
}
