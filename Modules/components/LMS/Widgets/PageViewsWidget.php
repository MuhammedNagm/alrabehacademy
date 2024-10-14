<?php

namespace Modules\Components\LMS\Widgets;

use Analytics;
use Spatie\Analytics\Period;

class PageViewsWidget
{

    function __construct()
    {
    }

    function run($args)
    {
        try {
            $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30));
            $visitors = [];
            $pageviews = [];
            $totalViews = ['labels' => []];

            foreach ($analyticsData as $k => $item) {
                array_push($totalViews['labels'], $item['date']->format('d M'));

                array_push($visitors, $item['visitors']);
                array_push($pageviews, $item['pageViews']);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
