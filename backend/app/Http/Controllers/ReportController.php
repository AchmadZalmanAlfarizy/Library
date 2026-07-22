<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Generate book report.
     */
    public function bookReport(): JsonResponse
    {
        // Langsung mengembalikan response tanpa variabel perantara
        return response()->json(
            Book::with('category')->get()
        );
    }

    /**
     * Generate loan report.
     */
    public function loanReport(Request $request): JsonResponse
    {
        // Menggunakan sintaks array untuk validasi agar lebih mudah dibaca
        $validator = Validator::make($request->all(), [
            'period'     => ['nullable', 'in:daily,weekly,monthly'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date'   => ['nullable', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $period = $request->query('period', 'daily');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Mengganti Switch-Case dengan metode when() bawaan Eloquent
        // Metode ini lebih aman dan menyatu dengan Query Builder
        $loans = Loan::with(['book', 'member'])
            ->when($period === 'daily', function ($query) use ($startDate) {
                $date = $startDate ?: now()->toDateString();
                $query->whereDate('borrow_date', $date);
            })
            ->when($period === 'weekly', function ($query) use ($startDate, $endDate) {
                $start = $startDate ?: now()->startOfWeek()->toDateString();
                $end   = $endDate ?: now()->endOfWeek()->toDateString();
                $query->whereBetween('borrow_date', [$start, $end]);
            })
            ->when($period === 'monthly', function ($query) use ($startDate, $endDate) {
                $start = $startDate ?: now()->startOfMonth()->toDateString();
                $end   = $endDate ?: now()->endOfMonth()->toDateString();
                $query->whereBetween('borrow_date', [$start, $end]);
            })
            ->get();

        return response()->json($loans);
    }
}