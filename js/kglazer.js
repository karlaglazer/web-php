window.addEventListener("load", CheckPage);
var greske = ["0", "0", "0", "0", "0", "0", "0"];
var id = ["ime", "prezime", "korisnicko_ime", "email", "adresa", "lozinka", "potvrda"];

function CheckMail() {
    var email = document.getElementById("email").value;
    if (email !== "") {
        if (/^([A-Za-z0-9][\w\.]*[A-Za-z0-9])+@((?=[A-Za-z0-9])([A-Za-z0-9]+[\w-]*[A-Za-z0-9])+\.[\w]{2,})$/.test(email)) {
            greske[3] = "0";
        } else {
            greske[3] = "Neispravan format emaila.";
            alert("Neispravan email!");
        }
    }
    Required();
}
function Required() {
    var prazno = false;
    for (var i = 0; i < id.length; i++) {
        var element = document.getElementById(id[i]).value;
        if (element === "") {
            prazno = true;
        }
        if (greske[i] !== "0") {
            prazno = true;
        }
    }
    if (prazno) {
        document.getElementById("posalji").disabled = true;
    } else {
        document.getElementById("posalji").disabled = false;
    }
}

function CheckPassword() {
    var element = document.getElementById("lozinka").value;
    if (element.length < 8) {
        greske[5] = "Lozinka mora imati minimalno 8 znakova.";
        alert("Lozinka mora imati minimalno 8 znakova.");
    } else {
        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{1,}$/.test(element)) {
            greske[5] = "Lozinka mora sadržavati min jedno veliko slovo, jedno malo i jedan broj.";
            alert("Lozinka mora sadržavati min jedno veliko slovo, jedno malo slovo i jedan broj.");
        } else {
            greske[5] = "0";
        }
    }
    Required();
}

function CheckPasswordConfirm() {
    var lozinka = document.getElementById("lozinka").value;
    var potvrda = document.getElementById("potvrda").value;
    if (potvrda === lozinka) {
        greske[6] = "0";
    } else {
        greske[6] = "Lozinka nije ista.";
        alert("Lozinka nije ista.");
    }
    Required();
}

function onSubmit(token) {
    document.getElementById("registracija").submit();
}

function SubmitForm() {
    document.getElementById("uredi").submit();
    CheckDiv('uredivanjeDiv');
}
function CloseForm(forma) {
    document.getElementById(forma).style.display = "none";
}

function CheckDiv(forma) {
    var prikaz = document.getElementById(forma);
    if (window.getComputedStyle(prikaz).display === "none") {
        prikaz.style.display = "block";
    } else {
        prikaz.style.display = "none";
    }
}

function CheckPage() {
    var page = document.title;
    switch (page) {
        case "Registracija":
            document.getElementById("posalji").disabled = true;
            document.getElementById("ime").addEventListener("focusout", function () {
                Required();
            });
            document.getElementById("prezime").addEventListener("focusout", function () {
                Required();
            });
            document.getElementById("email").addEventListener("focusout", function () {
                CheckMail();
            });
            document.getElementById("adresa").addEventListener("focusout", function () {
                Required();
            });
            document.getElementById("lozinka").addEventListener("focusout", function () {
                CheckPassword();
            });
            document.getElementById("potvrda").addEventListener("focusout", function () {
                CheckPasswordConfirm();
            });
            break;
        case "Nekretnine":
            document.getElementById("redoslijed").addEventListener("change", function () {
                SubmitForm();
            });
            document.getElementById("novaDiv").addEventListener("click", function () {
                CheckDiv('nova'); CloseForm('uredivanje')
            });
            document.getElementById("uredivanjeDiv").addEventListener("click", function () {
                CheckDiv('uredivanje'); CloseForm('nova')
            });
            break;


    }
}
$(document).ready(function () {
    $("#korisnicko_ime").keyup(function () {
        var korisnickoime = $(this).val();
        $.ajax({
            url: 'XML.php?korisnicko_ime=' + korisnickoime,
            type: 'GET',
            dataType: 'xml',
            success: function (xml) {
                $(xml).find('korime').each(function () {
                    console.log($(this).text());
                    if ($(this).text() === "") {
                        return;
                    }
                    if (korisnickoime === $(this).text())
                    {
                        $("#posalji").prop("disabled", true);
                        $("#korisnicko_ime").css("background-color", "red");
                        alert("Korisnicko ime vec postoji.");
                    } else {
                        $("#posalji").prop("disabled", false);
                        $("#korisnicko_ime").css("background-color", "green");
                    }
                    if (korisnickoime === "") {
                        $("#posalji").prop("disabled", true);
                        $("#korisnicko_ime").css("background-color", "white");
                    }
                    Required();
                });
            }
        });

    });
});
