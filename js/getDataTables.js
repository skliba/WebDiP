$(document).ready(function () {


    $.ajax({
        type: 'GET',
        url: './config.xml',
        dataType: 'xml',
        success: function (data) {
            broj = parseInt($(data).find('brojPoStranici').text());

            $(".display").dataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": true,
                "iDisplayLength": broj
            });


        }

    });

});

