$(function() {
    $.ajax({

        url: 'http://pharmacy.westudyit.com/php/chart_show.php',
        type: 'GET',
        success: function(data) {
            chartData = data;
            var chartProperties = {
                "caption": "Top Product",
                "xAxisName": "Item Name",
                "yAxisName": "Sold Quantity",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                width: '1200',
                height: '600',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": chartData
                }
            });
            apiChart.render();
        }
    });
});