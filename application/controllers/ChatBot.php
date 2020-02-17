<?php
class ChatBot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Content-Type:text/html; charset=utf-8");
        $this->load->library('form_validation');
        $this->load->model('chat_model');
        if (!isset($_SESSION["permission"])) {
            redirect('/Signin/index', 'location', 301);
        } elseif ($_SESSION["permission"] == 1) {
            redirect('/Manager/index', 'location', 301);
        }
    }
    public function index() //留言板
    {
        if (!file_exists('application/views/chatbot.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '聊天機器人'; // 第一個字母大寫
        $data['css'] = 'inside';
        $data['name'] = '聊天機器人';
        $this->load->view('temp/header',  $data);
        $this->load->view('chatbot');
    }
    public function pythonChatBot()
    {
        $parmal = $this->input->post('Message');
        exec("python c:/xampp/htdocs/ichat/assets/chatbot/chatbot.py {$parmal}", $out, $res);
        for ($i = 0; $i < count($out); $i++) {
            echo (iconv('big5', 'utf-8', $out[$i]) . '<br>');
        }
    }
}
