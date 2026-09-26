$(function() {
    "use strict";

    var hms = window.hmsAnalytics || {};
    var trendLabels = (hms.trendLabels && hms.trendLabels.length) ? hms.trendLabels : ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    var trendTotal = (hms.trendTotal && hms.trendTotal.length) ? hms.trendTotal : [0, 0, 0, 0, 0, 0, 0];
    var trendCompleted = (hms.trendCompleted && hms.trendCompleted.length) ? hms.trendCompleted : [0, 0, 0, 0, 0, 0, 0];
    var statusSeries = (hms.statusSeries && hms.statusSeries.length) ? hms.statusSeries : [0, 0, 0, 0];

    // Chart 1: 7-Day Hospital Appointment Trends
    if (document.querySelector("#chart1")) {
        var options1 = {
            series: [{
                name: "Total Bookings",
                data: trendTotal
            }, {
                name: "Completed",
                data: trendCompleted
            }],
            chart: {
                foreColor: '#6c757d',
                type: "area",
                height: 330,
                toolbar: { show: false },
                zoom: { enabled: false },
                dropShadow: {
                    enabled: false
                }
            },
            markers: {
                size: 4,
                colors: ["#3461ff", "#12bf24"],
                strokeColors: "#fff",
                strokeWidth: 2,
                hover: { size: 6 }
            },
            legend: {
                show: false
            },
            dataLabels: { enabled: false },
            grid: {
                show: true,
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
            },
            stroke: {
                show: true,
                width: 3,
                curve: "smooth"
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "vertical",
                    shadeIntensity: 0.5,
                    gradientToColors: ["#3461ff", "#12bf24"],
                    inverseColors: true,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                }
            },
            colors: ["#3461ff", "#12bf24"],
            xaxis: {
                categories: trendLabels
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return val + " patient(s)";
                    }
                }
            }
        };

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
    }

    // Chart 2: Appointment Status Breakdown (Donut)
    if (document.querySelector("#chart2")) {
        var totalCount = statusSeries.reduce(function(acc, val) { return acc + val; }, 0);
        var seriesToRender = totalCount > 0 ? statusSeries : [1, 1, 1, 0];

        var options2 = {
            series: seriesToRender,
            chart: {
                height: 250,
                type: 'donut',
            },
            labels: ['Completed', 'Pending', 'Admitted', 'Cancelled'],
            colors: ["#12bf24", "#ffc107", "#0dcaf0", "#dc3545"],
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return (totalCount > 0 ? val : 0) + " appointment(s)";
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { height: 220 },
                    legend: { position: 'bottom' }
                }
            }]
        };

        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
        chart2.render();
    }
});