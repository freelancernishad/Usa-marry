<?php

namespace App\Http\Controllers\Api\Admin\DashboardMetrics;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Display the dashboard metrics.
     *
     * @return JsonResponse
     */


     public function index(Request $request)
     {
         $year = $request->year ?? now()->year;
         $week = $request->week ?? 'current';

         $fromDate = $request->from_date;


         $toDate = isset($request->to_date) ? $request->to_date : $fromDate;





         // Total users
         $totalUsers = User::count();

         // New registrations in the last 7 days
         $newRegistrations = User::where('created_at', '>=', now()->subDays(7))->count();

         // Subscribed users
         $subscribedUsers = UserPackage::where('started_at', '<=', Carbon::now())
         ->where('ends_at', '>=', Carbon::now())
         ->with('user')  // Eager load the related user
         ->get()
         ->pluck('user')
         ->unique('id'); // Ensure unique users in case of multiple packages

        // To get the count of subscribed users
        $subscribedUserCount = $subscribedUsers->count();

         // Pending verifications
         $pendingVerifications = User::whereNull('email_verified_at')->count();

         // Package revenue data (monthly, yearly, weekly)
         $packageRevenueData = getPackageRevenueData($year, $week);

         // Total revenue by package
         $totalRevenueByPackage = $packageRevenueData['total_revenue_per_package'];

         // Weekly package revenue max value
         $weeklyPackageRevenueMax = $packageRevenueData['weekly_package_revenue_max'];

         // Calculate revenue by package within a date range if provided
         $revenueByDate = [];
         if ($fromDate) {
             $revenueByDate = Package::all()->map(function ($package) use ($fromDate, $toDate) {
                 // Query to get total revenue for the package within the specified date range or day
                 $totalAmountQuery = Payment::where('payable_type', 'Package')
                     ->where('payable_id', $package->id)
                     ->completed(); // Use the 'completed' scope to filter by completed payments

                 // Check if 'toDate' is undefined and apply appropriate date filter
                 if ($toDate === 'undefined') {
                     $fromDate = date("Y-m-d", strtotime($fromDate));
                     $totalAmountQuery->whereDate('paid_at', $fromDate);
                 } else {
                     $totalAmountQuery->whereBetween('paid_at', [$fromDate, $toDate]);
                 }

                 // Sum the total amount
                 $totalAmount = $totalAmountQuery->sum('amount');

                 return [
                     'name' => $package->package_name,
                     'total_amount' => (int) $totalAmount, // Cast to integer
                 ];
             })->toArray();
         }


        // Calculate total revenue across all packages
        $totalRevenue = Payment::where('payable_type', 'Package')
            ->completed()  // Use the 'completed' scope for completed payments
            ->sum('amount');


         return response()->json([
             'total_users' => $totalUsers,
             'new_registrations' => $newRegistrations,
             'subscribed_users' => $subscribedUserCount,
             'pending_verifications' => $pendingVerifications,
             'package_revenue' => $packageRevenueData['monthly_package_revenue'],
             'package_revenue_max' => $packageRevenueData['monthly_package_revenue_max'],
             'total_revenue_per_package' => $totalRevenueByPackage,
             'yearly_package_revenue' => $packageRevenueData['yearly_package_revenue'],
             'weekly_package_revenue' => $packageRevenueData['weekly_package_revenue'],
             'weekly_package_revenue_max' => $weeklyPackageRevenueMax,
             'revenue_by_date' => $revenueByDate, // Revenue by package within date range
             'total_revenue' => (int) $totalRevenue, // Total revenue across all packages
         ]);
     }





    public function adminDashboardOverview()
    {
        // --- Total Users and Growth ---
        $totalUsers = User::count();

        $startLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $lastMonthUsers = User::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();

        $totalUsersGrowth = $lastMonthUsers > 0
            ? round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : 100;

        // --- Gender Distribution ---
        $genderCounts = User::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        $maleCount = $genderCounts['Male'] ?? 0;
        $femaleCount = $genderCounts['Female'] ?? 0;
        $otherCount = $genderCounts['Other'] ?? 0;

        $malePercent = $totalUsers ? round(($maleCount / $totalUsers) * 100, 1) : 0;
        $femalePercent = $totalUsers ? round(($femaleCount / $totalUsers) * 100, 1) : 0;
        $otherPercent = $totalUsers ? round(($otherCount / $totalUsers) * 100, 1) : 0;

        // --- New Registrations ---
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfWeek = Carbon::now()->startOfWeek();

        $newThisMonth = User::where('created_at', '>=', $startOfMonth)->count();
        $newThisWeek = User::where('created_at', '>=', $startOfWeek)->count();

        $dailyAvg = $newThisMonth ? intval($newThisMonth / Carbon::now()->day) : 0;

        // --- Pending Approvals ---
        $pendingTotal = User::where('account_status', 'pending')->count();


        // --- Active (Successful) Subscriptions & Plans Breakdown ---
        $activeSubscriptions = Subscription::where('status', 'Success')->count();

        $activeLastMonth = Subscription::where('status', 'Success')
            ->whereBetween('created_at', [$startLastMonth, $endLastMonth])
            ->count();

        $subscriptionGrowth = $activeLastMonth > 0
            ? round((($activeSubscriptions - $activeLastMonth) / $activeLastMonth) * 100, 1)
            : 100;

        $plansCount = Subscription::where('status', 'Success')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->select('plans.name', DB::raw('count(*) as count'))
            ->groupBy('plans.name')
            ->pluck('count', 'plans.name')
            ->toArray();

        $basicCount = $plansCount['Basic'] ?? 0;
        $premiumCount = $plansCount['Premium'] ?? 0;
        $enterpriseCount = $plansCount['Enterprise'] ?? 0;

        // --- Revenue Summary ---
        $totalRevenue = Subscription::where('status', 'Success')->sum('final_amount');

        $revenueThisMonth = Subscription::where('status', 'Success')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('final_amount');

        $revenueLastMonth = Subscription::where('status', 'Success')
            ->whereBetween('created_at', [$startLastMonth, $endLastMonth])
            ->sum('final_amount');

        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 100;

        // $commissionsRevenue = DB::table('commissions')->sum('amount') ?? 0;
        // $adsRevenue = DB::table('advertisements')->sum('amount') ?? 0;

        $overview = [
            'total_users' => $totalUsers,
            'total_users_growth' => $totalUsersGrowth,
            'gender_distribution' => [
                'male' => ['count' => $maleCount, 'percentage' => $malePercent],
                'female' => ['count' => $femaleCount, 'percentage' => $femalePercent],
                'other' => ['count' => $otherCount, 'percentage' => $otherPercent],
            ],
            'new_registrations' => [
                'this_month' => $newThisMonth,
                'this_week' => $newThisWeek,
                'daily_avg' => $dailyAvg,
            ],
            'pending_approvals' => [
                'total' => $pendingTotal,
            ],
            'active_subscriptions' => [
                'total' => $activeSubscriptions,
                'growth' => $subscriptionGrowth,
                'plans' => [
                    'basic' => $basicCount,
                    'premium' => $premiumCount,
                    'enterprise' => $enterpriseCount,
                    'enterprise' => $enterpriseCount,
                ],
            ],
            'revenue_summary' => [
                'total' => round($totalRevenue, 2),
                'growth' => $revenueGrowth,
                'this_month' => round($revenueThisMonth, 2),
                'last_month' => round($revenueLastMonth, 2),
                'sources' => [
                    'subscriptions' => round($totalRevenue, 2),
                    // 'commissions' => round($commissionsRevenue, 2),
                    // 'advertisements' => round($adsRevenue, 2),
                ],
            ],
        ];

        return response()->json($overview);
    }




}
