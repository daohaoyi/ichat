<?php
class Friend_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
        session_start();
    }
    //好友名單
    public function getList() //取得好友
    {
        $query = $this->db->conn_id->prepare("SELECT friendId,account,userName,imgUrl,staus FROM friends A1,users A2
		WHERE (inviter=A2.userId OR invitee=A2.userId) AND (inviter=:inviter OR invitee=:invitee) AND A2.userId!=:userId 
		AND friendReview=1");
        $query->bindParam(":inviter", $_SESSION['userId']);
        $query->bindParam(":invitee", $_SESSION['userId']);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas = $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return false;
        }
    }
    public function addFriend($account) //增加好友
    {
        $this->load->helper('url');
        if ($account == $_SESSION["account"]) {
            echo "請不要輸入自己的帳號。";
        } else {
            $query = $this->db->conn_id->prepare("SELECT count(*) as count,userId,account,userName 
            FROM users WHERE account=:account");
            $query->bindParam(":account", $account);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data['count'] > 0) {
                $userId = $data['userId']; //對方
                $account = $data['account']; //對方
                $userName = $data['userName']; //對方
               
                $query = $this->db->conn_id->prepare("SELECT count(*) as count,inviter,invitee,friendReview
                FROM friends 
                WHERE (inviter = :inviter1 AND invitee = :invitee1) OR (inviter =:inviter2 AND invitee = :invitee2)");
                $query->execute(array(":inviter1" =>  $userId, ":invitee1" => $_SESSION['userId'],
                ":inviter2" => $_SESSION['userId'], ":invitee2" =>  $userId));
                $data = $query->fetch(PDO::FETCH_ASSOC);
                if ($data['count'] > 0) {
                    if ($data['friendReview'] == 0 &&  $data['invitee'] == $_SESSION['userId']) {
                        return "請審核對方的邀請。";
                    } elseif ($data['friendReview'] == 0 &&  $data['inviter'] == $_SESSION['userId']) {
                        return "等待" . $userName . "(" .  $account . ")" . "審核中。";
                    } elseif ($data['friendReview'] == 1) {
                        return "已與" . $userName . "(" .  $account . ")" . "好友了。";
                    }
                } else {
                    $query = $this->db->conn_id->prepare("INSERT INTO friends (inviter,invitee,friendReview) VALUES 
                    (:inviter,:invitee,0)");
                    $query->bindParam(":inviter", $_SESSION['userId']);
                    $query->bindParam(":invitee", $userId);
                    $query->execute();
                    return "成功對" . $userName . "(" .  $account . ")" . "送出好友邀請。";
                }
            } else {
                return "抱歉，搜尋不到此會員。";
            }
        }
    }
    public function delectFriend($friendId) //刪除好友
    {
        $query = $this->db->conn_id->prepare("DELETE FROM friends WHERE friendId=:friendId");
        $query->bindParam(":friendId", $friendId);
        $query->execute();
        return TRUE;
    }
    //好友名單

    //好友聊天紀錄
    public function getRecord() //取得聊天紀錄
    {
        $query = $this->db->conn_id->prepare("SELECT frmeId,fid,friendName,userName as sendName,friendMessage,newDate,temp.imgUrl FROM 
        friends_message A1,
        (SELECT max(friendTime) as newDate,A2.friendId as fid,userName as friendName,imgUrl 
		FROM friends A1,friends_message A2,users A3
        WHERE (inviter=A3.userId OR invitee=A3.userId) AND A1.friendId=A2.friendId 
        AND (inviter=:inviter OR invitee=:invitee) AND A3.userId!=:userId AND friendReview=1 group by fid) AS temp
        ,users A3
        WHERE temp.newDate =  A1.friendTime AND A3.userId=A1.userId order by friendTime desc");
        $query->bindParam(":inviter", $_SESSION['userId']);
        $query->bindParam(":invitee", $_SESSION['userId']);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function getUnread() //取得未讀訊息數量
    {
        $query = $this->db->conn_id->prepare("SELECT fid,amount FROM friends_message  A1,
		(SELECT friendId as fid,count(friendId) AS amount,max(friendTime) as newDate FROM friends_message 
		WHERE userId!=:userId AND friendReady='0' group by friendId) AS temp 
		WHERE temp.newDate =  A1.friendTime order by friendTime desc");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    //好友聊天紀錄

    //好友審核
    public function getReview() //取得好友審核資料
    {
        $query = $this->db->conn_id->prepare("SELECT A1.friendId,account,userName FROM friends A1,users A2
        WHERE (invitee=A2.userId OR inviter=A2.userId) AND invitee=:invitee AND account!=:account AND friendReview=0");
        $query->bindParam(":invitee", $_SESSION['userId']);
        $query->bindParam(":account",  $_SESSION['account']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function agreeInvite($friendId) //同意好友邀請
    {
        $query = $this->db->conn_id->prepare("UPDATE friends SET friendReview=1 WHERE friendId=:friendId");
        $query->bindParam(":friendId", $friendId);
        $query->execute();
        return TRUE;
    }
    public function refuseInvite($friendId) //刪除好友
    {
        $query = $this->db->conn_id->prepare("DELETE FROM friends WHERE friendId=:friendId");
        $query->bindParam(":friendId", $friendId);
        $query->execute();
        return TRUE;
    }
    //好友審核

    //好友聊天
    public function getMessage($friendId) //取得好友聊天訊息
    {
        $query = $this->db->conn_id->prepare("SELECT frmeId,A1.friendId,A2.userId AS sendUser,userName,imgUrl,friendMessage,friendTime,friendReady
        FROM friends A1,users A2,friends_message A3
        WHERE A1.friendId=A3.friendId AND A3.userId=A2.userId
        AND friendReview=1 AND A3.friendId=:friendId ORDER BY friendTime");
        $query->bindParam(":friendId", $friendId);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function readMessage($friendId) //已讀對方訊息功能
    {
        $query = $this->db->conn_id->prepare("UPDATE friends_message SET friendReady=1 WHERE friendId=:friendId AND userId!=:userId");
        $query->bindParam(":friendId", $friendId);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();

        $query = $this->db->conn_id->prepare("SELECT frmeId FROM friends_message WHERE friendId=:friendId AND userId=:userId AND friendReady=1");
        $query->bindParam(":friendId", $friendId);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    public function addMessage($friendId, $friendMessage, $friendTime) //發送訊息功能
    {
        $query = $this->db->conn_id->prepare("INSERT INTO friends_message (friendId,userId,friendMessage,friendTime) VALUES 
        (:friendId,:userId,:friendMessage,:friendTime)");
        $query->bindParam(":friendId", $friendId);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->bindParam(":friendMessage", $friendMessage);
        $query->bindParam(":friendTime", $friendTime);
        $query->execute();
        return TRUE;
    }
}
