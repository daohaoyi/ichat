<?php
class Signin_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
        session_start();
    }
    public function userLogin($account, $password,$day) //會員登入
    {
        $query = $this->db->conn_id->prepare("SELECT count(*) as count,userId,account,password,userName,imgUrl,permission 
        FROM users WHERE binary account=:account AND (permission=0 OR permission=2)");
        $query->bindParam(":account", $account);
        $query->execute();
        $data =  $query->fetch(PDO::FETCH_ASSOC);
        if ($data['count'] > 0) { //確認有該會員
            if (password_verify($password, $data["password"])) { //確認密碼輸入正確
                $query = $this->db->conn_id->prepare("UPDATE users SET staus='1'WHERE account=:account");
                $query->bindParam(":account", $account);
                $query->execute();
                $_SESSION["userId"] = $data["userId"];
                $_SESSION["account"] = $data["account"];
                $_SESSION["userName"] = $data["userName"];
                $_SESSION["imgUrl"] = $data["imgUrl"];
                //檢查權限-----------------------------------------------------------------------------
                $query = $this->db->conn_id->prepare("SELECT count(*) as count FROM manager_ban WHERE userId=:userId");
                $query->bindParam(":userId",  $_SESSION["userId"]);
                $query->execute();
                $data =  $query->fetch(PDO::FETCH_ASSOC);
                if ($data['count'] > 0) {  //禁言名單有該會員
                    if (((strtotime(date("Y-m-d h:i:s")) / (60 * 60 * 24) - strtotime($data['banTime']) / (60 * 60 * 24))) >= 7) { //比對時間是否七天
                        $query = $this->db->conn_id->prepare("DELETE FROM manager_ban WHERE userId=:userId");
                        $query->bindParam(":userId", $_SESSION["userId"]);
                        $query->execute();

                        $query = $this->db->conn_id->prepare("UPDATE users SET permission=0 WHERE account=:account");
                        $query->bindParam(":account", $_SESSION["account"]);
                        $query->execute();
                        $_SESSION["permission"] = 0;
                    } else { //沒有七天繼續禁言
                        $_SESSION["permission"] = 2;
                    }
                } else { //檢查檢舉名單是否有會員
                    $query = $this->db->conn_id->prepare("SELECT count(*) as count,A1.chmeId,A2.userId,reason 
                    FROM manager_message A1,chats_message A2 
                    WHERE A1.chmeId=A2.chmeId AND A2.userId=:userId AND verify=1");
                    $query->bindParam(":userId",  $_SESSION["userId"]);
                    $query->execute();
                    $data =  $query->fetch(PDO::FETCH_ASSOC);
                    if ($data['count'] > 2) { //檢舉名單有該會員且達到三筆紀錄
                        $query = $this->db->conn_id->prepare("DELETE FROM manager_message WHERE userId=:userId");
                        $query->bindParam(":userId", $_SESSION["userId"]);
                        $query->execute();

                        $query = $this->db->conn_id->prepare("UPDATE users SET permission=2 WHERE userId=:userId");
                        $query->bindParam(":userId", $_SESSION["userId"]);
                        $query->execute();

                        $query = $this->db->conn_id->prepare("INSERT INTO manager_ban (userId,banTime) VALUES 
                        (:userId,:banTime)");
                        $query->bindParam(":userId", $_SESSION["userId"]);
                        $query->bindParam(":banTime", $day);
                        $query->execute();
                        $_SESSION["permission"] = 2;
                    } else { //檢舉名單有會員但沒超過三筆
                        $_SESSION["permission"] = 0;
                    }
                }
                //檢查權限-----------------------------------------------------------------------------
                if ($_SESSION["permission"] == 0) {
                    return $_SESSION["userName"] . "(" . $_SESSION["account"] . ")" . "登入成功";
                } elseif ($_SESSION["permission"] == 2) {
                    return $_SESSION["userName"] . "(" . $_SESSION["account"] . ")" . "登入成功(禁言中)";
                }
            }
        } else {
            return FALSE;
        }
    }
    public function userRegistered() //會員註冊
    {
        //變數宣告
        $name = $this->input->post('name');
        $account = $this->input->post('account');
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $email = $this->input->post('email');
        $gender = $this->input->post('gender');
        //上傳檔案設定
        $config['upload_path'] = './assets/images'; //路徑
        $config['allowed_types'] = 'gif|jpg|png'; //檔案型態
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('headStick')) {
            $img = "userimg.png";
            $fileName = $img;
        } else {
            $fileName = $this->upload->data('file_name');
        }
        $stmt = $this->db->conn_id->prepare("SELECT count(*) as count FROM users WHERE binary account=:account");
        $stmt->bindParam(":account", $account);
        $stmt->execute();
        $data =  $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data['count'] > 0) {
            return FALSE;
        } else {
            $stmt = $this->db->conn_id->prepare("INSERT INTO users (userName,account,password,email,gender,imgUrl,staus,permission) VALUES 
            (:userName,:account,:password,:email,:gender,:imgUrl,0,0)");
            $stmt->bindParam(":userName", $name);
            $stmt->bindParam(":account", $account);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":gender", $gender);
            $stmt->bindParam(":imgUrl", $fileName);
            $stmt->execute();
            return "會員註冊成功";
        }
    }
    public function managerLogin()
    {
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $query = $this->db->conn_id->prepare("SELECT count(*) as count,userId,account,password,userName,imgUrl,permission 
        FROM users WHERE account=:account AND permission=1");
        $query->bindParam(":account", $account);
        $query->execute();
        $data =  $query->fetch(PDO::FETCH_ASSOC);
        if ($data['count']> 0) {
            if (password_verify($password, $data['password'])) {
                $query = $this->db->conn_id->prepare("UPDATE users SET staus=1 WHERE account=:account");
                $query->bindParam(":account", $data['account']);
                $query->execute();
                $_SESSION["userId"] = $data["userId"];
                $_SESSION["account"] = $data["account"];
                $_SESSION["userName"] = $data["userName"];
                $_SESSION["imgUrl"] = $data["imgUrl"];
                $_SESSION["permission"] =  $data["permission"];
                return $_SESSION["userName"] . "(" . $_SESSION["account"] . ")" . "管理員登入";
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }
    public function noticeSend($userId,$title,$notice,$dateTime){
        $query = $this->db->conn_id->prepare("INSERT INTO manger_notice (userId,title,notice,dateTime) VALUES 
            (:userId,:title,:notice,:dateTime)");
            $query->bindParam(":userId", $userId);
            $query->bindParam(":title", $title);
            $query->bindParam(":notice", $notice);
            $query->bindParam(":dateTime", $dateTime);
            $query->execute();
            return "訊息發送成功";
    }
    public function userLogout()
    {
        $query = $this->db->conn_id->prepare("UPDATE users SET staus=0 WHERE account=:account");
        $query->bindParam(":account", $_SESSION["account"]);
        $query->execute();
        session_destroy();
        return TRUE;
    }
}
