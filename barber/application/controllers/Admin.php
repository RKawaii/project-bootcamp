<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('./excel/vendor/autoload.php');
//require('./simponi/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// CONTROLLER UNTUK HALAMAN ADMIN

// HALAMAN KEUANGAN BERISI HALAMAN DATA AKUN, HALAMAN DATA CALON MAHASISWA, HALAMAN DOKUMEN DAN ADMIN (HOME)

class Admin extends CI_Controller
{

    // METHOD KONSTRUKTOR UNTUK LOAD MODEL
    public function __construct()
    {
        // LOAD PARENT CI_CONTROLLER
        parent::__construct();
        // AMBIL SESSION, DI PROSES DI MODEL
        $data["datamanager"] = $this->Admin_model->get_session();
        // PERIKSA USER YANG LOGIN AGAR TIDAK DAPAT KE HALAMAN USER LAIN
        is_logged_in();
    }

    public function pagination($tabel, $url)
    {
        // PAGINATION
        $config["base_url"] = base_url() . $url;
        $config["total_rows"] = $this->Admin_model->count_row($tabel);
        $config["per_page"] = 10;

        // PAGINATION STYLE
        $config["full_tag_open"] = "<ul class='pagination'>";
        $config["full_tag_close"] = "</ul>";

        $config["first_link"] = "First";
        $config["first_tag_open"] = "<li class='waves-effect'>";
        $config["first_tag_close"] = "</li>";

        $config["last_link"] = "Last";
        $config["last_tag_open"] = "<li class='waves-effect'>";
        $config["last_tag_close"] = "</li>";

        $config["next_link"] = "<i class='material-icons'>chevron_right</i>";
        $config["next_tag_open"] = "<li class='waves-effect'>";
        $config["next_tag_close"] = "</li>";

        $config["prev_link"] = "<i class='material-icons'>chevron_left</i>";
        $config["prev_tag_open"] = "<li class='waves-effect'>";
        $config["prev_tag_close"] = "</li>";

        $config["cur_tag_open"] = "<li class='waves-effect active'><a>";
        $config["cur_tag_close"] = "</a></li>";

        $config["num_tag_open"] = "<li class='waves-effect'>";
        $config["num_tag_close"] = "</li>";

        return $this->pagination->initialize($config);
    }

    // METHOD UNTUK MENGHAPUS SEMUA RECORD
    public function hapus_record()
    {
        $this->Admin_model->hapus_semua(1, "record");
        redirect("admin");
    }

    // HALAMAN DEFAULT: ADMIN
    public function index()
    {
        $data["judul"] = "Beranda";

        $pagination_record = $this->pagination("record", "admin/index");
        $data["start"] = $this->uri->segment(3);

        // AMBIL BEBERAPA DATA
        $data["akun"] = $this->Admin_model->count_row("data_akun");
        $data["dokumen"] = $this->Admin_model->count_row("data_dokumen");
        $data["pendaftaran"] = $this->Admin_model->count_all("data_diri", "data_ortu", "data_pendidikan", "data_prodi");

        $data["record"] = $this->Admin_model->select_record($pagination_record->per_page, $data["start"]);

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar");
        $this->load->view("admin/index", $data);
        $this->load->view("templates/footer");
    }

    // HALAMAN DATA AKUN
    public function akun()
    {
        $data["judul"] = "Data Akun";

        $pagination_akun = $this->pagination("data_akun", "admin/akun");
        $data["start"] = $this->uri->segment(3);

        // JIKA ADA PENCARIAN DATA AKUN
        $data["akun"] = $this->Admin_model->select($pagination_akun->per_page, $data["start"], "data_akun");

        if ($this->input->post("keyword")) {
            $keyword = $this->input->post("keyword");
            $data["akun"] = $this->Data_akun_model->cari($keyword, "data_akun");
        }

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar");
        $this->load->view("admin/akun", $data);
        $this->load->view("templates/footer");
    }

    // HALAMAN DATA PENDAFTARAN
    public function pendaftaran()
    {
        $data["judul"] = "Data Pendaftaran";

        $pagination_diri = $this->pagination("data_diri", "admin/pendaftaran");
        $pagination_ortu = $this->pagination("data_ortu", "admin/pendaftaran");
        $pagination_pendidikan = $this->pagination("data_pendidikan", "admin/pendaftaran");
        $pagination_prodi = $this->pagination("data_prodi", "admin/pendaftaran");

        $data["start"] = $this->uri->segment(3);

        // AMBIL SEMUA DATA PENDAFTARAN
        $data["data_diri"] = $this->Data_diri_model->select($pagination_diri->per_page, $data["start"], "data_diri");
        $data["data_ortu"] = $this->Data_ortu_model->select($pagination_ortu->per_page, $data["start"], "data_ortu");
        $data["data_pendidikan"] = $this->Data_pendidikan_model->select($pagination_pendidikan->per_page, $data["start"], "data_pendidikan");
        $data["data_prodi"] = $this->Data_prodi_model->select($pagination_prodi->per_page, $data["start"], "data_prodi");

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar");
        $this->load->view("admin/pendaftaran", $data);
        $this->load->view("templates/tabs/data_diri_tab", $data);
        $this->load->view("templates/tabs/data_ortu_tab", $data);
        $this->load->view("templates/tabs/data_pendidikan_tab", $data);
        $this->load->view("templates/tabs/data_prodi_tab", $data);
        $this->load->view("templates/footer");
    }

    // HALAMAN DATA DOKUMEN
    public function dokumen()
    {
        $data["judul"] = "Data Dokumen";

        $pagination_dokumen = $this->pagination("data_dokumen", "admin/dokumen");

        $data["start"] = $this->uri->segment(3);

        // JIKA ADA PENCARIAN DATA AKUN
        $data["dokumen"] = $this->Data_dokumen_model->select($pagination_dokumen->per_page, $data["start"], "data_dokumen");

        if ($this->input->post("keyword")) {
            $keyword = $this->input->post("keyword");
            $data["dokumen"] = $this->Data_dokumen_model->cari($keyword, "data_dokumen");
        }

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar");
        $this->load->view("admin/dokumen", $data);
        $this->load->view("templates/footer");
    }

    //halaman rekap
    public function rekap()
    {
        $data["judul"] = "Rekap";
        $data["jurusan"] = $this->Pendaftar_model->select("jurusan");

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar");
        $this->load->view("admin/rekap", $data);
        $this->load->view("templates/footer");
    }

    //proses pembuatan
    public function buat_rekap()
    {
        //query builder
        $Qtahun = " AND YEAR(data_akun.tanggal_daftar) = " . $this->input->post('tahun');
        $Qbayar = '';
        if ($this->input->post('pembayaran') == "all") { } else {
            if ($this->input->post('pembayaran') == "sudah") {
                $Qbayar = " AND data_akun.sudah_bayar = 1";
            } else if ($this->input->post('pembayaran') == "belum") {
                $Qbayar = " AND data_akun.sudah_bayar = 0";
            }
        }
        $Qjurusan = '';
        if ($this->input->post('jurusan') == "all") { } else {
            $Qbayar = ' AND data_prodi.jurusan = "' . $this->input->post('jurusan') . '"';
        }

        $Qjenjang = ' AND data_prodi.jenjang = "' . $this->input->post('jenjang') . '"';


        $Query = "select data_akun.nis,data_diri.nama_lengkap,data_diri.alamat,data_diri.tanggal_lahir,data_diri.telepon,data_diri.jenis_kelamin,data_diri.agama,data_prodi.jurusan,data_prodi.kelas,data_prodi.jenjang,data_akun.tanggal_daftar,data_akun.sudah_bayar from data_akun join data_diri on data_akun.id = data_diri.data_akun_id join data_prodi on data_prodi.data_akun_id = data_akun.id WHERE data_akun.daftar = 1" . $Qtahun . $Qbayar . $Qjurusan . $Qjenjang . "";

        $data = $this->Admin_model->get_rekap($Query);
        // end query builder



        //buat spreadsheet
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        // masukan data dalam excel
        $sheet->setCellValue('A1', 'Data Rekap Tanggal: ' . Date("d/m/Y"));
        $sheet->setCellValue('A2', 'NIS');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Alamat');
        $sheet->setCellValue('D2', 'Tanggal Lahir');
        $sheet->setCellValue('E2', 'Telepon');
        $sheet->setCellValue('F2', 'Jenis Kelamin');
        $sheet->setCellValue('G2', 'Agama');
        $sheet->setCellValue('H2', 'Jurusan');
        $sheet->setCellValue('I2', 'Kelas');
        $sheet->setCellValue('J2', 'Jenjang');
        $sheet->setCellValue('K2', 'tanggal daftar');
        $sheet->setCellValue('L2', 'pembayaran');

        $i = 3;

        foreach ($data as $dataIns) {

            $sheet->setCellValue('A' . $i, $dataIns->nis);
            $sheet->setCellValue('B' . $i, $dataIns->nama_lengkap);
            $sheet->setCellValue('C' . $i, $dataIns->alamat);
            $sheet->setCellValue('D' . $i, $dataIns->tanggal_lahir);
            $sheet->setCellValue('E' . $i, $dataIns->telepon);
            $sheet->setCellValue('F' . $i, $dataIns->jenis_kelamin);
            $sheet->setCellValue('G' . $i, $dataIns->agama);
            $sheet->setCellValue('H' . $i, $dataIns->jurusan);
            $sheet->setCellValue('I' . $i, $dataIns->kelas);
            $sheet->setCellValue('J' . $i, $dataIns->jenjang);
            $sheet->setCellValue('K' . $i, $dataIns->tanggal_daftar);
            if ($dataIns->sudah_bayar == 1) {
                $sheet->setCellValue('L' . $i, 'Sudah Bayar');
            } else if ($dataIns->sudah_bayar == 0) {
                $sheet->setCellValue('L' . $i, 'Belum Bayar');
            } else {
                $sheet->setCellValue('L' . $i, '#ERROR');
            }


            $i++;
        }


        $writer = new Xlsx($spreadsheet); // ubah spreadsheet menjadi excel

        $filename = 'rekap-' . Date("d-m-Y"); // set nama file

        //append excel ke header
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // lakukan download file
    }
}
