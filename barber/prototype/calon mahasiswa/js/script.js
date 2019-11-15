$(document).ready(function () {
    // SIDE NAVBAR
    $('.sidenav').sidenav();

    //SLIDER IMAGE
    $('.slider').slider({
        // MODIFIKASI

        //TINGGI SLDIER
        height: 350,

        //WAKTU TRANSISI TULISAN (MILISEKON)
        transition: 600,

        //WAKTU PERPINDAHAN ANTAR GAMBAR (DETIK)
        interval: 3000
    });

    //PARALLAX
    $('.parallax').parallax();

    // MATERIAL BOX
    $('.materialboxed').materialbox();

    // TABS
    $('.tabs').tabs();

    // SELECT
    $('select').formSelect();

    // DATE PICKER
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });
});