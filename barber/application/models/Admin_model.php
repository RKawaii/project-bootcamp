<?php

// MODEL UNTUK HALAMAN ADMIN

class Admin_model extends CI_model
{

    // METHOD UNTUK AMBIL DATA SESSION SETELAH LOGIN BERHASIL
    public function get_session()
    {
        return $this->db->get_where("data_manager", ["kode" => $this->session->userdata("kode")])->row_array();
    }

    // METHOD UNTUK MENDAPATKAN SEMUA DATA
    public function select($limit, $start, $tabel)
    {
        return $this->db->get($tabel, $limit, $start)->result_array();
    }

    // MEHOD UNTUK HAPUS SEMUA DATA RECORD
    public function hapus_record($user_role, $tabel)
    {
        $this->db->delete($tabel, ["user_role_id" => $user_role]);
    }

    // METHOD UNTUK MENDAPATKAN DATA JOIN
    public function select_record($limit, $start)
    {
        $this->db->select("`record`.*, `user_role`.`id`");
        $this->db->from("`record`");
        $this->db->join("`user_role`", "`user_role_id` = `user_role`.`id`", "inner");
        $this->db->limit($limit, $start);
        $this->db->where("`user_role_id`", 1);

        return $this->db->get()->result_array();
    }

    // METHOD UNTUK MENDAPATKAN JUMLAH DATA
    public function count_row($tabel)
    {
        return $this->db->get($tabel)->num_rows();
    }

    public function count_all($tabel1, $tabel2, $tabel3, $tabel4)
    {
        $data1 = $this->db->get($tabel1)->num_rows();

        $data2 = $this->db->get($tabel2)->num_rows();

        $data3 = $this->db->get($tabel3)->num_rows();

        $data4 = $this->db->get($tabel4)->num_rows();

        return $total = $data1 + $data2 + $data3 + $data4;
    }

    public function get_rekap($query)
    {
        return $this->db->query($query)->result();
    }
}
