<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AI\TrendForecastService;
use App\Services\AI\BehavioralAnalyticsService;

class AIController extends Controller
{
    /**
     * Trend Forecasting for Handmade Products
     */
    public function trendForecast()
    {
        $service = new TrendForecastService();

        $categoryTrends = $service->getCategoryTrends();
        $emergingProducts = $service->getEmergingProducts();
        $seasonalPatterns = $service->getSeasonalPatterns();
        $demandForecast = $service->getDemandForecast();

        return view('admin.ai.trends', compact(
            'categoryTrends',
            'emergingProducts',
            'seasonalPatterns',
            'demandForecast'
        ));
    }

    /**
     * Behavioral Analytics for User Engagement
     */
    public function behavioralAnalytics()
    {
        $service = new BehavioralAnalyticsService();
        $analytics = $service->getAnalytics();

        return view('admin.ai.behavioral', compact('analytics'));
    }
}
