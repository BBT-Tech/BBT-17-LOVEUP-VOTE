<?php
/**
 * User: imyxz
 * Date: 2017-11-08
 * Time: 15:10
 * Github: https://github.com/imyxz/
 */
class voicer_model extends SlimvcModel
{
    function getVoicerVotedInSeconds($vote_voicer_id,$seconds)
    {
        $row=$this->queryStmt("select count(*) from vote_log where vote_voicer_id=? and vote_datetime>=DATE_SUB(now(),INTERVAL ? SECOND)",
            "ii",
            $vote_voicer_id,
            $seconds)->row();
        return $row['count(*)'];
    }
    function getVoicersList()
    {
        $result=$this->query("select * from voicer_info order by voicer_id asc")->all();

        return $result;
    }
    function getVoicersListByRank()
    {
        $result=$this->query("select * from voicer_info order by vote_count desc")->all();
        $index=1;
        foreach($result as &$one)
        {
            $one['rank']=$index++;
        }
        return $result;
    }
    function getVoicerInfo($voicer_id)
    {
        $result=$this->getVoicersListByRank();//反正只有8个人，就这样写算了
        foreach($result as $one)
        {
            if($one['voicer_id']==$voicer_id)
                return $one;
        }
        return false;
    }
    function isVoicerExist($voicer_id)
    {
        $row=$this->queryStmt("select voicer_id from voicer_info where voicer_id=?",
            "i",
            $voicer_id)->row();
        return !empty($row);
    }
}