<?php
class Manager_model extends CI_Model
{
    function __construct()
    {
        $this->load->database();
        session_start();
    }
    function getReport()
    {
        $query = $this->db->conn_id->prepare("SELECT A1.chmeId as chmeId,reason,userName,chatMessage 
        FROM manager_message A1,chats_message A2,users A3 
        WHERE A1.chmeId=A2.chmeId AND A2.userId=A3.userId AND verify=0");
        $query->execute();
        if ($query->rowCount() > 0) {
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $data;
            }
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return FALSE;
        }
    }
    function yesReport($chmeId, $reason)
    {
        $query = $this->db->conn_id->prepare("UPDATE manager_message SET verify=1 WHERE chmeId=:chmeId AND reason=:reason ");
        $query->bindParam(":chmeId", $chmeId);
        $query->bindParam(":reason", $reason);
        $query->execute();

        $query = $this->db->conn_id->prepare("UPDATE chats_message SET view=1 WHERE chmeId=:chmeId");
        $query->bindParam(":chmeId", $chmeId);
        $query->execute();

        return TRUE;
    }
    function noReport($chmeId)
    {
        $query = $this->db->conn_id->prepare("DELETE FROM manager_message WHERE chmeId=:chmeId");
        $query->bindParam(":chmeId", $chmeId);
        $query->execute();
        return TRUE;
    }
    function getNotice()
    {
        $query = $this->db->conn_id->prepare("SELECT userName,manoId,title,notice 
        FROM manger_notice A1,users A2
        WHERE A1.userId=A2.userId");
        $query->execute();
        if ($query->rowCount() > 0) {
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $data;
            }
            return json_encode($datas, JSON_UNESCAPED_UNICODE);
        } else {
            return FALSE;
        }
    }
    function delectNotice($manoId)
    {
        $query = $this->db->conn_id->prepare("DELETE FROM manger_notice WHERE manoId=:manoId");
        $query->bindParam(":manoId", $manoId);
        $query->execute();
        return TRUE;
    }
   
}
