$(document).ready(function () {
    errorArray = new Array();

    $("#uname").focusout(function () {

        var korIme = $("#uname").val();

        $.ajax({
            type: 'GET',
            url: './korisnik.php',
            dataType: 'xml',
            data: {
                'korisnik': korIme
            },
            success: function (data) {

                if (($(data).find('korisnik').text())[0] === '1') {
                    errorArray[4] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Korisničko ime <strong>" + korIme + "</strong> je zauzeto </p>";
                    $("#greske").html(errorArray.join(""));
                    $("#uname").removeClass("highlight-input").addClass("highlight-input-error");
                }
                else {
                    errorArray[4] = '';
                    $("#greske").html(errorArray.join(""));
                    $("#uname").removeClass("highlight-input-error").addClass("highlight-input");
                }
            }
        });
    });

    $("#ime").focusout(function () {
        var name = $("#ime").val();
        var firstLetter = name[0];

        if (firstLetter !== firstLetter.toUpperCase()) {
            errorArray[0] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Ime mora počinjati velikim početnim slovom</p>";
            $("#greske").html(errorArray.join(""));
            $("#ime").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[0] = '';
            $("#greske").html(errorArray.join(""));
            $("#ime").removeClass("highlight-input-error").addClass("highlight-input");
        }
    });

    $("#prezime").focusout(function () {
        var surname = $("#prezime").val();
        var firstLetter = surname[0];

        if (firstLetter !== firstLetter.toUpperCase()) {
            errorArray[1] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Prezime mora počinjati velikim početnim slovom</p>";
            $("#greske").html(errorArray.join(""));
            $("#prezime").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[1] = '';
            $("#greske").html(errorArray.join(""));
            $("#prezime").removeClass("highlight-input-error").addClass("highlight-input");
        }
    });

    $("#adresa").focusout(function () {
        var adress = $("#adresa").val();

        if (adress.length > 100) {
            errorArray[2] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Adresa ne smije imati vise od 100 znakova</p>";
            $("#greske").html(errorArray.join(""));
            $("#adresa").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[2] = '';
            $("#greske").html(errorArray.join(""));
            $("#adresa").removeClass("highlight-input-error").addClass("highlight-input");
        }
    });

    $("#grad").focusout(function () {
        var city = $("#grad").val();
        var firstLetter = city[0];

        if (firstLetter !== firstLetter.toUpperCase()) {
            errorArray[3] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Grad mora počinjati velikim početnim slovom</p>";
            $("#greske").html(errorArray.join(""));
            $("#grad").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[3] = '';
            $("#greske").html(errorArray.join(""));
            $("#grad").removeClass("highlight-input-error").addClass("highlight-input");
        }
    });

    $("#pw").focusout(function () {
        var pw = $("#pw").val();

        if (pw.length < 6) {
            errorArray[5] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Lozinka mora imati najmanje 6 znakova</p>";
            $("#greske").html(errorArray.join(""));
            $("#pw").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[5] = '';
            $("#greske").html(errorArray.join(""));
            $("#pw").removeClass("highlight-input-error").addClass("highlight-input");
        }
    });


    $("#registracija").submit(function (e) {

        var atLeastOneIsChecked = $('input[name="radioB"]:checked').length;
        if (atLeastOneIsChecked === 0) {

            errorArray[6] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Morate odabrati spol</p>";
            $("#greske").html(errorArray.join(""));
            $("#radioBcontainer").removeClass("highlight-input").addClass("highlight-input-error");
            e.preventDefault();
        }


        var asnwer = $("#answer").val();

        if (asnwer.length < 1) {
            errorArray[7] = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> Unesite odgovor na sigurnosno pitanje</p>";
            $("#greske").html(errorArray.join(""));
            $("#answer").removeClass("highlight-input").addClass("highlight-input-error");
        }
        else {
            errorArray[7] = '';
            $("#greske").html(errorArray.join(""));
            $("#answer").removeClass("highlight-input-error").addClass("highlight-input");
        }
        
        var formular = document.getElementById("registracija");
        var elementiForme = formular.elements;  
        for (i = 0; i < errorArray.length; i++)
        {
            if (errorArray[i] !== '' && errorArray[i] !== undefined)
            {

                e.preventDefault();

            }
            else {
                for (i = 0; i < formular.elements.length; i++) {
                    if (elementiForme[i].type !== "submit" && elementiForme[i].type !== "reset") {

                        if (formular.elements[i].value === '')
                        {
                            e.preventDefault();
                            document.getElementById("greske").innerHTML = "<p><img src='http://png-2.findicons.com/files/icons/1609/ose_png/256/warning.png'> </p><p> <strong>Sva polja moraju biti unesena! </strong></p>";
                            $("#uname").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#ime").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#prezime").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#adresa").removeClass("highlight-input").addClass("highlight-input-error");
                            
                            $("#grad").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#pw").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#radioBcontainer").removeClass("highlight-input").addClass("highlight-input-error");
                            $("#answer").removeClass("highlight-input").addClass("highlight-input-error");
                            
                        }
                        else {
                            return true;
                        }
                    }

                }
            }

        }
    });
    
    $("#delForm").click(function(){
        
        for(var i = 0; i < errorArray.length; i++){
            errorArray[i] = '';
        }
        $("#greske").html("");
        
        $("#uname").removeClass("highlight-input-error").addClass("highlight-input");
        $("#ime").removeClass("highlight-input-error").addClass("highlight-input");
        $("#prezime").removeClass("highlight-input-error").addClass("highlight-input");
        $("#adresa").removeClass("highlight-input-error").addClass("highlight-input");
        $("#grad").removeClass("highlight-input-error").addClass("highlight-input");
        $("#pw").removeClass("highlight-input-error").addClass("highlight-input");
        $("#radioBcontainer").removeClass("highlight-input-error");
        $("#mail").removeClass("hightlight-input-error");
        $("#answer").removeClass("highlight-input-error").addClass("highlight-input");
    });




});







