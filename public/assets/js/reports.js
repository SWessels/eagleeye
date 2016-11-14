

$(function() {
    $('.nav-tabs a[href="#tab_7days"]').click(function(){
        $.ajax({
            url: '/reports/get_7days_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0) {

                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_7days'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series4').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_7days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_7days').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_7days').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_7days').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average daily sales.</span></strong>');
                $('#orders_placed_7days').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_7days').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_7days').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last 7 days.');
            }

        });

    });

    $('.nav-tabs a[href="#tab_year"]').click(function(){

    $.ajax({
        url: '/reports/get_year_by_date',
        dataType: 'json',
        type: 'GET',
        success: function (response) {
            if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0) {


                var main_chart;
                var drawGraph = function (highlight) {


                    var series = [
                        {
                            label: "Purchased Items",
                            data: response.order_item_count,
                            color: '#969492',
                            bars: {

                                fillColor: '#969492',
                                fill: true,
                                show: true,
                                lineWidth: 0,
                                barWidth: 2419200000 * 0.5,
                                align: 'center'
                            },
                            shadowSize: 0,
                            hoverable: true
                        },
                        {
                            label: "Orders Count",
                            data: response.order_count,
                            color: '#AD6823',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            hoverable: true
                        },
                        {
                            label: "Order Average Amount",
                            data: response.order_average_amount,
                            color: '#6fdcde',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            hoverable: true
                        },


                        {
                            label: "Gross Sale",
                            data: response.order_amounts,
                            yaxis: 2,
                            color: '#25b37f',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;&nbsp;"
                        },
                        {
                            label: "Net Sale",
                            data: response.order_net_amount,
                            yaxis: 2,
                            color: '#78f0aa',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;&nbsp;"
                        },
                        {
                            label: "Refund Amount",
                            data: response.refund_amount,
                            yaxis: 2,
                            color: '#e74c3c',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;"
                        },
                    ];

                    if (highlight !== 'undefined' && series[highlight]) {
                        highlight_series = series[highlight];

                        highlight_series.color = '#9c5d90';

                        if (highlight_series.bars) {
                            highlight_series.bars.fillColor = '#9c5d90';
                        }

                        if (highlight_series.lines) {
                            highlight_series.lines.lineWidth = 5;
                        }
                    }

                    main_chart = jQuery.plot(
                        jQuery('#dateBy_year'),
                        series,
                        {
                            legend: {
                                show: false
                            },
                            tooltip: {
                                show: true,
                                content: "y: %y"
                            },
                            grid: {
                                color: '#aaa',
                                borderColor: 'transparent',
                                borderWidth: 1,
                                hoverable: true
                            },
                            xaxes: [{
                                color: '#aaa',
                                position: "bottom",
                                tickColor: 'transparent',
                                mode: "time",
                                timeformat: "%b",
                                monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                tickLength: 1,
                                minTickSize: [1, "month"],
                                font: {
                                    color: "#aaa"
                                }
                            }],
                            yaxes: [
                                {
                                    min: 0,
                                    minTickSize: 1,
                                    tickDecimals: 0,
                                    color: '#d4d9dc',
                                    font: {color: "#aaa"}
                                },
                                {
                                    position: "right",
                                    min: 0,
                                    tickDecimals: 2,
                                    alignTicksWithAxis: 1,
                                    color: 'transparent',
                                    font: {color: "#aaa"}
                                }
                            ],
                        }
                    );


                }

                drawGraph();
                jQuery('.highlight_series').hover(function () {
                        // alert('h');
                        var h = jQuery(this).data('series');

                        drawGraph(jQuery(this).data('series'));
                    },
                    function () {
                        drawGraph();
                    });
            }
            else{
                $('#dateBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
            }

            $('#gross_sale').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
            $('#net_gross_sale').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
            $('#average_monthly_sale').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average monthly sales.</span></strong>');
            $('#orders_placed').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
            $('#total_item').html('<strong><span style="color:#969492 " class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
            $('#total_refund').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

        },
        error: function (response) {
            alert('Error: displaying in graph of current year.');
        }

    });
    });
    
    $('.nav-tabs a[href="#tab_7days"]').trigger( "click" );

    $('.nav-tabs a[href="#tab_lmonth"]').click(function(){
        $.ajax({
            url: '/reports/get_lmonth_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {

                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_lmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series1').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_lmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }
                $('#gross_sale_lmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount)+ '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_lmonth').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;' +checkAmount(response.total_order_net_amount)+ '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_lmonth').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;' + checkAmount(response.total_average) + '<br> Average daily sales.</span></strong>');
                $('#orders_placed_lmonth').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_lmonth').html('<strong><span  style="color:#969492 " class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_lmonth').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last month.');
            }

        });

    });

    $('#lmonth_per_day').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_lmonth_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {

                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_lmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series1').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_lmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }
                $('#gross_sale_lmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount)+ '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_lmonth').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;' +checkAmount(response.total_order_net_amount)+ '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_lmonth').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;' + checkAmount(response.total_average) + '<br> Average daily sales.</span></strong>');
                $('#orders_placed_lmonth').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_lmonth').html('<strong><span  style="color:#969492 " class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_lmonth').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last month.');
            }

        });

    });

    $('#lmonth_per_week').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        
        $.ajax({
            url: '/reports/get_lmonth_by_date_perWeek',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {

                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_lmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 7,
                                    minTickSize: [7, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series1').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_lmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }
                $('#gross_sale_lmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount)+ '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_lmonth').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;' +checkAmount(response.total_order_net_amount)+ '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_lmonth').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;' + checkAmount(response.total_average) + '<br> Average weekly sales.</span></strong>');
                $('#orders_placed_lmonth').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_lmonth').html('<strong><span  style="color:#969492 " class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_lmonth').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last month.');
            }

        });

    });
    
    $('.nav-tabs a[href="#tab_cmonth"]').click(function(){
        $.ajax({
            url: '/reports/get_cmonth_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_cmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series2').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_cmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');

                }

                $('#gross_sale_cmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_cmonth').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_cmonth').html('<strong><span  style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average daily sales.</span></strong>');
                $('#orders_placed_cmonth').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_cmonth').html('<strong><span  style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_cmonth').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current month.');
            }

        });

    });

    $('#cmonth_per_day').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_cmonth_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_cmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series2').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_cmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');

                }

                $('#gross_sale_cmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_cmonth').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_cmonth').html('<strong><span  style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average daily sales.</span></strong>');
                $('#orders_placed_cmonth').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_cmonth').html('<strong><span  style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_cmonth').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current month.');
            }

        });

    });

    $('#cmonth_per_week').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_cmonth_by_date_perWeek',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_cmonth'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [7, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series2').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_cmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');

                }

                $('#gross_sale_cmonth').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_cmonth').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_cmonth').html('<strong><span  style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average weekly sales.</span></strong>');
                $('#orders_placed_cmonth').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_cmonth').html('<strong><span  style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_cmonth').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current month.');
            }

        });

    });

    $('.nav-tabs a[href="#tab_14days"]').click(function(){
        $.ajax({
            url: '/reports/get_14days_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {

                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_14days'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series3').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });

                }
                else{
                    $('#dateBy_14days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_14days').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount) + '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_14days').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_net_amount) + '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_14days').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;' +checkAmount(response.total_average)+ '<br> Average daily sales.</span></strong>');
                $('#orders_placed_14days').html('<strong><span style="color:#AD6823" class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_14days').html('<strong><span style="color:#969492" class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_14days').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last 14 days.');
            }

        });

    });

    $('#14days_per_day').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_14days_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {

                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_14days'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series3').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });

                }
                else{
                    $('#dateBy_14days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_14days').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount) + '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_14days').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_net_amount) + '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_14days').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;' +checkAmount(response.total_average)+ '<br> Average daily sales.</span></strong>');
                $('#orders_placed_14days').html('<strong><span style="color:#AD6823" class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_14days').html('<strong><span style="color:#969492" class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_14days').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last 14 days.');
            }

        });

    });

    $('#14days_per_week').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_14days_by_date_perWeek',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {

                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_14days'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 7,
                                    minTickSize: [7, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series3').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });

                }
                else{
                    $('#dateBy_14days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_14days').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_amount) + '<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_14days').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;' + checkAmount(response.total_order_net_amount) + '<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_14days').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;' +checkAmount(response.total_average)+ '<br> Average weekly sales.</span></strong>');
                $('#orders_placed_14days').html('<strong><span style="color:#AD6823" class="amount">&nbsp;' + checkCount(response.total_order_count) + '<br> Orders placed.</span></strong>');
                $('#total_item_14days').html('<strong><span style="color:#969492" class="amount">&nbsp;' + checkCount(response.total_order_item_count) + '<br> Items purchased.</span></strong>');
                $('#total_refund_14days').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of last 14 days.');
            }

        });

    });
    


    $('.nav-tabs a[href="#tab_yesterday"]').click(function(){
        $.ajax({
            url: '/reports/get_yesterday_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0)
                {

                    var main_chart;
                    var drawGraph = function (highlight) {

                        // alert(highlight);
                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_yesterday'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );
                    }
                    drawGraph();
                    jQuery('.highlight_series5').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_yesterday').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div> ');
                }
                $('#gross_sale_yesterday').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_yesterday').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_yesterday').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average sale.</span></strong>');
                $('#orders_placed_yesterday').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_yesterday').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_yesterday').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');


            },
            error: function (response) {
                alert('Error: displaying in graph of yesterday.');
            }

        });

    });

    $('.nav-tabs a[href="#tab_today"]').click(function(){
        $.ajax({
            url: '/reports/get_today_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                   || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0){
                    var main_chart;
                    var drawGraph = function( highlight ) {

                    // alert(highlight);
                    var series = [
                        {
                            label: "Purchased Items",
                            data: response.order_item_count,
                            color: '#969492',
                            bars: {

                                fillColor: '#969492',
                                fill: true,
                                show: true,
                                lineWidth: 0,
                                barWidth: 86400000 * 0.5,
                                align: 'center'
                            }, shadowSize: 0,
                            hoverable: true
                        },
                        {
                            label: "Orders Count",
                            data: response.order_count,
                            color: '#AD6823',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            hoverable: true
                        },
                        {
                            label: "Order Average Amount",
                            data: response.order_average_amount,
                            /*yaxis: 2,*/
                            color: '#6fdcde',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            hoverable: true
                        },


                        {
                            label: "Gross Sale",
                            data: response.order_amounts,
                            yaxis: 2,
                            color: '#25b37f',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;&nbsp;"
                        },
                        {
                            label: "Net Sale",
                            data: response.order_net_amount,
                            yaxis: 2,
                            color: '#78f0aa',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;&nbsp;"
                        },
                        {
                            label: "Refund Amount",
                            data: response.refund_amount,
                            yaxis: 2,
                            color: '#e74c3c',
                            points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                            lines: {show: true, lineWidth: 2, fill: false},
                            shadowSize: 0,
                            prepend_tooltip: "&euro;"
                        },
                    ];
                    //  console.log(series[ highlight ]);
                    if ( highlight !== 'undefined' && series[ highlight ] ) {
                        highlight_series = series[ highlight ];

                        highlight_series.color = '#9c5d90';

                        if ( highlight_series.bars ) {
                            highlight_series.bars.fillColor = '#9c5d90';
                        }

                        if ( highlight_series.lines ) {
                            highlight_series.lines.lineWidth = 5;
                        }
                    }

                    main_chart = jQuery.plot(
                        jQuery('#dateBy_today'),
                        series,
                        {
                            legend: {
                                show: false
                            },
                            tooltip: {
                                show: true,
                                content: "y: %y"
                            },
                            grid: {
                                color: '#aaa',
                                borderColor: 'transparent',
                                borderWidth: 1,
                                hoverable: true
                            },
                            xaxes: [ {
                                color: '#aaa',
                                position: "bottom",
                                tickColor: 'transparent',
                                mode: "time",
                                timeformat: "%d %b",
                                monthNames: ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],
                                tickLength: 1,
                                minTickSize: [1, "day"],
                                font: {
                                    color: "#aaa"
                                }
                            } ],
                            yaxes: [
                                {
                                    min: 0,
                                    minTickSize: 1,
                                    tickDecimals: 0,
                                    color: '#d4d9dc',
                                    font: { color: "#aaa" }
                                },
                                {
                                    position: "right",
                                    min: 0,
                                    tickDecimals: 2,
                                    alignTicksWithAxis: 1,
                                    color: 'transparent',
                                    font: { color: "#aaa" }
                                }
                            ],
                        });


                   }
                    drawGraph();
                    jQuery('.highlight_series6').hover(function() {
                                    // alert('h');
                                    var h = jQuery(this).data('series');
                    
                                    drawGraph( jQuery(this).data('series') );
                                },
                                    function() {
                                        drawGraph();
                                    });
                 }
                else{
                    $('#dateBy_today').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }
                   
                $('#gross_sale_today').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_today').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_today').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average sale.</span></strong>');
                $('#orders_placed_today').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_today').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_today').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of today.');
            }

        });

    });

    $('#go_custom').click(function(e){
        e.preventDefault();
        e.stopPropagation();


        var from = $('#from').val();
        var to   = $('#to').val();
        if(from == '' || to == ''){

            alert('Select date range!');
            return false;
        }

        $.ajax({
            url: '/reports/get_custom_by_date',
            data: {from:from, to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0){
                    var main_chart;
                    var drawGraph = function( highlight ) {

                        // alert(highlight);
                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                }, shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if ( highlight !== 'undefined' && series[ highlight ] ) {
                            highlight_series = series[ highlight ];

                            highlight_series.color = '#9c5d90';

                            if ( highlight_series.bars ) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if ( highlight_series.lines ) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [ {
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                } ],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: { color: "#aaa" }
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: { color: "#aaa" }
                                    }
                                ],
                            });


                    }
                    drawGraph();
                    jQuery('.highlight_series7').hover(function() {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph( jQuery(this).data('series') );
                        },
                        function() {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_custom').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_custom').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_custom').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average sale.</span></strong>');
                $('#orders_placed_custom').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_custom').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_custom').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in custom graph.');
            }
            });

    });

    $('#year_per_day').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_year_by_date_perDay',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0) {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.3,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];

                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_year'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat:"%d %b",
                                    monthNames: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }

                    drawGraph();
                    jQuery('.highlight_series').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average monthly sales.</span></strong>');
                $('#orders_placed').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item').html('<strong><span style="color:#969492 " class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current year.');
            }

        });

    });

    $('#year_per_week').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_year_by_date_perWeek',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0) {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.3,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];

                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_year'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat:"%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 7,
                                    minTickSize: [7, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }

                    drawGraph();
                    jQuery('.highlight_series').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average weekly sales.</span></strong>');
                $('#orders_placed').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item').html('<strong><span style="color:#969492 " class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current year.');
            }

        });

    });

    $('#year_per_month').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/reports/get_year_by_date',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0) {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 2419200000 * 0.5,
                                    align: 'center'
                                },
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];

                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_year'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%b",
                                    monthNames: ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "month"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }

                    drawGraph();
                    jQuery('.highlight_series').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale').html('<strong><span style="color:#25b37f" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale').html('<strong><span style="color:#78f0aa " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale').html('<strong><span style="color:#6fdcde " class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> Average monthly sales.</span></strong>');
                $('#orders_placed').html('<strong><span style="color:#AD6823 " class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item').html('<strong><span style="color:#969492 " class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund').html('<strong><span style="color:#e74c3c " class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in graph of current year.');
            }

        });

    });

    $('#custom_per_day').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var from = $('#from').val();
        var to   = $('#to').val();
        if(from == '' || to == ''){

            alert('Select date range!');
            return false;
        }

        $.ajax({
            url: '/reports/get_custom_by_date',
            data: {from:from, to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0){
                    var main_chart;
                    var drawGraph = function( highlight ) {

                        // alert(highlight);
                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                }, shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if ( highlight !== 'undefined' && series[ highlight ] ) {
                            highlight_series = series[ highlight ];

                            highlight_series.color = '#9c5d90';

                            if ( highlight_series.bars ) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if ( highlight_series.lines ) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [ {
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                } ],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: { color: "#aaa" }
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: { color: "#aaa" }
                                    }
                                ],
                            });


                    }
                    drawGraph();
                    jQuery('.highlight_series7').hover(function() {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph( jQuery(this).data('series') );
                        },
                        function() {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_custom').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_custom').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_custom').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average sale.</span></strong>');
                $('#orders_placed_custom').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_custom').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_custom').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in custom graph.');
            }
        });

    });

    $('#custom_per_week').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var from = $('#from').val();
        var to   = $('#to').val();
        if(from == '' || to == ''){

            alert('Select date range!');
            return false;
        }

        $.ajax({
            url: '/reports/get_custom_by_date_perWeek',
            data: {from:from, to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0){
                    var main_chart;
                    var drawGraph = function( highlight ) {

                        // alert(highlight);
                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                }, shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if ( highlight !== 'undefined' && series[ highlight ] ) {
                            highlight_series = series[ highlight ];

                            highlight_series.color = '#9c5d90';

                            if ( highlight_series.bars ) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if ( highlight_series.lines ) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [ {
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],
                                    tickLength: 7,
                                    minTickSize: [7, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                } ],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: { color: "#aaa" }
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: { color: "#aaa" }
                                    }
                                ],
                            });


                    }
                    drawGraph();
                    jQuery('.highlight_series7').hover(function() {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph( jQuery(this).data('series') );
                        },
                        function() {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_custom').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_custom').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_custom').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average weekly sale.</span></strong>');
                $('#orders_placed_custom').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_custom').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_custom').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in custom graph.');
            }
        });

    });

    $('#custom_per_month').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var from = $('#from').val();
        var to   = $('#to').val();
        if(from == '' || to == ''){

            alert('Select date range!');
            return false;
        }

        $.ajax({
            url: '/reports/get_custom_by_date_perMonth',
            data: {from:from, to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                if(response.order_amounts.length > 0 || response.order_net_amount.length > 0 || response.order_item_count.length > 0
                    || response.order_count.length > 0 || response.order_average_amount.length > 0 || response.refund_amount.length > 0){
                    var main_chart;
                    var drawGraph = function( highlight ) {

                        // alert(highlight);
                        var series = [
                            {
                                label: "Purchased Items",
                                data: response.order_item_count,
                                color: '#969492',
                                bars: {

                                    fillColor: '#969492',
                                    fill: true,
                                    show: true,
                                    lineWidth: 0,
                                    barWidth: 86400000 * 0.5,
                                    align: 'center'
                                }, shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Orders Count",
                                data: response.order_count,
                                color: '#AD6823',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },
                            {
                                label: "Order Average Amount",
                                data: response.order_average_amount,
                                /*yaxis: 2,*/
                                color: '#6fdcde',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                hoverable: true
                            },


                            {
                                label: "Gross Sale",
                                data: response.order_amounts,
                                yaxis: 2,
                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Net Sale",
                                data: response.order_net_amount,
                                yaxis: 2,
                                color: '#78f0aa',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },
                            {
                                label: "Refund Amount",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if ( highlight !== 'undefined' && series[ highlight ] ) {
                            highlight_series = series[ highlight ];

                            highlight_series.color = '#9c5d90';

                            if ( highlight_series.bars ) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if ( highlight_series.lines ) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#dateBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [ {
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "month"],
                                    font: {
                                        color: "#aaa"
                                    }
                                } ],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: { color: "#aaa" }
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: { color: "#aaa" }
                                    }
                                ],
                            });


                    }
                    drawGraph();
                    jQuery('.highlight_series7').hover(function() {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph( jQuery(this).data('series') );
                        },
                        function() {
                            drawGraph();
                        });
                }
                else{
                    $('#dateBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }

                $('#gross_sale_custom').html('<strong><span style="color:#25b37f " class="amount">&euro;&nbsp;'+checkAmount(response.total_order_amount)+'<br> Gross sale this period.</span></strong>');
                $('#net_gross_sale_custom').html('<strong><span style="color:#78f0aa" class="amount">&euro;&nbsp;'+checkAmount(response.total_order_net_amount)+'<br> Net sale this period.</span></strong>');
                $('#average_monthly_sale_custom').html('<strong><span style="color:#6fdcde" class="amount">&euro;&nbsp;'+checkAmount(response.total_average)+'<br> average sale.</span></strong>');
                $('#orders_placed_custom').html('<strong><span style="color:#AD6823" class="amount">&nbsp;'+checkCount(response.total_order_count)+'<br> Orders placed.</span></strong>');
                $('#total_item_custom').html('<strong><span style="color:#969492" class="amount">&nbsp;'+checkCount(response.total_order_item_count)+'<br> Items purchased.</span></strong>');
                $('#total_refund_custom').html('<strong><span style="color:#e74c3c" class="amount">&euro;&nbsp;'+checkAmount(response.total_refund_amount)+'<br> Refund amount.</span></strong>');

            },
            error: function (response) {
                alert('Error: displaying in custom graph.');
            }
        });

    });

    $('#go_custom_product').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var from = $('#from').val();
        var to   = $('#to').val();
        if(from == '' || to == ''){

            alert('Select date range!');
            return false;
        }

        from = from.replace(/\//g,"-");
        to = to.replace(/\//g, "-");

        angular.element($("#tab_product_custom")).scope().getData(from, to);
    });

});

    function showVar(pid, period){

        var str = pid;
        var str = str.split("-");
        var period_name = str[0];
        var id = str[1];

        var period = period;
        var period_func = "'"+period+"'";
        var product_type = 'parent';

        var from = $('#from').val();
        var to   = $('#to').val();


        $.ajax({
            url: '/reports/get_product_variations',
            data: {parent_id : id , period: period, from:from , to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                console.log(response);
                if(response.length > 0) {
                    var str = '<tr class="var">';
                    str += '<td colspan="6">';
                    str += '<table class="table table-bordered table-hover table-checkable top10">';

                    $.each(response, function (i, v) {


                        str += '<tr>'
                        str += '<td  width="1%">--</td>';
                        str += '<td  width="20%"><a onclick="displayVarGraph('+v.product_id+','+period_func+' )" id="' + v.product_id + '">' + v.name + '</a><br> <strong>' + v.sku + '</strong></td>';
                        str += '<td width="10%">' + v.sales + '</td>';
                        str += '<td width="10%" >' + v.returns + '</td>';
                        str += '<td width="15%">' + v.revenue + '</td>';
                        str += '<td width="15%">' + v.net_revenue + '</td>';
                        str += '</tr>';
                    });

                    str += '</table>';
                    str += '</td>';
                    str += '</tr>';
                    if ($('#'+period_name+"-"+ id).parent().parent().next().hasClass("var")) {


                        $('#'+period_name+"-"+ id).parent().parent().next().remove();
                    }
                    else {

                        $('#'+period_name+"-"+ id).parent().parent().after(str)
                    }
                    //$(".var").slideToggle();
                }
            },
            error: function (response) {
                alert('Error found while fetching data.');
            }
        })

        if(period == "year"){

        $.ajax({
            url: '/reports/get_year_by_product',
            data: {parent_id : id, product_type:product_type},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.refund_amount.length > 0) {


                    var main_chart;
                    var drawGraph = function (highlight) {


                        var series = [

                            {
                                label: "Sales",
                                data: response.order_amounts,

                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },

                            {
                                label: "Refunds",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];

                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#productBy_year'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%b",
                                    monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "month"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }

                    drawGraph();
                    jQuery('.highlight_series').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#productBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }


                $('#sales').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                $('#refunds').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                $('#pname').html(response.product_name);


            },
            error: function (response) {
                alert('Error: displaying in graph of current year.');
            }

        });

        }
        else if(period == 'lmonth'){

            $.ajax({
                url: '/reports/get_lmonth_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_lmonth'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_lmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_lmonth').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_lmonth').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_lmonth').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == 'cmonth'){

            $.ajax({
                url: '/reports/get_cmonth_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_cmonth'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_cmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_cmonth').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_cmonth').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_cmonth').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == '14days'){

            $.ajax({
                url: '/reports/get_14days_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_14days'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_14days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_14days').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_14days').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_14days').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == '7day'){

            $.ajax({
                url: '/reports/get_7days_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_7days'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_7days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_7day').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_7day').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_7day').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }

        else if(period == 'yesterday'){

            $.ajax({
                url: '/reports/get_yesterday_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_yesterday'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_yesterday').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_yesterday').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_yesterday').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_yesterday').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == 'today'){

            $.ajax({
                url: '/reports/get_today_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_today'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_today').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_today').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_today').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_today').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }

        else if(period == 'custom'){

        $.ajax({
            url: '/reports/get_custom_by_product',
            data: {parent_id : id, product_type:product_type , from: from , to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                {


                    var main_chart;
                    var drawGraph = function (highlight) {

                        var series = [

                            {
                                label: "Sales",
                                data: response.order_amounts,

                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },

                            {
                                label: "Refunds",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#productBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series1').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#productBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }


                $('#sales_product_custom').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                $('#refunds_product_custom').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                $('#pname_product_custom').html(response.product_name);


            },
            error: function (response) {
                alert('Error: displaying in graph of last month.');
            }

        });

    }
}

    function displayVarGraph(id, period){

        var from = $('#from').val();
        var to   = $('#to').val();

        var product_type = 'child';
        if(period == 'year'){


            $.ajax({
                url: '/reports/get_year_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0) {


                        var main_chart;
                        var drawGraph = function (highlight) {


                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];

                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_year'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%b",
                                        monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "month"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }

                        drawGraph();
                        jQuery('.highlight_series').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_year').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of current year.');
                }

            });

        }
        else if(period == 'lmonth'){

            $.ajax({
                url: '/reports/get_lmonth_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_lmonth'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_lmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_lmonth').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_lmonth').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_lmonth').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == 'cmonth'){

            $.ajax({
                url: '/reports/get_cmonth_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_cmonth'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_cmonth').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_cmonth').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_cmonth').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_cmonth').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == '14days'){

            $.ajax({
                url: '/reports/get_14days_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_14days'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_14days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_14days').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_14days').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_14days').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == '7day'){

            $.ajax({
                url: '/reports/get_7days_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_7days'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_7days').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_7day').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_7day').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_7day').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }

        else if(period == 'yesterday'){

            $.ajax({
                url: '/reports/get_yesterday_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_yesterday'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_yesterday').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_yesterday').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_yesterday').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_yesterday').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == 'today'){

            $.ajax({
                url: '/reports/get_today_by_product',
                data: {parent_id : id, product_type:product_type},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                    {


                        var main_chart;
                        var drawGraph = function (highlight) {

                            var series = [

                                {
                                    label: "Sales",
                                    data: response.order_amounts,

                                    color: '#25b37f',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;&nbsp;"
                                },

                                {
                                    label: "Refunds",
                                    data: response.refund_amount,
                                    yaxis: 2,
                                    color: '#e74c3c',
                                    points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                    lines: {show: true, lineWidth: 2, fill: false},
                                    shadowSize: 0,
                                    prepend_tooltip: "&euro;"
                                },
                            ];
                            //  console.log(series[ highlight ]);
                            if (highlight !== 'undefined' && series[highlight]) {
                                highlight_series = series[highlight];

                                highlight_series.color = '#9c5d90';

                                if (highlight_series.bars) {
                                    highlight_series.bars.fillColor = '#9c5d90';
                                }

                                if (highlight_series.lines) {
                                    highlight_series.lines.lineWidth = 5;
                                }
                            }

                            main_chart = jQuery.plot(
                                jQuery('#productBy_today'),
                                series,
                                {
                                    legend: {
                                        show: false
                                    },
                                    tooltip: {
                                        show: true,
                                        content: "y: %y"
                                    },
                                    grid: {
                                        color: '#aaa',
                                        borderColor: 'transparent',
                                        borderWidth: 1,
                                        hoverable: true
                                    },
                                    xaxes: [{
                                        color: '#aaa',
                                        position: "bottom",
                                        tickColor: 'transparent',
                                        mode: "time",
                                        timeformat: "%d %b",
                                        monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                        tickLength: 1,
                                        minTickSize: [1, "day"],
                                        font: {
                                            color: "#aaa"
                                        }
                                    }],
                                    yaxes: [
                                        {
                                            min: 0,
                                            minTickSize: 1,
                                            tickDecimals: 0,
                                            color: '#d4d9dc',
                                            font: {color: "#aaa"}
                                        },
                                        {
                                            position: "right",
                                            min: 0,
                                            tickDecimals: 2,
                                            alignTicksWithAxis: 1,
                                            color: 'transparent',
                                            font: {color: "#aaa"}
                                        }
                                    ],
                                }
                            );


                        }
                        drawGraph();
                        jQuery('.highlight_series1').hover(function () {
                                // alert('h');
                                var h = jQuery(this).data('series');

                                drawGraph(jQuery(this).data('series'));
                            },
                            function () {
                                drawGraph();
                            });
                    }
                    else{
                        $('#productBy_today').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                    }


                    $('#sales_product_today').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                    $('#refunds_product_today').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                    $('#pname_product_today').html(response.product_name);


                },
                error: function (response) {
                    alert('Error: displaying in graph of last month.');
                }

            });

        }
        else if(period == 'custom'){

        $.ajax({
            url: '/reports/get_custom_by_product',
            data: {parent_id : id, product_type:product_type , from: from , to:to},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                if(response.order_amounts.length > 0 || response.refund_amount.length > 0 )
                {


                    var main_chart;
                    var drawGraph = function (highlight) {

                        var series = [

                            {
                                label: "Sales",
                                data: response.order_amounts,

                                color: '#25b37f',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;&nbsp;"
                            },

                            {
                                label: "Refunds",
                                data: response.refund_amount,
                                yaxis: 2,
                                color: '#e74c3c',
                                points: {show: true, radius: 4, lineWidth: 2, fillColor: '#fff', fill: true},
                                lines: {show: true, lineWidth: 2, fill: false},
                                shadowSize: 0,
                                prepend_tooltip: "&euro;"
                            },
                        ];
                        //  console.log(series[ highlight ]);
                        if (highlight !== 'undefined' && series[highlight]) {
                            highlight_series = series[highlight];

                            highlight_series.color = '#9c5d90';

                            if (highlight_series.bars) {
                                highlight_series.bars.fillColor = '#9c5d90';
                            }

                            if (highlight_series.lines) {
                                highlight_series.lines.lineWidth = 5;
                            }
                        }

                        main_chart = jQuery.plot(
                            jQuery('#productBy_custom'),
                            series,
                            {
                                legend: {
                                    show: false
                                },
                                tooltip: {
                                    show: true,
                                    content: "y: %y"
                                },
                                grid: {
                                    color: '#aaa',
                                    borderColor: 'transparent',
                                    borderWidth: 1,
                                    hoverable: true
                                },
                                xaxes: [{
                                    color: '#aaa',
                                    position: "bottom",
                                    tickColor: 'transparent',
                                    mode: "time",
                                    timeformat: "%d %b",
                                    monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
                                    tickLength: 1,
                                    minTickSize: [1, "day"],
                                    font: {
                                        color: "#aaa"
                                    }
                                }],
                                yaxes: [
                                    {
                                        min: 0,
                                        minTickSize: 1,
                                        tickDecimals: 0,
                                        color: '#d4d9dc',
                                        font: {color: "#aaa"}
                                    },
                                    {
                                        position: "right",
                                        min: 0,
                                        tickDecimals: 2,
                                        alignTicksWithAxis: 1,
                                        color: 'transparent',
                                        font: {color: "#aaa"}
                                    }
                                ],
                            }
                        );


                    }
                    drawGraph();
                    jQuery('.highlight_series1').hover(function () {
                            // alert('h');
                            var h = jQuery(this).data('series');

                            drawGraph(jQuery(this).data('series'));
                        },
                        function () {
                            drawGraph();
                        });
                }
                else{
                    $('#productBy_custom').html('<div class="col-md-12" style="display: table;padding: 0;height: 100%;"><span style="margin: auto;display: table-cell;vertical-align: middle;text-align: center;"> No record available for this period </span></div>');
                }


                $('#sales_product_custom').html('<strong><span style="color:#25b37f" class="amount">&nbsp;'+checkCount(response.total_sales)+'<br> Sales in this period.</span></strong>');
                $('#refunds_product_custom').html('<strong><span style="color:#78f0aa " class="amount">&nbsp;'+checkCount(response.total_refunds)+'<br> Refunds in this period.</span></strong>');
                $('#pname_product_custom').html(response.product_name);


            },
            error: function (response) {
                alert('Error: displaying in graph of last month.');
            }

        });

    }
}

function checkAmount(val){
    if(val==null || val===false){

        return val = '0.00';
    }
    else{
        return val;
    }

}
function checkCount(val){
    if(val==null || val===false){

        return val = '0';
    }
    else{
        return val;
    }

}