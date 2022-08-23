<?php

namespace App\Http\Controllers\Invest\Admin;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\IvInvest;
use App\Models\IvLedger;
use App\Services\GraphData;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class InvestDashboardController extends Controller
{
    private $graphData;

    public function __construct(GraphData $graphData)
    {
        $this->graphData = $graphData;
    }

    public function index()
    {
        $stats['stats'] = IvLedger::statistics();
        $stats['activeInvest'] = [
            'plan' => IvInvest::isActive()->count(),
            'amount' => IvInvest::isActive()->sum('amount'),
            'profit' => IvInvest::isActive()->sum('profit'),
            'last' => [
                'plan' => IvInvest::isActive()->lastWeek()->count(),
                'amount' => IvInvest::isActive()->lastWeek()->sum('amount'),
                'profit' => IvInvest::isActive()->lastWeek()->sum('profit'),
            ],
            'since' => [
                'plan' => IvInvest::isActive()->fromLastWeek()->count(),
                'amount' => IvInvest::isActive()->fromLastWeek()->sum('amount'),
                'profit' => IvInvest::isActive()->fromLastWeek()->sum('profit'),
            ]
        ];

        $stats['activeInvest']['diff'] = [
            'plan' => to_dfp(IvInvest::isActive()->thisWeek()->count(), $stats['activeInvest']['last']['plan']),
            'amount' => to_dfp(IvInvest::isActive()->thisWeek()->sum('amount'), $stats['activeInvest']['last']['amount']),
            'profit' => to_dfp(IvInvest::isActive()->thisWeek()->sum('profit'), $stats['activeInvest']['last']['profit']),
        ];

        $this->graphData->set('total', 'term_start');
        $ivGraphData = IvInvest::selectRaw('SUM(amount) as total,term_start')
            ->isValid()
            ->lastDays(Carbon::now()->daysInMonth)
            ->groupBy(DB::RAW('CAST(term_start as DATE)'))
            ->get()
            ->toArray();

        $stats['ivGraph'] = $this->graphData->getDays($ivGraphData, Carbon::now()->daysInMonth)->flatten(false);
        $stats['ivGraphDate'] = [
            'start' => Carbon::now()->subDays(Carbon::now()->daysInMonth)->startOfDay()->tz(time_zone())->format('d M'),
            'end' => Carbon::now()->today()->tz(time_zone())->format('d M'),
        ];

        $topActivePlans = $this->calculatePlans(IvInvest::isActive()->get(), true);
        $stats['activePlans'] = $this->formatActivePlans($topActivePlans);

        $stats['topPlans'] = $this->calculatePlans(IvInvest::thisMonth()->get())->take(5);
        $groupedTopPlan = $stats['topPlans']->groupBy('term_start')
            ->map(function ($item, $key) {
                return ['term_start' => $key, 'count' => collect($item)->sum('count')];
            });

        $this->graphData->set('count', 'term_start', 'd M', 'quantity');
        $stats['topSchemeGraph'] = $this->graphData->getDays($groupedTopPlan)->get();
        $stats['recent'] = IvInvest::recent()->with('user')->take(5)->get();

        return view('investment.admin.dashboard', $stats);
    }

    private function calculatePlans($plans, $withUsers = false)
    {
        $temp = [];
        $colors = ['#9cabff', '#b8acff', '#ffa9ce', '#f9db7b'];
        $totalPlans = count($plans);
        foreach ($plans as $plan) {
            $schemeName = $plan->scheme['name'];
            if (array_key_exists($schemeName, $temp)) {
                $temp[$schemeName]['count']++;
                if ($withUsers && !array_key_exists($plan->user_id, $temp[$schemeName]['users'])) {
                    $temp[$schemeName]['user_count']++;
                }
            } else {
                $temp[$schemeName]['count'] = 1;
                if ($withUsers) {
                    $temp[$schemeName]['user_count'] = 1;
                    $temp[$schemeName]['users'][] = $plan->user_id;
                }
            }
            $temp[$schemeName]['percentage'] = (int)(($temp[$schemeName]['count'] / $totalPlans) * 100);
            $temp[$schemeName]['term_start'] = $plan->term_start->toDateString();
        }
        $data = collect($temp)->sortBy('percentage', SORT_REGULAR, true);
        return $data;
    }

    private function formatActivePlans($data)
    {
        $count = $data->count();
        $colors = ['#9cabff', '#b8acff', '#ffa9ce'];

        $final = [];

        if (!blank($data)) {
            $final = $data->take(3)->toArray();
            foreach ($final as $key => $value) {
                $final[$key]['color'] = array_shift($colors);
            }

            if ($count > 4) {
                $percentage = array_sum(array_column($final, 'percentage'));
                $final['Others']['color'] = '#f9db7b';
                $final['Others']['count'] = $count - 3;
                $final['Others']['percentage'] = 100 - $percentage;
            }
        }

        return $final;
    }
}
