<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charity;
use App\Models\Donation;
use Illuminate\Http\Request;

class CharityController extends Controller
{
    /**
     * Display a listing of charities.
     */
    public function index(Request $request)
    {
        $query = Charity::withCount('donations')
            ->withSum('donations', 'amountDonated');

        if ($request->has('search') && $request->search) {
            $query->where('charName', 'like', "%{$request->search}%");
        }

        if ($request->has('status') && $request->status) {
            $query->where('charStatus', $request->status);
        }

        $charities = $query->orderBy('charityID', 'desc')->paginate(10);

        return view('admin.charities.index', compact('charities'));
    }

    /**
     * Show the form for creating a new charity.
     */
    public function create()
    {
        return view('admin.charities.create');
    }

    /**
     * Store a newly created charity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'charName' => 'required|string|max:100',
            'charDesc' => 'nullable|string',
            'charConDetails' => 'nullable|string|max:200',
            'charStatus' => 'required|in:available,unavailable',
        ]);

        Charity::create($validated);

        return redirect()->route('admin.charities.index')
            ->with('success', 'Charity created successfully.');
    }

    /**
     * Display the specified charity.
     */
    public function show(Charity $charity)
    {
        $charity->load(['donations.order.buyer']);
        $totalDonations = $charity->donations()->sum('amountDonated');
        return view('admin.charities.show', compact('charity', 'totalDonations'));
    }

    /**
     * Show the form for editing the specified charity.
     */
    public function edit(Charity $charity)
    {
        return view('admin.charities.edit', compact('charity'));
    }

    /**
     * Update the specified charity.
     */
    public function update(Request $request, Charity $charity)
    {
        $validated = $request->validate([
            'charName' => 'required|string|max:100',
            'charDesc' => 'nullable|string',
            'charConDetails' => 'nullable|string|max:200',
            'charStatus' => 'required|in:available,unavailable',
        ]);

        $charity->update($validated);

        return redirect()->route('admin.charities.index')
            ->with('success', 'Charity updated successfully.');
    }

    /**
     * Remove the specified charity.
     */
    public function destroy(Charity $charity)
    {
        if ($charity->donations()->count() > 0) {
            return redirect()->route('admin.charities.index')
                ->with('error', 'Cannot delete charity with existing donations.');
        }

        $charity->delete();

        return redirect()->route('admin.charities.index')
            ->with('success', 'Charity deleted successfully.');
    }

    /**
     * Display donation summary dashboard.
     */
    public function donationSummary()
    {
        $totalDonations = Donation::sum('amountDonated');
        $totalDonationCount = Donation::count();
        $charities = Charity::withCount('donations')
            ->withSum('donations', 'amountDonated')
            ->orderByDesc('donations_sum_amount_donated')
            ->get();

        // Monthly donation data for the current year
        $monthlyDonations = Donation::selectRaw('MONTH(dateDonated) as month, SUM(amountDonated) as total')
            ->whereYear('dateDonated', date('Y'))
            ->groupByRaw('MONTH(dateDonated)')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.charities.summary', compact('totalDonations', 'totalDonationCount', 'charities', 'monthlyDonations'));
    }

    /**
     * Display charity impact reports.
     */
    public function impactReports(Request $request)
    {
        $query = Donation::with(['charity', 'order.buyer']);

        if ($request->has('charity') && $request->charity) {
            $query->where('charityID', $request->charity);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->where('dateDonated', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->where('dateDonated', '<=', $request->to_date . ' 23:59:59');
        }

        $donations = $query->orderBy('dateDonated', 'desc')->paginate(15);
        $charities = Charity::all();

        // Summary stats
        $filteredTotal = $query->sum('amountDonated');
        $filteredCount = $query->count();

        return view('admin.charities.reports', compact('donations', 'charities', 'filteredTotal', 'filteredCount'));
    }
}
