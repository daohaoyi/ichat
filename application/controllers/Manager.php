<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Manager_model');
        if (!isset($_SESSION["permission"])) {
            redirect('/Signin/index', 'location', 301);
        } elseif ($_SESSION["permission"] == 0 || $_SESSION["permission"] == 2) {
            redirect('/Signin/Manager', 'location', 301);
        }
    }
    public function index($where) //會員登入畫面
    {
        if (!file_exists('application/views/managerList.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '檢舉管理'; // 第一個字母大寫
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('managerList');
    }
    public function getReport() //取得檢舉訊息列表
    {
        echo $this->Manager_model->getReport();
    }
    public function yesReport() //同意檢舉
    {
        $chmeId = $this->input->post('chmeId');
        $reason = $this->input->post('reason');
        echo $this->Manager_model->yesReport($chmeId, $reason);
    }
    public function noReport() //不同意檢舉
    {
        $chmeId = $this->input->post('chmeId');
        echo $this->Manager_model->noReport($chmeId);
    }
    public function getNotice() //取得檢舉訊息列表
    {
        echo $this->Manager_model->getNotice();
    }
    public function delectNotice() //不同意檢舉
    {
        $manoId = $this->input->post('manoId');
        echo $this->Manager_model->delectNotice($manoId);
    }
}
