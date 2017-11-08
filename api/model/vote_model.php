<?php
/**
 * User: imyxz
 * Date: 2017-11-08
 * Time: 14:26
 * Github: https://github.com/imyxz/
 */
class vote_model extends SlimvcModel
{
    function isUserVotedToday($user_id)
    {
        $row=$this->queryStmt("select vote_voicer_id from vote_log where user_id=? and vote_date=date(now()) limit 1",
            "i",
            $user_id)->row();
        if(!$row)   return false;
        else
            return $row['vote_voicer_id'];
    }
    function vote($user_id,$vote_voicer_id,$ip_addr,$request_headers)
    {
        $flag=$this->queryStmt("insert ignore into vote_log set user_id=?,vote_date=date(now()),vote_datetime=now(),
                                vote_voicer_id=?,ip_addr=?,request_headers=?",
            "iiss",
            $user_id,
            $vote_voicer_id,
            $ip_addr,
            $request_headers);
        if($flag&&$this->Affected==1)
            return $this->queryStmt("update voicer_info set vote_count=vote_count+1 where voicer_id=?",
                "i",
                $vote_voicer_id);
        else
            return false;
    }

}