<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Signin_model');
    }
    public function index($page = 'user') //會員登入畫面
    {
        if (!file_exists('application/views/' . $page . '.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '會員登入';
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('user');
        if (isset($_SESSION["permission"]) && ($_SESSION["permission"] == 0 || $_SESSION["permission"] == 2)) {
            redirect('/Chat/list/全部/new', 'location', 301);
        } elseif (isset($_SESSION["account"], $_SESSION["permission"]) && ($_SESSION["permission"] == 1)) {
            redirect('/Manager/index/report', 'location', 301);
        }
    }
    public function signup() //會員註冊畫面
    {
        if (!file_exists('application/views/signup.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '會員註冊';
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('signup');
        if (isset($_SESSION["permission"]) && ($_SESSION["permission"] == 0 || $_SESSION["permission"] == 2)) {
            redirect('/Chat/list/全部/new', 'location', 301);
        } elseif (isset($_SESSION["account"], $_SESSION["permission"]) && ($_SESSION["permission"] == 1)) {
            redirect('/Manager/index/report', 'location', 301);
        }
    }
    public function userRegistered() //
    {
        echo $this->Signin_model->userRegistered();
    }
    public function userLogin() //
    {
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $day = date('Y-m-d H:i:s');
        echo $this->Signin_model->userLogin($account, $password, $day);
    }

    public function userLogout() //退出會員控制器
    {
        if ($this->Signin_model->userLogout()) {
            echo "登出成功";
        }
    }

    public function noticeSend() //送出聯絡管理員訊息控制器
    {
        $noticeTitle = $this->input->post('noticeTitle');
        $noticeMessage = $this->input->post('noticeMessage');
        $dateTime = date('Y-m-d H:i:s');
        echo $this->Signin_model->noticeSend($_SESSION["userId"],$noticeTitle,$noticeMessage,$dateTime);
    }

    public function manager() //管理員登入畫面
    {
        if (!file_exists('application/views/manager.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '管理員登入';
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('manager');
    }
    public function managerLogin() //
    {
        echo $this->Signin_model->managerLogin();
    }
}
