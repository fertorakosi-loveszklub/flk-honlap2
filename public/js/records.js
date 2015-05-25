$(document).ready(function() {
    // Reset
    resetAll();

    // Load records when tab has been shown
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Reset all
        resetAll();

        // Get target elements
        var targetTab = $($(e.target).attr("href"));
        var targetTable = targetTab.find('.recordtable');

        // Get category
        var categoryID = $(e.target).data('category');

        // Show loading icon
        $('.loading').show();

        // Load data
        $.ajax({
            url         : '/rekordok/rekordok/' + categoryID,
            dataType    : 'json',
            error       : function() {
                $('#error').html('A szerver nem elérhető');
                $('.error').show();
            },
            success     : function(data) {
                if (!data.success) {
                    $('#error').html(data.message);
                } else {
                    displayData(targetTable, data.data);
                }
            },
            complete    : function() {
                $('.loading').hide();
            }
        });
    })
});

/**
 * Resets all tabs.
 */
function resetAll() {
    $('.error').hide();
    $('.recordtable').hide();
    $('.recordtable tbody').html('');
    $('.loading').show();
}

/**
 * Inserts loaded data into the DOM
 * @param targetTable Target table storing the records.
 * @param data Data to be stored.
 */
function displayData(targetTable, data) {
    data.forEach(function(entry) {
        var row = '<tr>';
        row +=  '<td>' + entry.real_name + '</td>';
        row +=  '<td>' + formatDate(entry.shot_at) + '</td>';
        row +=  '<td>' + entry.points + '</td>';
        row +=  '<td>' + entry.shots + '</td>';
        row +=  '<td>' + entry.record + '</td>';
        row +=  '<td><a href="' + entry.image_url + '" class="fancyImage">Megtekintés</a></td>';
        row += '</tr>';
        targetTable.children('tbody').append(row);
    });

    targetTable.show();
    $('.fancyImage').fancybox();
}

function formatDate(date) {
    date = new Date(date);
    return date.getFullYear() + ". " + (date.getMonth() + 1) + ". " + date.getDate() + ".";
}