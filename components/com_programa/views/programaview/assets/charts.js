google.load('visualization', '1.0', {'packages': ['corechart']});
jQuery(document).ready(function() {
    jQuery(".indTable").mouseenter(function() {
        drawChart();
    }).mouseleave(function() {
        drawChart();
    });

});

function drawChart() {
    var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        // Set chart options
        var options = {'title':'How Much Pizza I Ate Last Night',
                       'width':400,
                       'height':300};

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}