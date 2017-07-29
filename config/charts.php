<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default settings for charts
    |--------------------------------------------------------------------------
    */

    'default' => [
        'type'          => 'line',
        'library'       => 'google',
        'element_label' => 'Element',
        'title'         => 'My chart',
        'height'        => 400,
        'width'         => 500,
        'responsive'    => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets required by the libraries
    |--------------------------------------------------------------------------
    */

    'assets' => [
        'global' => [
            'scripts' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',
            ],
        ],

        'canvas-gauges' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/gh/Mikhus/canvas-gauges@v2.0.9/gauge.min.js',
            ],
        ],

        'chartist' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.js',
            ],
            'styles' => [
                'https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.css',
            ],
        ],

        'chartjs' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/gh/chartjs/Chart.js@v2.4.0/dist/Chart.min.js',
            ],
        ],

        'fusioncharts' => [
            'scripts' => [
                'https://static.fusioncharts.com/code/latest/fusioncharts.js',
                'https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js',
            ],
        ],

        'google' => [
            'scripts' => [
                'https://www.google.com/jsapi',
                'https://www.gstatic.com/charts/loader.js',
                "google.charts.load('current', {'packages':['corechart', 'gauge', 'geochart', 'bar', 'line']})",
            ],
        ],

        'highcharts' => [
            'styles' => [
                'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.4/css/highcharts.css',
            ],
            'scripts' => [
                'https://cdn.jsdelivr.net/highcharts/5.0.2/highcharts.js',
                'https://cdn.jsdelivr.net/highcharts/5.0.2/modules/exporting.js',
                'https://cdn.jsdelivr.net/highcharts/5.0.2/modules/map.js',
                'https://cdn.jsdelivr.net/highcharts/5.0.2/modules/data.js',
                'https://code.highcharts.com/mapdata/custom/world.js',
            ],
        ],

        'justgage' => [
            'scripts' => [
                'http://cdn.jsdelivr.net/gh/DmitryBaranovskiy/raphael@v2.2.7/raphael.min.js',
                'https://cdn.jsdelivr.net/gh/sawpcdn/justgage@1.2.2/justgage.min.js',
            ],
        ],

        'morris' => [
            'styles' => [
                'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
            ],
            'scripts' => [
                'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
                'https://cdn.jsdelivr.net/morris.js/0.5.1/morris.min.js',
            ],
        ],

        'plottablejs' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/d3js/4.8.0/d3.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.min.js',
            ],
            'styles' => [
                'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.css',
            ],
        ],

        'progressbarjs' => [
            'scripts' => [
                'https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js',
            ],
        ],

        'c3' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/d3js/3.5.5/d3.min.js',
                'https://cdn.jsdelivr.net/c3/0.4.11/c3.min.js',
            ],
            'styles' => [
                'https://cdn.jsdelivr.net/c3/0.4.11/c3.min.css',
            ],
        ],
    ],
];
