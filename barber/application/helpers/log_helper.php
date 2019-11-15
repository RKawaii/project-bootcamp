<?php

// HELPER BUATAN SENDIRI UNTUK MENGECEK USER YANG LOGIN AGAR TIDAK DAPAT KE HALAMAN LAIN

function is_logged_in(){
    // UNTUK MEMANGGIL FRAMEWORK CI
    $ci = get_instance();

    // CEK APAKAH USER ITU SUDAH LOGIN
    if((!$ci->session->userdata("kode")) && (!$ci->session->userdata("nis"))){
        // JIKA BELUM LOGIN MAKA AKAN DILEMPAR KE AUTH YAITU HALAMAN LOGIN
        redirect("auth");
    }else{
        // JIKA SUDAH LOGIN DAPATKAN SESSION DARI LOGIN YANG BERHASIL
        $role_id = $ci->session->userdata("role_id");
        // PECAH URL NYA AMBIL SEGMENT PERTAMA YAITU CLASS CONTROLLERNYA
        $menu = $ci->uri->segment(1);

        // COCOKKAN JENIS MENU YANG SUDAH LOGIN DENGAN YANG DI DATABASE
        $queryMenu = $ci->db->get_where("user_menu", ["menu" => $menu])->row_array();

        // AMBIL MENU ID
        $menu_id = $queryMenu["id"];

        // COCOKKAN ROLE ID DAN MENU ID PADA USER YANG SUDAH LOGIN
        $userAccess = $ci->db->get_where("user_access_menu", [
            "role_id" => $role_id,
            "menu_id" => $menu_id
        ]);       
        
        // // JIKA TIDAK YANG LOGIN TIDAK SESUAI DENGAN ROLE ID DAN MENU ID MAKA AKAN DILEMPARKAN KE HALAMAN BLOK
        // // CONTOH: JIKA ADMIN YANG LOGIN, MAKA ADMIN TIDAK DAPAT MENGAKSES HALAMAN PUNYA KEUANGAN, BEGITU SEBALIKNYA
        if($userAccess->num_rows() < 1){
            redirect("auth/blok");
        }
    }
}

