$(document).ready(function () {

    //add class when checkd
    $("td:contains(√)").addClass("marked-first");

    //check in meeting table same names of interlocutors
    $("td:contains(√)").each(function () {
        markedClassFirstName = $(this).attr("class").split(' ')[0];
        markedClassSecondName = $(this).attr("class").split(' ')[1];
        $("td:not(:contains(√))").each(function () {
            unmarkedClassFirstName = $(this).attr("class").split(' ')[0];
            unmarkedClassSecondName = $(this).attr("class").split(' ')[1];
            if (unmarkedClassFirstName === markedClassSecondName && unmarkedClassSecondName === markedClassFirstName) {
                $(this).append("<span>√</span>");
                $(this).addClass("marked-second");
            }
        });
    });
});