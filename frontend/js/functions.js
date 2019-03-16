function fillAppliedBefore(name, surname, day, month, year, id_number, street_number, city, zip) {
    var number = "";
    var removeDebaterButton = document.getElementById("remove-debater");
    if (removeDebaterButton != null) {
        var line = removeDebaterButton.getAttribute("row");
        if (line > 0) {
            number = "-"+line;
        }
    }
    document.getElementById("name"+number).value = name;
    document.getElementById("surname"+number).value = surname;
    document.getElementById("day"+number).value = day;
    document.getElementById("month"+number).value = month;
    document.getElementById("year"+number).value = year;
    document.getElementById("op"+number).value = id_number;
    document.getElementById("street"+number).value = street_number;
    document.getElementById("city"+number).value = city;
    document.getElementById("zip"+number).value = zip;
}

function loadDebaterLine(number, language) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
            if (xmlhttp.status == 200) {
                document.getElementById("debater-line-"+number).innerHTML = xmlhttp.responseText;
                if (number < 5) {
                    if (number == 1) {
                        document.getElementById("applied-before").removeAttribute("style");
                        document.getElementById("remove-debater").removeAttribute("style");
                        document.getElementById("apply").removeAttribute("style");
                    }
                    inc = number + 1;
                    document.getElementById("next-debater").setAttribute("onclick", "loadDebaterLine("+inc+",'"+language+"')");
                } else {
                    document.getElementById("next-debater").remove();
                }
                document.getElementById("remove-debater").setAttribute("onclick", "deleteDebaterLine("+number+",'"+language+"')");
                document.getElementById("remove-debater").setAttribute("row", number);

                smartform.rebindAllForms(true, null);
            }
        }
    };

    xmlhttp.open("GET", "team-form.php?number="+number+"&lang="+language, true);
    xmlhttp.send();
}

function deleteDebaterLine(number, language) {
    document.getElementById("debater-line-"+number).innerHTML = '';
    dec = number - 1;
    document.getElementById("remove-debater").setAttribute("onclick", "deleteDebaterLine("+dec+",'"+language+"')");
    document.getElementById("remove-debater").setAttribute("row", dec);
    document.getElementById("next-debater").setAttribute("onclick", "loadDebaterLine("+number+",'"+language+"')");
}