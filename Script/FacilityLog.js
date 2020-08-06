$(document).ready(function () {
    $("#btn-load").on('click',function () {
        const table=$("#facility-table");
        table.fadeOut();
        table.load("Includes/LogReservation.php",{},function () {
            table.fadeIn();
        });

    });
});