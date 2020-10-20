
google.load('visualization', '1.0', {'packages': ['orgchart']});
jQuery(document).ready(function() {
    drawChart();

});

function drawChart() {
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Manager');
    data.addColumn('string', 'ToolTip');
    
    data.addRows([
        [{v: 'Mike', f: 'Mike<div style="color:red; font-style:italic">President</div>'}, '', 'The President'],
        [{v: 'Jim', f: 'Jim<div style="color:red; font-style:italic">Vice President</div>'}, 'Mike', 'VP'],
        ['Alice', 'Mike', ''],
        ['Bob', 'Jim', 'Bob Sponge'],
        ['Carol', 'Bob', 'prueba']
    ]);
    
    var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    data.setRowProperty(3, 'selectedStyle', 'background-color:green');
    data.setRowProperty(3, 'style', 'border: 2px solid green');
    chart.draw(data, {allowHtml: true,collapsed:true});
}