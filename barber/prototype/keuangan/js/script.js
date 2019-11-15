$(document).ready(function () {
    // SIDE NAVBAR
    $('.sidenav').sidenav();

    // TABS
    $('.tabs').tabs();

    // MODAL
    $('.modal').modal();

    // SELECT
    $('select').formSelect();

    // DATE PICKER
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });
});