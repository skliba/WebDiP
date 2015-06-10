$(document).ready(function () {
    $("#timeShiftButton").click(function () {
        var pomak = $("#timeShift").val();

        $.ajax({
            type: 'POST',
            url: 'http://arka.foi.hr/WebDiP/pomak_vremena/vrijeme.php',
            data: {
                'pomak': pomak
            },
            success: function (data) {
                

                $.ajax({
                    type: 'GET',
                    url: 'http://arka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=xml',
                    dataType: 'xml',
                    success: function (data) {
                        
                        curr = $(data).find('brojSati').text();
                        var varJson = {'value': curr};
                        $.post('virtualTime.php', varJson);

                    }
                });
            }


        });
    });
});



