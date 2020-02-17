<?php
class Group extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Content-Type:text/html; charset=utf-8");
        $this->load->library('form_validation');
        $this->load->model('Group_model');
        if (!isset($_SESSION["permission"])) {
            redirect('/Signin/index', 'location', 301);
        } elseif ($_SESSION["permission"] == 1) {
            redirect('/Manager/index', 'location', 301);
        }
    }
    public function index($where)
    {
        if (!file_exists('application/views/groupList.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = '我的群組';
        $data['css'] = 'style';
        $this->load->view('temp/header',  $data);
        $this->load->view('groupList');
    }
    public function getGroup() //取得群組列表
    {
        echo $this->Group_model->getGroup();
    }
    public function getInvite2() //取得二次邀請名單
    {
        $groupName = $this->input->post('groupName');
        echo $this->Group_model->getInvite2($groupName);
    }
    public function addMember() //增加二次邀請進入群組名單
    {
        $groupId = $this->input->post('groupId');
        $inviteMember = $this->input->post('inviteMember');
        echo $this->Group_model->addMember($groupId, $inviteMember);
    }
    function getRecord() //取得好友聊天紀錄
    {
        $output = '';
        if ($this->Group_model->getRecord() != false) {
            foreach ($this->Group_model->getRecord() as $data) {
                if (strrpos($data["groupMessage"], 'img src') > 0) {
                    $Message = ($data["userId"] == $_SESSION["userId"] ? "照片已傳送" : $data["userName"] . "傳送了照片");
                } else {
                    $Message = mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $data['groupMessage']), 0, 32, 'utf8');
                }
                $output .= '<a href="' . base_url('Group/inside/') . $data['gid'] . '/' . $data['groupName'] . '" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <img src="' . base_url('assets/images/') . $data['imgUrl'] . '" class="rounded-circle" alt="" width="40px" height="40px">
                    <h5 class="mb-1 align-items-center flex-grow-1">
                    ' . $data['groupName'] . '
                    </h5>
                    <span>
                    ' . $data["newDate"] . '
                    <span>
                </div>
                <div class="d-flex w-100 justify-content-start align-items-start">
                    <p class="ml-3 pl-4 mb-1 flex-grow-1">
                    ' . $Message . '
                    </p>
                    <span class="badge badge-primary badge-pill" id="unread' . $data['gid'] . '"></span>
                </div>
            </a>';
            }
            echo  $output;
        }
    }
    function getUnread() //取得好友聊天未讀數量
    {
        echo  $this->Group_model->getUnread();
    }
    public function getReview() //取得群組邀請
    {
        echo $this->Group_model->getReview();
    }
    public function agreeReview() //同意群組邀請
    {
        $groupId = $this->input->post('groupId');
        echo $this->Group_model->agreeReview($groupId);
    }
    public function refuseReview() //拒絕群組邀請
    {
        $groupId = $this->input->post('groupId');
        echo $this->Group_model->refuseReview($groupId);
    }
    public function addGroup() //增加群組列表
    {
        $members = $this->input->post('member');
        $groupName = $this->input->post('groupName');
        echo $this->Group_model->addGroup($members, $groupName);
    }
    public function getInvite() //取得邀請好友列表
    {
        echo $this->Group_model->getInvite();
    }
    public function dropOutGroup() //退出群組
    {
        $groupId = $this->input->post('groupId');
        echo $this->Group_model->dropOutGroup($groupId);
    }

    public function inside($groupId, $groupName)
    {
        if (!file_exists('application/views/groupInside.php')) {
            // 哇勒!我們沒有這個頁面!
            show_404();
        }
        $data['title'] = urldecode($groupName); // 第一個字母大寫
        $data['css'] = 'inside';
        $this->load->view('temp/header',  $data);
        $this->load->view('groupInside');
    }
    public function addMessage() //發送增加群組訊息
    {
        $groupId = $this->input->post('groupId');
        $groupMessage = $this->input->post('groupMessage');
        $groupTime = date('Y-m-d H:i:s');
        echo $this->Group_model->addMessage($groupId, $groupMessage, $groupTime);
    }
    public function getMessage() //取得群組訊息
    {
        $groupId = $this->input->post('groupId');
        $day = '';
        $output = '';
        if ($this->Group_model->getMessage($groupId) != false) {
            foreach ($this->Group_model->getMessage($groupId) as $data) {
                if ($day != date("Y-m-d", strtotime($data["groupTime"]))) {
                    $day = date("Y-m-d", strtotime($data["groupTime"]));
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
                            <p id="grmeId' . $data["grmeId"] . '" class="text-right text-nowrap font-weight-bold text-white m-0">
                            </p>
                            <p class="text-left text-nowrap font-weight-bold text-white m-0">
                            ' . date("h:i", strtotime($data["groupTime"])) . '
                            </p>
                        </div>
                        <div class="bg-white rounded align-self-center p-2">
                        ' . $data["groupMessage"] . '
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
                        ' . $data["groupMessage"] . '
                        </div>
                        <div class="align-self-end ml-2">
                            <p class="text-left text-nowrap font-weight-bold text-white m-0">
                            ' . date("h:i", strtotime($data["groupTime"])) . '
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
    public function readMessage()
    {
        $groupId = $this->input->post('groupId');
        echo $this->Group_model->readMessage($groupId);
    }
    function uploadImage() //朋友訊息圖片上傳
    {
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        $userId = $_SESSION['userId'];
        $groupId = $this->input->post('groupId');
        $groupTime = date('Y-m-d H:i:s');
        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/images/") . $this->upload->data('file_name');
            $groupMessage = '<img src="' . $src . '" class="img-thumbnail" alt="Responsive image">';
            $query = $this->db->conn_id->prepare("INSERT INTO groups_message (groupId,userId,groupMessage,groupTime) VALUES 
            (:groupId,:userId,:groupMessage,:groupTime)");
            $query->bindParam(":groupId",  $groupId);
            $query->bindParam(":userId", $userId);
            $query->bindParam(":groupMessage",  $groupMessage);
            $query->bindParam(":groupTime",  $groupTime);
            $query->execute();

            $query = $this->db->conn_id->prepare("SELECT A2.groupId,A1.userId,A2.grmeId FROM groups_member A1,groups_message A2 
            WHERE A1.groupId=A2.groupId AND A1.groupId=:groupId AND A2.groupMessage=:groupMessage AND A1.userId!=:userId");
            $query->bindParam(":groupId", $groupId);
            $query->bindParam(":groupMessage", $groupMessage);
            $query->bindParam(":userId", $_SESSION["userId"]);
            $query->execute();
            $datas = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                foreach ($datas as $data) {
                    $query = $this->db->conn_id->prepare("INSERT INTO groups_read (groupId,userId,grmeId,seen) VALUES 
                         (:groupId,:userId,:grmeId,0)");
                    $query->bindParam(":groupId",  $data["groupId"]);
                    $query->bindParam(":userId", $data["userId"]);
                    $query->bindParam(":grmeId",  $data["grmeId"]);
                    $query->execute();
                }
                echo TRUE;
            } else {
                echo False;
            }
        } else {
            echo FALSE;
        }
    }
    function uploadVideo()
    {
        $config['upload_path'] = './assets/videos';
        $config['allowed_types'] = 'mp4';
        $this->load->library('upload', $config);
        $groupId = $this->input->post('groupId');
        $groupTime = date('Y-m-d H:i:s');
        if ($this->upload->do_upload('file')) {
            $src = base_url("assets/videos/") . $this->upload->data('file_name');
            $groupMessage = '<video class="mw-100" controls>
                            <source src="' . $src . '" type="video/mp4">
                            Your browser does not support the video tag.
                            </video>';
            $query = $this->db->conn_id->prepare("INSERT INTO groups_message (groupId,userId,groupMessage,groupTime) VALUES 
                            (:groupId,:userId,:groupMessage,:groupTime)");
            $query->bindParam(":groupId",  $groupId);
            $query->bindParam(":userId", $_SESSION['userId']);
            $query->bindParam(":groupMessage",  $groupMessage);
            $query->bindParam(":groupTime",  $groupTime);
            $query->execute();

            $query = $this->db->conn_id->prepare("SELECT A2.groupId,A1.userId,A2.grmeId FROM groups_member A1,groups_message A2 
            WHERE A1.groupId=A2.groupId AND A1.groupId=:groupId AND A2.groupMessage=:groupMessage AND A1.userId!=:userId");
            $query->bindParam(":groupId", $groupId);
            $query->bindParam(":groupMessage", $groupMessage);
            $query->bindParam(":userId", $_SESSION["userId"]);
            $query->execute();

            if ($query->rowCount() > 0) {
                while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                    $query = $this->db->conn_id->prepare("INSERT INTO groups_read (groupId,userId,grmeId,seen) VALUES 
                         (:groupId,:userId,:grmeId,0)");
                    $query->bindParam(":groupId",  $data["groupId"]);
                    $query->bindParam(":userId", $data["userId"]);
                    $query->bindParam(":grmeId",  $data["grmeId"]);
                    $query->execute();
                }
                echo TRUE;
            } else {
                echo False;
            }
        } else {
            echo FALSE;
        }
    }
}
