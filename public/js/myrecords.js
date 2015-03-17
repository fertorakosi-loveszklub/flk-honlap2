$(document).ready(function() {
    /**
     * Hide progress button by default.
     * Load google code then enable it.
     */
    $('.myProgress').hide();
    google.load("visualization", "1", {
        packages    : ["corechart"],
        callback    : function() { enableProgress(); }
    });

    // Hide error
    $('.error').hide();

    // Sort
    $('.recordtable').tablesorter();

    // Fancybox
    $('.fancyimage').fancybox();

    // Toggle visibility
    $('.toggle-visibility').click(function(){
        toggleVisibility($(this));
    });
})

function toggleVisibility(element) {
    // Disable
    element.prop('disabled', true);

    // Set waiting icon
    element.children('i').removeClass('fa-toggle-off fa-toggle-on')
        .addClass('fa-circle-o-notch fa-spin');

    $.ajax({
        url         : '/rekordok/lathatosag/' + element.data('id'),
        dataType    : 'json',
        error       : function() {
            $('#error').html('A szerver nem elérhető');
            $('.error').show();
        },
        success     : function(data) {
            if (!data.success) {
                $('#error').html(data.message);
                // Don't set icons, PEBKAC
            } else {
                element.children('i').removeClass('fa-circle-o-notch fa-spin');
                element.children('i').addClass(data.isPublic ? 'fa-toggle-on' : 'fa-toggle-off');
                element.prop('disabled', false);
            }
        }
    });
}

function enableProgress() {
    $('.myProgress').show();

    $('.myProgress').each(function() {
        var that = this;
        $(this).fancybox({
            afterLoad: function () {
                drawProgress($(that).data('id'));
            }
        })
    });

    /*$('.myProgress').click(function() {
        var button = $(this);
        button.fancybox({
            afterLoad: function() {
                drawProgress(button.data('id'));
            }
        });
    });*/
}

function drawProgress(id) {
    $.ajax({
        url         : '/rekordok/grafikon/' + id,
        dataType    : 'json',
        error       : function() {
            $('#error').html('A szerver nem elérhető');
            $('.error').show();
        },
        success     : function(data) {
            if (!data.success) {
                $('#error').html(data.message);
                // Don't set icons, PEBKAC
            } else {
                var chartData = data.data;
                for(var i = 1; i < chartData.length; ++i) {
                    //var date = chartData[0][1].split(/[-]/);
                    //chartData[i][0] = new Date(date[0], date[1]-1, date[2], 0, 0, 0);
                    chartData[i][1] = parseInt(chartData[i][1]);
                }

                // Transform data
                var chartData = google.visualization.arrayToDataTable(data.data);

                var options = {
                    title: 'Egyéni teljesítmény',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('progressData'));

                chart.draw(chartData, options);
            }
        }
    });
}