<?php
class Chat_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
        session_start();
    }
    /////////////////////////留言板列表function
    public function addChat($chatSort, $chatName, $chatMessage, $date) //新增留言板
    {
        $query = $this->db->conn_id->prepare("SELECT count(*) as count FROM chatName WHERE chatName=:chatName");
        $query->bindParam(":chatName", $chatName);
        $query->execute();
        $rows = $query->fetch(PDO::FETCH_ASSOC);
        if ($rows['count'] > 0) {
            return FALSE;
        } else {
            $query = $this->db->conn_id->prepare("INSERT INTO chats (userId,chatName,sort,chatTime) VALUES 
           (:userId,:chatName,:sort,:chatTime)");
            $query->bindParam(":userId", $_SESSION["userId"]);
            $query->bindParam(":chatName", $chatName);
            $query->bindParam(":sort", $chatSort);
            $query->bindParam(":chatTime", $date);
            $query->execute();

            $query = $this->db->conn_id->prepare("SELECT count(*) as count,chatId FROM chats WHERE chatName=:chatName");
            $query->bindParam(":chatName", $chatName);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            $query = $this->db->conn_id->prepare("INSERT INTO chats_Message (chatId,userId,chatMessage,chmeTime) VALUES 
            (:chatId,:userId,:chatMessage,:chmeTime)");
            $query->bindParam(":chatId",  $data["chatId"]);
            $query->bindParam(":userId", $_SESSION["userId"]);
            $query->bindParam(":chatMessage", $chatMessage);
            $query->bindParam(":chmeTime", $date);
            $query->execute();
            return "聊天室創建成功";
        }
    }
    public function getAllChat($start, $limit, $motion, $find) //取得全部版
    {
        if ($motion == "new") { //最新
            $sql = "SELECT respond,chatId,chatName,chatMessage,chatTime,sort,userName,account,imgUrl,view FROM chats A1,users A2,
            (SELECT count(A2.chatId) as respond,chatMessage,chatTime as newDate,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A3
            WHERE A1.userId=A2.userId AND A3.newDate=A1.chatTime ORDER BY chatTime DESC
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
        } elseif ($motion == "hot") { //熱門
            $sql = "SELECT respond,chatId,chatName,chatMessage,chatTime,sort,userName,account,imgUrl,view FROM chats A1,users A2,
            (SELECT count(A2.chatId) as respond,chatMessage,chatTime as newDate,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A3
            WHERE A1.userId=A2.userId AND A3.newDate=A1.chatTime ORDER BY respond DESC
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
        } elseif ($motion == "search") { //搜尋
            $find = '%' . $find . '%';
            $sql = "SELECT respond,A2.chatId,A4.chatName,A4.chatMessage,A1.chatTime,sort,userName,account,imgUrl,A4.view FROM chats A1,chats_message A2,users A3,
			(SELECT count(A2.chatId) as respond,chatTime as newDate,chatName,chatMessage,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A4
			WHERE A1.chatId=A2.chatId AND A2.userId=A3.userId AND A4.newDate=A1.chatTime
			AND (A1.chatName LIKE :chatName OR A2.chatMessage LIKE :chatMessage)
			GROUP BY A2.chatId
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
            $query->bindParam(":chatName", $find);
            $query->bindParam(":chatMessage", $find);
        }
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function getSortChat($start, $limit, $sort, $motion, $find) //取得分類版
    {
        if ($motion == "new") { ////最新
            $sql = "SELECT respond,chatId,chatName,chatMessage,chatTime,sort,userName,account,imgUrl,view FROM chats A1,users A2,
            (SELECT count(A2.chatId) as respond,chatMessage,chatTime as newDate,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A3
            WHERE A1.userId=A2.userId AND A3.newDate=A1.chatTime AND sort=:sort ORDER BY chatTime DESC
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
            $query->bindParam(":sort", $sort);
        } elseif ($motion == "hot") { ////熱門
            $sql = "SELECT respond,chatId,chatName,chatMessage,chatTime,sort,userName,account,imgUrl,view FROM chats A1,users A2,
            (SELECT count(A2.chatId) as respond,chatMessage,chatTime as newDate,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A3
            WHERE A1.userId=A2.userId AND A3.newDate=A1.chatTime AND sort=:sort ORDER BY respond DESC
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
            $query->bindParam(":sort", $sort);
        } elseif ($motion == "search") { ////搜尋
            $find = '%' . $find . '%';
            $sql = "SELECT respond,A2.chatId,A4.chatName,A4.chatMessage,A1.chatTime,sort,userName,account,imgUrl,A4.view FROM chats A1,chats_message A2,users A3,
			(SELECT count(A2.chatId) as respond,chatTime as newDate,chatName,chatMessage,view FROM chats A1,chats_message A2 
            WHERE A1.chatId=A2.chatId GROUP BY A2.chatId) A4
			WHERE A1.chatId=A2.chatId AND A2.userId=A3.userId AND A4.newDate=A1.chatTime AND sort=:sort
			AND (A1.chatName LIKE :chatName OR A2.chatMessage LIKE :chatMessage)
			GROUP BY A2.chatId
            LIMIT $start, $limit";
            $query = $this->db->conn_id->prepare($sql);
            $query->bindParam(":sort", $sort);
            $query->bindParam(":chatName", $find);
            $query->bindParam(":chatMessage", $find);
        }
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    /////////////////////////留言板列表function

    /////////////////////////留言板內部function
    public function getChatMessage($chatId) //取得留言訊息
    {
        $query = $this->db->conn_id->prepare("SELECT @rank := @rank + 1 AS 'rank',good,bad,chmeId,A3.userId,chatMessage,chmeTime,chatTime,chatName,sort,account,userName,imgUrl,view,c_appraise 
		FROM chats_message A1,chats A2,users A3 ,
		(SELECT @rank := 0) A4,
		(SELECT count(*) as good from chats_message where c_appraise=1 and chatId=:chatId) A5,
		(SELECT count(*) as bad from chats_message where c_appraise=2 and chatId=:chatId) A6
		WHERE A1.chatId=A2.chatId AND A1.userId=A3.userId AND A2.chatId=:chatId ORDER BY chmeTime ASC");
        $query->bindParam(":chatId", $chatId);
        $query->bindParam(":chatId", $chatId);
        $query->bindParam(":chatId", $chatId);
        $query->execute();
        $query->execute();
        $datas =  $query->fetchAll();
        if (count($datas) > 0) {
            return $datas;
        } else {
            return False;
        }
    }
    public function addChatMeaage($chatId, $chatMessage, $date) //新增留言訊息
    {
        $chatId = $this->input->post('chatId');
        $chatMessage = $this->input->post('chatMessage');
        $date = date('Y-m-d H:i:s');
        $query = $this->db->conn_id->prepare("INSERT INTO chats_Message (chatId,userId,chatMessage,chmeTime) VALUES 
        (:chatId,:userId,:chatMessage,:chmeTime)");
        $query->bindParam(":chatId",   $chatId);
        $query->bindParam(":userId", $_SESSION["userId"]);
        $query->bindParam(":chatMessage", $chatMessage);
        $query->bindParam(":chmeTime", $date);
        $query->execute();
        return TRUE;
    }
    public function addChatReport($chmeId, $reason) //新增檢舉訊息
    {
        $query = $this->db->conn_id->prepare("SELECT userId FROM chats_message WHERE chmeId=:chmeId");
        $query->bindParam(":chmeId", $chmeId);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = $this->db->conn_id->prepare("SELECT count(*) as count,userId 
        FROM manager_message WHERE userId=:userId AND chmeId=:chmeId");
        $query->bindParam(":userId", $data['userId']);
        $query->bindParam(":chmeId", $chmeId);
        $query->execute();
        $rows = $query->fetch(PDO::FETCH_ASSOC);
        if ($rows['count'] < 1) {
            $query = $this->db->conn_id->prepare("INSERT INTO manager_message (chmeId,userId,reason,verify) VALUES 
            (:chmeId,:userId,:reason,0)");
            $query->bindParam(":userId", $data['userId']);
            $query->bindParam(":chmeId", $chmeId);
            $query->bindParam(":reason", $reason);
            $query->execute();
            return TRUE;
        }else{
            return FALSE;
        }
        
    }
    /////////////////////////留言板內部function
}
