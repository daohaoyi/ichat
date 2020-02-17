<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Chat_model');
        if (!isset($_SESSION["permission"])) {
            redirect('/Signin/index', 'location', 301);
        } elseif ($_SESSION["permission"] == 1) {
            redirect('/Manager/index', 'location', 301);
        }
    }

    public function list($sort, $motion) //會員登入畫面
    {
        if (!file_exists('application/views/chatList.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '討論版'; // 第一個字母大寫
        $data['css'] = 'style';
        $data['sort'] = urldecode($sort);
        $this->load->view('temp/header',  $data);
        $this->load->view('chatList');
    }
    public function addChat() //新增討論版控制器
    {
        $chatSort = $this->input->post('chatSort');
        $chatName = $this->input->post('chatName');
        $chatMessage = $this->input->post('chatMessage');
        $date = date('Y-m-d H:i:s');
        echo $this->Chat_model->addChat($chatSort, $chatName, $chatMessage, $date);
    }
    public function getChat() //顯示討論版控制器
    {
        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $sort = $this->input->post('sort');
        $motion = $this->input->post('motion');
        $find = $this->input->post('find');
        $output = '';
        if ($sort == "全部") {
            if ($this->Chat_model->getAllChat($start, $limit, $motion, $find) != false) {
                foreach ($this->Chat_model->getAllChat($start, $limit, $motion, $find) as $data) {
                    $output .= '<a href="' . base_url("Chat/inside/" . $data['chatId']) . '" class="list-group-item list-group-item-action flex-column">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex justify-content-start">
                                <img src="' . base_url("assets/images/" . $data['imgUrl']) . '" class="rounded-circle float-left" alt="無法顯示圖片" width="40px" height="40px">
                                <span class="pt-2">
                                ' . $data['sort'] . '•' . $data['userName'] . '(' . $data['account'] . ')•' . $data['chatTime'] . '
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-1 pl-1">' . mb_substr($data['chatName'], 0, 32, "utf8") . '</h3>
                        <p class="mb-1 pl-1">' . ($data['view'] == 1 ? "該訊息已被檢舉刪除": mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $data['chatMessage']), 0, 32, 'utf8')) . '</p>
                        <small class="mb-1 pl-1">回應:' . $data['respond'] . '筆</small>
                    </a>';
                }
                echo  $output;
            }
        } else {
            if ($this->Chat_model->getSortChat($start, $limit, $sort, $motion, $find) != false) {
                foreach ($this->Chat_model->getSortChat($start, $limit, $sort, $motion, $find) as $data) {
                    $output .= '<a href="' . base_url("Chat/inside/" . $data['chatId']) . '" class="list-group-item list-group-item-action flex-column">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex justify-content-start">
                                <img src="' . base_url("assets/images/" . $data['imgUrl']) . '" class="rounded-circle float-left" alt="無法顯示圖片" width="40px" height="40px">
                                <span class="pt-2">
                                ' . $data['sort'] . '•' . $data['userName'] . '(' . $data['account'] . ')•' . $data['chatTime'] . '
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-1 pl-1">' . mb_substr($data['chatName'], 0, 32, "utf8") . '</h3>
                        <p class="mb-1 pl-1">' . ($data['view'] == 1 ? "該訊息已被檢舉刪除" : mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $data['chatMessage']), 0, 32, 'utf8')) . '</p>
                        <small class="mb-1 pl-1">回應:' . $data['respond'] . '筆</small>
                    </a>';
                }
                echo  $output;
            }
        }
    }
    function uploadImage() //朋友訊息圖片上傳
    {
        // $this->load->helper('url');
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpg|png|jfif';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/images/") . $this->upload->data('file_name');
            $chatMessage = '<img src="' . $src . '" class="img-thumbnail" alt="Responsive image"></img><br><br>';
            echo $chatMessage;
        } else {
            echo FALSE;
        }
    }
    function uploadVideo() //朋友訊息影片上傳
    {
        // $this->load->helper('url');
        $config['upload_path'] = './assets/videos';
        $config['allowed_types'] = 'mp4';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/videos/") . $this->upload->data('file_name');
            $chatMessage = '<video class="mw-100" controls>
                            <source src="' . $src . '" type="video/mp4">
                            Your browser does not support the video tag.
                            </video><br><br>';
            echo $chatMessage;
        } else {
            echo FALSE;
        }
    }

    public function inside()
    {
        if (!file_exists('application/views/chatInside.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = "討論版"; // 第一個字母大寫
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('chatInside');
    }
    public function getChatMessage() //取得訊息控制器
    {
        $chatId = $this->input->post('chatId');
        $userId = $this->input->post('userId');
        $output = '';
        $check_appraise = "as";
        $count = 0;
        foreach ($this->Chat_model->getChatMessage($chatId) as $data) {
            if ($data["c_appraise"] == 1) {
                $check_appraise = "正面";
            } elseif ($data["c_appraise"] == 2) {
                $check_appraise = "負面";
            } elseif ($data["c_appraise"] == 3) {
                $check_appraise = "疑問句";
            } else {
                $check_appraise = "未判斷!請等候!";
            }
            $output .= '<div class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-start">
                <img src="' . base_url('assets/images/') . $data["imgUrl"] . '" class="rounded-circle" width="40px" height="40px">
                <span class="pt-2">
                   ' . $data["userName"] . '(' . $data["account"] . ')' . '•' . $check_appraise
                . ($count == 0 ? '•(正:' . $data["good"] . '負:' . $data["bad"] . ')' : "") . '
                </span>
            </div>
            ' . ($data["rank"] == 1 ? '<h2 class="mt-2 pl-1 pr-1">' . $data["chatName"] . '</h2>' : '') . '    
            <div class="d-flex justify-content-start mt-2 pl-1">
                <a href="' . base_url('Chat/list/') . $data["sort"] . '/new' . '">' . $data["sort"] . '</a>
                •
                <span>
                ' . $data["chmeTime"] . '
                </span>
            </div>
            <div class="mt-2 pl-1">
            ' . ($data["view"] == 0 ? $data["chatMessage"] : "該訊息已被檢舉刪除") . '    
            </div>
            ' . ($data["view"] == 0 && $data["userId"] != $userId ?
                    '<button id="' . $data["chmeId"] . '" onClick="reportMessage(this.id)" type="button" class="btn btn-danger btn-sm mt-2" data-toggle="modal" data-target="#report">
                <i class="fa fa-bomb">
                    檢舉
                </i>
             </button>' : "") . '  
        </div>';
            $count++;
        }
        echo  $output;
    }
    public function addChatMeaage() //新增訊息控制器
    {
        $chatId = $this->input->post('chatId');
        $chatMessage = $this->input->post('chatMessage');
        $date = date('Y-m-d H:i:s');
        echo $this->Chat_model->addChatMeaage($chatId, $chatMessage, $date);
    }
    public function addChatReport() //新增檢舉控制器
    {
        $chmeId = $this->input->post('chmeId');
        $reason = $this->input->post('reason');
        echo $this->Chat_model->addChatReport($chmeId, $reason);
    }
}
