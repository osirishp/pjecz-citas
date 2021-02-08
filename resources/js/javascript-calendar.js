'use strict';
let dt = new Date();

function renderDate() {
    let dateString = new Date();

    dt.setDate(1);
    let day = dt.getDay();

    let endDate = new Date(
        dt.getFullYear(),
        dt.getMonth() + 1,
        0
    ).getDate();

    let prevDate = new Date(
        dt.getFullYear(),
        dt.getMonth(),
        0
    ).getDate();

    let today = new Date();

    let months = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    ];
    var diasInhabiles = ["2021-02-01","2021-03-15","2021-03-29","2021-03-30","2021-03-31","2021-04-01","2021-04-02","2021-05-05","2021-05-10","2021-07-19","2021-07-20","2021-07-21","2021-07-22","2021-07-23","2021-07-26","2021-07-27","2021-07-28","2021-07-29","2021-07-30","2021-09-16","2021-09-17","2021-10-12","2021-11-02","2021-11-15"];

    document.getElementById("icalendarMonth").innerHTML = months[dt.getMonth()] +' '+ dt.getFullYear();
    /*document.getElementById("icalendarDateStr").innerHTML = dateString.toDateString();*/

    let cells = "";
    let countDate = 0;

    for (let x = day; x > 0; x--) {  /* dias mes anterior */
        cells += "<div class='icalendar__prev-date'>" + (prevDate - x + 1) + "</div>";
    }

    for (let i = 1; i <= endDate; i++) {
        var dia = new Date( dt.getFullYear(), dt.getMonth() , i ) ;
        var fecha = dia.toISOString().substring(0, 10) ;
        

        if (i === today.getDate() && dt.getMonth() === today.getMonth() && dt.getFullYear() === today.getFullYear()) {
            cells += "<div class='icalendar__today'><a href='javascript:void(0) ;' onclick='seleccionarDia(\"" + fecha + "\") ;' class='urlMini'  style='display:block;'>" + i + "</a></div>";
        } else {
             
             

             if( dia.getDay() == 6 || dia.getDay() == 0){
                cells += "<div class='SabadoDomingo'>" + i + "</div>";
             }
             else{
                if(dia < today){
                    cells += "<div> " + i + "</div>";
                }
                else{
                    if(diasInhabiles.indexOf(fecha) >= 0 ){
                        cells += "<div class='diaInhabil'>" + i + "</div>";
                    }
                    else{
                        cells += "<div><a href='javascript:void(0) ;'  onclick='seleccionarDia(\"" + fecha + "\") ;'  class='urlMini'  style='display:block;'>" + i + "</a></div>";    
                    }
                }
                
            }
        }

        countDate = i;
    }

    let reservedDateCells = countDate + day + 1;
    for (let j1 = reservedDateCells, j2 = 1; j1 <= 42; j1++, j2++) {  /* dias siguiente mes */
        cells += "<div class='icalendar__next-date'>" + j2 + "</div>";
    }

    document.getElementsByClassName("icalendar__days")[0].innerHTML = cells;
}

renderDate();


function moveDate(param) {
    if (param === 'prev') {
        dt.setMonth(dt.getMonth() - 1);
    } else if (param === 'next') {
        dt.setMonth(dt.getMonth() + 1);
    }

    renderDate();
}