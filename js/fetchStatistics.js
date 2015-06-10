
function fetch() {
    var myTable = $("<table id='userListTable' class='display'>");
    myTable.append("<thead><tr><th>Username</th><th>Vrijeme</th><th>Akcija</th><th>Vrsta loga</th></tr></thead>");
    $.ajax({
        type: 'GET',
        url: './statistika.xml',
        dataType: 'xml',
        success: function (data) {

            var tbody = $("<tbody>");
            $(data).find('akcija').each(function () {

                var row = "<tr>";
                row += "<td>" + $(this).find('korisnik').text() + "</td>";
                row += "<td>" + $(this).find('vrijeme').text() + "</td>";
                row += "<td>" + $(this).find('opis').text() + "</td>";
                row += "<td>" + $(this).find('vrstaLog').text() + "</td>";
                row += "</tr>";
                tbody.append(row);
            });
            tbody.append("</tbody>");
            myTable.append(tbody);
            $("#userList").html(myTable);
            dataTablez();
        }
    });
}


function dataTablez(){ 
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
}




