
function provjeri_korime() {
    var korime = $('#korime').val();
    $.post("provjeriDostupnost.php", {korime: korime},
            function (result) {
                if (result > 0) {
                    $("#korimeG").html("Korisničnko ime nije dostupno!");
                    return false;
                }
            });
    var v = document.registracija.korime.value;
    var re = new RegExp(/^[a-zA-Z0-9_-]{6,16}$/);
    var ok = re.test(v);
    if (!ok)
    {
        $(document.registracija.korime).addClass("lose");
        $(document.registracija.korime).removeClass("dobro");
        $("#korimeG").html("Korisnicko ime nije ispravno!");
        return false;
    } else {
        $(document.registracija.korime).removeClass("lose");
        $(document.registracija.korime).addClass("dobro");
        $("#korimeG").html("");
        return true;
    }


}

function provjeri_lozinku() {
    var v = document.registracija.lozinka1.value;
    var re = new RegExp(/(?=.*[a-z]+)(?=.*[A-Z]+)(?=.*[0-9]+)(?=.*[!#$?]+)(?=.{8,})/);
    var ok = re.test(v);
    if (!ok)
    {
        $(document.registracija.lozinka1).addClass("lose");
        $(document.registracija.lozinka1).removeClass("dobro");
        $("#lozG").html("Lozinka nije ispravna!");
        return false;
    } else {
        $(document.registracija.lozinka1).removeClass("lose");
        $(document.registracija.lozinka1).addClass("dobro");
        $("#lozG").html("");
        return true;
    }
}

function provjeri_lozinku2() {
    var v = document.registracija.lozinka1.value;
    var v2 = document.registracija.lozinka2.value;
    if (v === v2)
    {
        $(document.registracija.lozinka2).removeClass("lose");
        $(document.registracija.lozinka2).addClass("dobro");
        $("#loz2G").html("");
        return false;
    } else {
        $(document.registracija.lozinka2).addClass("lose");
        $(document.registracija.lozinka2).removeClass("dobro");
        $("#loz2G").html("Lozinka se ne podudara!");
        return true;
    }
}

function provjeri_dan() {
    var v = document.registracija.dan.value;
    var re = new RegExp(/^[1-9]|1[1-9]|2[1-9]|3[0-1]$/);
    var ok = re.test(v);
    if (!ok)
    {
        $(document.registracija.dan).addClass("lose");
        $(document.registracija.dan).removeClass("dobro");
        return false;
    } else {
        $(document.registracija.dan).removeClass("lose");
        $(document.registracija.dan).addClass("dobro");
        return true;
    }
}

function provjeri_godinu() {
    var v = document.registracija.god.value;
    var re = new RegExp(/^193\d|194\d|195\d|196\d|197\d|198\d|198\d|199\d|200\d|201[0-5]$/);
    var ok = re.test(v);
    if (!ok)
    {
        $(document.registracija.god).addClass("lose");
        $(document.registracija.god).removeClass("dobro");
        return false;
    } else {
        $(document.registracija.god).removeClass("lose");
        $(document.registracija.god).addClass("dobro");
        return true;
    }
}


function provjeri_email() {
    var v = document.registracija.email.value;
    var re = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+$/);
    var ok = re.test(v);
    if (!ok)
    {
        $(document.registracija.email).addClass("lose");
        $(document.registracija.email).removeClass("dobro");
        return false;
    } else {
        $(document.registracija.email).removeClass("lose");
        $(document.registracija.email).addClass("dobro");
        return true;
    }
}

$(document).ready(function () {
    $('#dnevnik').DataTable(
            {
                "aaSorting": [[0, "asc"], [1, "asc"], [2, "asc"]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": true
            });
});





