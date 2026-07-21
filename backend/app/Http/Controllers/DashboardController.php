<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get and display the dashboard statistics data.
     */
    public function index(Request $request)
    {
        // Mengambil 5 buku dengan peminjaman terbanyak
        $topBooks = Book::withCount('loans')
            ->orderByDesc('loans_count')
            ->limit(5)
            ->get();

        // Statistik jumlah peminjaman bulanan di tahun berjalan
        $monthlyLoans = Loan::select(
                DB::raw('MONTH(borrow_date) as month'), 
                DB::raw('COUNT(id) as total')
            )
            ->whereYear('borrow_date', now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Menampilkan anggota aktif berdasarkan peminjaman 30 hari terakhir
        $dateThreshold = now()->subDays(30);

        $activeUsers = Member::whereHas('loans', function ($query) use ($dateThreshold) {
            $query->where('borrow_date', '>=', $dateThreshold);
        })->withCount(['loans' => function ($query) use ($dateThreshold) {
            $query->where('borrow_date', '>=', $dateThreshold);
        }])->get();

        return response()->json([
            'popular_books'   => $topBooks,
            'loans_per_month' => $monthlyLoans,
            'active_members'  => $activeUsers,
        ]);
    }
}