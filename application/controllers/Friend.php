<?php
class Friend extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Content-Type:text/html; charset=utf-8");
        $this->load->library('form_validation');
        $this->load->model('Friend_model');
        if (!isset($_SESSION["permission"])) {
            redirect('/Signin/index', 'location', 301);
        } elseif ($_SESSION["permission"] == 1) {
            redirect('/Manager/index', 'location', 301);
        }
    }
    public function index($where)
    {
        if (!file_exists('application/views/friendList.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '我的好友';
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('friendList');
    }
    //好友名單的方法
    public function getList() //取得好友列表
    {
        $output = '';
        if ($this->Friend_model->getList() != false) {
            foreach ($this->Friend_model->getList() as $data) {
                $output .= '<button type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#friendLsitModal' . $data["friendId"] . '">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <img src="' . base_url("assets/images/") . $data['imgUrl'] . '" class="rounded-circle mr-3" alt="" width="40px" height="40px">
                    <h5 class="mb-1 align-items-center flex-grow-1">
                        ' . $data["userName"] . '
                    </h5>
                    <span class="badge ' . ($data["staus"] == 1 ? "badge-success" : "badge-danger") . '">
                    ' . ($data["staus"] == 1 ? "上線中" : "下線中") . '
                    </span>
                </div>
            </button>
            <div class="modal fade" id="friendLsitModal' . $data["friendId"] . '" tabindex="-1" role="dialog" aria-labelledby="friendLsitModalLabel' . $data["friendId"] . '" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="friendLsitModalLabel' . $data["friendId"] . '"> ' . $data["userName"] . '</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="' . base_url('Friend/inside/') . $data['friendId'] . '/' . $data['userName'] . '" class="btn btn-success btn-block">
                            聊天
                        </a>
                        <button type="button" class=" btn btn-danger btn-block" data-toggle="modal" data-target="#delectFriend' . $data["friendId"] . '" data-dismiss="modal">
                            刪除
                        </button>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delectFriend' . $data["friendId"] . '" tabindex="-1" role="dialog" aria-labelledby="delectFriend" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delectFriend">刪除好友</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    確定要刪除好友?
                </div>
                <div class="modal-footer">
                    <button id="' . $data["friendId"] . '" onClick="delectFriend(this.id)" type="submit" class="btn btn-primary">確定</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>';
            }
            echo  $output;
        }
    }
    public function addFriend() //發送增加朋友訊息
    {
        $account = $this->input->post('account');
        print_r($this->Friend_model->addFriend($account));
    }
    public function delectFriend() //刪除好友
    {
        $friendId = $this->input->post('friendId');
        echo $this->Friend_model->delectFriend($friendId);
    }
    //好友名單

    //好友聊天紀錄
    function getRecord() //取得好友聊天紀錄
    {
        $output = '';
        if ($this->Friend_model->getRecord() != false) {
            foreach ($this->Friend_model->getRecord() as $data) {
                if (strrpos($data["friendMessage"], 'img src') > 0) {
                    $Message = ($data["sendName"] == $data["friendName"] ? "照片已傳送" : $data["sendName"] . "傳送了照片");
                } else {
                    $Message = mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $data['friendMessage']), 0, 32, 'utf8');
                }
                $output .= '<a href="' . base_url('Friend/inside/') . $data['fid'] . '/' . $data['friendName'] . '" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <img src="' . base_url('assets/images/') . $data['imgUrl'] . '" class="rounded-circle" alt="" width="40px" height="40px">
                    <h5 class="mb-1 align-items-center flex-grow-1">
                    ' . $data['friendName'] . '
                    </h5>
                    <span>
                    ' . $data["newDate"] . '
                    <span>
                </div>
                <div class="d-flex w-100 justify-content-start align-items-start">
                    <p class="ml-3 pl-4 mb-1 flex-grow-1">
                    ' . $Message . '
                    </p>
                    <span class="badge badge-primary badge-pill" id="unread' . $data['fid'] . '"></span>
                </div>
            </a>';
            }
            echo  $output;
        }
    }
    function getUnread() //取得好友聊天未讀數量
    {
        echo  $this->Friend_model->getUnread();
    }
    //好友聊天紀錄的方法

    // 好友審核
    function getReview() //取得好友審核
    {
        $output = '';
        if ($this->Friend_model->getReview() != false) {
            foreach ($this->Friend_model->getReview() as $data) {
                $output .= '<div class="list-group-item d-flex justify-content-between align-items-center">
                <p class="h4 flex-grow-1">
                ' . $data["userName"] . '( ' . $data["account"] . ')
                </p>
                <button type="button" id="' . $data["friendId"] . '" onclick="agree_Invite_Ajax(this.id)" class="btn btn-primary mr-1">
                    同意
                </button>
                <button type="button" id="' . $data["friendId"] . '" onclick="refuse_Invite_Ajax(this.id)" class="btn btn btn-danger">
                    拒絕
                </button>
            </div>';
            }
            echo  $output;
        }
    }
    function agreeInvite() //好友邀請同意
    {
        $friendId = $this->input->post('friendId');
        echo $this->Friend_model->agreeInvite($friendId);
    }
    public function refuseInvite() //刪除好友
    {
        $friendId = $this->input->post('friendId');
        echo $this->Friend_model->refuseInvite($friendId);
    }
    // 好友審核

    public function inside($friendId, $friendName)
    {
        if (!file_exists('application/views/friendInside.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = urldecode($friendName); // 第一個字母大寫
        $data['css'] = 'inside';
        $this->load->view('temp/header',  $data);
        $this->load->view('friendInside');
    }
    function getMessage() //取的好友聊天訊息
    {
        $friendId = $this->input->post('friendId');
        $day = '';
        $output = '';
        if ($this->Friend_model->getMessage($friendId) != FALSE) {
            foreach ($this->Friend_model->getMessage($friendId) as $data) {
                if ($day != date("Y-m-d", strtotime($data["friendTime"]))) {
                    $day = date("Y-m-d", strtotime($data["friendTime"]));
                    if ($day == date("Y-m-d")) {
                        $output .= '<div class="row d-flex justify-content-center my-3" style="opacity:0.8">
                        <div class="col-8 bg-white rounded d-flex justify-content-center">
                            今天
                        </div> 
                    </div>';
                    } else {
                        $output .= '<div class="row d-flex justify-content-center my-3" style="opacity:0.8">
                        <div class="col-8 bg-white rounded d-flex justify-content-center">
                            ' . $day . '
                        </div> 
                    </div>';
                    }
                }
                if ($data["sendUser"] == $_SESSION["userId"]) {
                    $output .= '<div class="row my-4 d-flex justify-content-center">
                <div class="col-12 col-md-10 d-flex justify-content-end">
                    <div class="d-flex justify-content-start">
                        <div class="align-self-end mr-2">
                            <p id="frmeId' . $data["frmeId"] . '" class="text-right text-nowrap font-weight-bold text-white m-0">
                            </p>
                            <p class="text-left text-nowrap font-weight-bold text-white m-0">
                            ' . date("h:i", strtotime($data["friendTime"])) . '
                            </p>
                        </div>
                        <div class="bg-white rounded align-self-center p-2">
                        ' . $data["friendMessage"] . '
                        </div>
                    </div>
                </div>
            </div>';
                } elseif ($data["sendUser"] != $_SESSION["userId"]) {
                    $output .= ' <div class="row my-4 d-flex justify-content-center">
                    <div class="col-12 col-md-10 d-flex justify-content-start">
                    <div class="d-flex justify-content-start">
                    <img src="' . base_url('assets/images/') . $data["imgUrl"] . '" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">
                        <div class="bg-white rounded align-self-center p-2">
                        ' . $data["friendMessage"] . '
                        </div>
                        <div class="align-self-end ml-2">
                            <p class="text-left text-nowrap font-weight-bold text-white m-0">
                            ' . date("h:i", strtotime($data["friendTime"])) . '
                            </p>
                         </div>
                    </div>
                </div> 
                </div>';
                }
            }
            echo  $output;
        }
    }
    function readMessage() //已讀朋友訊息
    {
        $friendId = $this->input->post('friendId');
        echo $this->Friend_model->readMessage($friendId);
    }
    function addMessage() //發送增加朋友訊息
    {
        $friendId = $this->input->post('friendId');
        $friendMessage = $this->input->post('friendMessage');
        $friendTime = date('Y-m-d H:i:s');
        echo $this->Friend_model->addMessage($friendId, $friendMessage, $friendTime);
    }
    function uploadImage() //朋友訊息圖片上傳
    {
        // $this->load->helper('url');
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        $friendId = $this->input->post('friendId');

        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/images/") . $this->upload->data('file_name');
            $friendMessage = '<img src="' . $src . '" class="img-thumbnail" alt="Responsive image"></img>';
            $data = array(
                'friendId' => $friendId,
                'userId' => $_SESSION["userId"],
                'friendMessage' => $friendMessage,
                'friendTime' => date('Y-m-d H:i:s')
            );
            $this->db->insert('friends_message', $data);
            echo TRUE;
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
        $friendId = $this->input->post('friendId');

        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/videos/") . $this->upload->data('file_name');
            $friendMessage = '<video class="mw-100" controls>
                            <source src="' . $src . '" type="video/mp4">
                            Your browser does not support the video tag.
                            </video>';
            $data = array(
                'friendId' => $friendId,
                'userId' => $_SESSION["userId"],
                'friendMessage' => $friendMessage,
                'friendTime' => date('Y-m-d H:i:s')
            );
            $this->db->insert('friends_message', $data);
            echo TRUE;
        } else {
            echo FALSE;
        }
    }
    //platform block
}
