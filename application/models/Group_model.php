<?php
class Group_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
        session_start();
    }
    public function getGroup()
    {
        $query = $this->db->conn_id->prepare("SELECT A2.groupId,groupName,imgUrl FROM groups_member A1,groups A2 
		WHERE A1.groupId=A2.groupId AND userId=:userId AND groupReview=1");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    public function getInvite2($groupName)
    {
        $query = $this->db->conn_id->prepare("SELECT groupId,userId,account,userName FROM friends A1,users A2,groups A3
        WHERE (inviter=A2.userId OR invitee=A2.userId) AND (invitee=:invitee OR inviter=:inviter) 
        AND friendReview=1 AND groupName=:groupName AND account!=:account");
        $query->bindParam(":invitee", $_SESSION['userId']);
        $query->bindParam(":inviter", $_SESSION['userId']);
        $query->bindParam(":groupName", $groupName);
        $query->bindParam(":account", $_SESSION['account']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 1) {
            foreach ($datas as $data) {
                $query2 = $this->db->conn_id->prepare("SELECT * FROM groups_member WHERE userId=:userId AND groupId=:groupId");
                $query2->bindParam(":userId", $data['userId']);
                $query2->bindParam(":groupId", $data['groupId']);
                $query2->execute();
                $data =  $query->fetchAll();
                if (count($data) < 1) {
                    $datas[] = $data;
                }
            }
            if (empty($datas)) {
                return FALSE;
            } else {
                return json_encode($datas, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function addMember($groupId, $inviteMember)
    {
        $stmt = $this->db->conn_id->prepare("SELECT * from groups_read where groupId=:groupId");
        $stmt->bindParam(":groupId", $groupId);
        $stmt->execute();
        $datas = $stmt->fetchAll();

        foreach ($inviteMember as $userID) {
            $query = $this->db->conn_id->prepare("INSERT INTO groups_member (groupId,userId,groupReview) VALUES 
            (:groupId,:userId,0)");
            $query->bindParam(":groupId", $groupId);
            $query->bindParam(":userId", $userID);
            $query->execute();
            foreach ($datas as $data) {
                $query = $this->db->conn_id->prepare("INSERT INTO groups_read (groupId,grmeId,userId) VALUES 
                (:groupId,:grmeId,:userId)");
                $query->bindParam(":groupId", $data['groupId']);
                $query->bindParam(":grmeId", $data['grmeId']);
                $query->bindParam(":userId", $userID);
                $query->execute();
            }
        }
        return TRUE;
    }
    public function getRecord() //取得聊天紀錄
    {
        $query = $this->db->conn_id->prepare("SELECT gid,groupName,A1.userId,userName,groupMessage,A3.imgUrl,newDate FROM groups_message A1,
		(SELECT A1.groupId as gid,max(groupTime) as newDate FROM groups_message A1,groups_member A2 
		WHERE A1.groupId=A2.groupId AND A2.userId=:userId group by A2.groupId) as temp,
		groups A3,users A4
		WHERE A4.userId=A1.userId AND A1.groupTime=temp.newDate AND A3.groupId=A1.groupId order by groupTime desc");
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
        $query = $this->db->conn_id->prepare("SELECT A2.groupId,count(A1.groupId) AS amount FROM groups_read A1,groups_member A2 
		WHERE A1.groupId=A2.groupId AND A2.userId=:userId AND A1.userId=:userId AND seen=0 group by A1.groupId");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }

    public function getReview()
    {
        $query = $this->db->conn_id->prepare("SELECT A3.groupId ,groupName FROM groups_member A1,users A2,groups A3 
		WHERE (A1.userId=A2.userId) AND (A3.groupId=A1.groupId) AND A1.userId=:userId AND groupReview=0");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    public function agreeReview($groupId)
    {
        $query = $this->db->conn_id->prepare("UPDATE groups_member SET groupReview=1 WHERE userId=:userId AND groupId=:groupId ");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->bindParam(":groupId", $groupId);
        $query->execute();
        return TRUE;
    }
    public function refuseReview($groupId)
    {
        $query = $this->db->conn_id->prepare("DELETE FROM groups_member WHERE groupId=:groupId AND userId=:userId");
        $query->bindParam(":groupId", $groupId);
        $query->bindParam(":userId", $_SESSION["userId"]);
        $query->execute();
        return TRUE;
    }
    public function addGroup($members, $groupName)
    {
        $query = $this->db->conn_id->prepare("SELECT * FROM groups WHERE groupName=:groupName");
        $query->bindParam(":groupName", $groupName);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            $config['upload_path'] = './assets/images';
            $config['allowed_types'] = 'gif|jpg|png';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('groupSticker')) {
                $img = "groupimg.jpg";
                $fileName = $img;
            } else {
                $fileName = $this->upload->data('file_name');
            }

            $query = $this->db->conn_id->prepare("INSERT INTO groups (groupName,imgUrl) VALUES 
            (:groupName,:imgUrl)");
            $query->bindParam(":groupName", $groupName);
            $query->bindParam(":imgUrl", $fileName);
            $query->execute();

            $query = $this->db->conn_id->prepare("SELECT * FROM groups WHERE groupName=:groupName");
            $query->bindParam(":groupName", $groupName);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            $query = $this->db->conn_id->prepare("INSERT INTO groups_member (groupId,userId,groupReview) VALUES 
            (:groupId,:userId,1)");
            $query->bindParam(":groupId",  $data["groupId"]);
            $query->bindParam(":userId", $_SESSION["userId"]);
            $query->execute();

            foreach ($members as $member) {
                $query = $this->db->conn_id->prepare("INSERT INTO groups_member (groupId,userId,groupReview) VALUES 
            (:groupId,:userId,0)");
                $query->bindParam(":groupId", $data["groupId"]);
                $query->bindParam(":userId", $member);
                $query->execute();
            }
            return TRUE;
        }
    }
    public function getInvite()
    {
        $query = $this->db->conn_id->prepare("SELECT inviter,invitee,account,userName FROM friends A1,users A2
		WHERE (inviter=A2.userId OR invitee=A2.userId) AND (invitee=:invitee OR inviter=:inviter) AND 
          account!=:account AND friendReview=1");
        $query->bindParam(":invitee", $_SESSION["userId"]);
        $query->bindParam(":inviter", $_SESSION["userId"]);
        $query->bindParam(":account", $_SESSION["account"]);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    public function dropOutGroup($groupId)
    {
        $query = $this->db->conn_id->prepare("DELETE FROM groups_member WHERE groupId=:groupId AND userId=:userId");
        $query->bindParam(":groupId", $groupId);
        $query->bindParam(":userId", $_SESSION["userId"]);
        $query->execute();
        return TRUE;
    }

    public function getMessage($groupId)
    {
        $query = $this->db->conn_id->prepare("SELECT A2.groupId,userName,A1.userId AS sendUser,grmeId,groupMessage,groupTime,imgUrl 
		FROM users A1,groups_message A2
		WHERE A2.userId=A1.userId AND A2.groupId=:groupId ORDER BY groupTime");
        $query->bindParam(":groupId", $groupId);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function readMessage($groupId)
    { //已讀對方訊息功能
        $query = $this->db->conn_id->prepare("UPDATE groups_read SET seen=1 WHERE userId=:userId AND groupId=:groupId");
        $query->bindParam(":userId", $_SESSION['userId']);
        $query->bindParam(":groupId", $groupId);
        $query->execute();

        $query = $this->db->conn_id->prepare("SELECT grmeId,sum(seen) as seen FROM groups_read WHERE groupId=:groupId group by grmeId");
        $query->bindParam(":groupId", $groupId);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return False;
        }
    }
    public function addMessage($groupId, $groupMessage, $groupTime)
    {
        $query = $this->db->conn_id->prepare("INSERT INTO groups_message (groupId,userId,groupMessage,groupTime) VALUES 
        (:groupId,:userId,:groupMessage,:groupTime)");
        $query->bindParam(":groupId",  $groupId);
        $query->bindParam(":userId", $_SESSION["userId"]);
        $query->bindParam(":groupMessage",  $groupMessage);
        $query->bindParam(":groupTime", $groupTime);
        $query->execute();

        $query = $this->db->conn_id->prepare("SELECT A2.groupId,A1.userId,A2.grmeId FROM groups_member A1,groups_message A2 
		WHERE A1.groupId=A2.groupId AND A1.groupId=:groupId AND A2.groupMessage=:groupMessage AND A1.userId!=:userId");
        $query->bindParam(":groupId", $groupId);
        $query->bindParam(":groupMessage", $groupMessage);
        $query->bindParam(":userId", $_SESSION["userId"]);
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $query = $this->db->conn_id->prepare("INSERT INTO groups_read (groupId,userId,grmeId,seen) VALUES 
        (:groupId,:userId,:grmeId,0)");
                $query->bindParam(":groupId",  $data["groupId"]);
                $query->bindParam(":userId", $data["userId"]);
                $query->bindParam(":grmeId",  $data["grmeId"]);
                $query->execute();
            }
            return TRUE;
        } else {
            return False;
        }
    }
}
