<?php
/**
 * User: imyxz
 * Date: 2017-11-08
 * Time: 15:08
 * Github: https://github.com/imyxz/
 */
class vote extends SlimvcController
{
    function getVoicersList()
    {
        try {
            $user_id=$this->helper("global_helper")->getUserID();
            /** @var user_model $user_model */
            $user_model=$this->model("user_model");
            /** @var vote_model $vote_model */
            $vote_model=$this->model("vote_model");
            /** @var var_model $var_model */
            $var_model=$this->model("var_model");
            /** @var voicer_model $voicer_model */
            $voicer_model=$this->model("voicer_model");
            $json=$this->getRequestJson();

            $list=$voicer_model->getVoicersListByRank();
            $data=array();
            foreach($list as $one)
            {
                $data[]=array("voicerID"=>$one['voicer_id'],
                    "name"=>$one['voicer_name'],
                    "voteCount"=>$one['vote_count'],
                    "rank"=>$one['rank']);
            }
            $return['data']=$data;
            $return['status']=0;
            $this->outputJson($return);

        } catch (Exception $e) {
            $return=array();
            $return['status'] = 1;
            $return['errorMessage'] = $e->getMessage();
            $this->outputJson($return);

        }
    }
    function getVoicer()
    {
        try {
            $user_id=$this->helper("global_helper")->getUserID();
            /** @var user_model $user_model */
            $user_model=$this->model("user_model");
            /** @var vote_model $vote_model */
            $vote_model=$this->model("vote_model");
            /** @var var_model $var_model */
            $var_model=$this->model("var_model");
            /** @var voicer_model $voicer_model */
            $voicer_model=$this->model("voicer_model");
            $voicer_id=intval($_GET['id']);
            $one=$voicer_model->getVoicerInfo($voicer_id);
            if(!$one)   throw new Exception("没有这个选手哦");
            $data=array("voicerID"=>$one['voicer_id'],
                "name"=>$one['voicer_name'],
                "voteCount"=>$one['vote_count'],
                "rank"=>$one['rank']);
            $return['data']=$data;
            $return['status']=0;
            $this->outputJson($return);

        } catch (Exception $e) {
            $return=array();
            $return['status'] = 1;
            $return['errorMessage'] = $e->getMessage();
            $this->outputJson($return);

        }
    }
    function loveUp()
    {
        try {
            global $Config;

            $allow=$Config['AllowRefererPrefix'];
            if(!empty($headers['REFERER']) && substr($headers['REFERER'],0,strlen($allow))!=$allow)
                throw new Exception("CSRF detected!");

            $user_id=$this->helper("global_helper")->getUserID();
            /** @var user_model $user_model */
            $user_model=$this->model("user_model");
            /** @var vote_model $vote_model */
            $vote_model=$this->model("vote_model");
            /** @var var_model $var_model */
            $var_model=$this->model("var_model");
            /** @var voicer_model $voicer_model */
            $voicer_model=$this->model("voicer_model");
            $headers=$this->helper("global_helper")->getRequestHeaders();



            if($var_model->getValue("VOTE_STATUS")!=1)
                throw new Exception("现在不是在投票时间段内哦");

            $json=$this->getRequestJson();
            $voicer_id=intval($json['voicerID']);


            if(!$voicer_model->isVoicerExist($voicer_id))   throw new Exception("选手不存在哦");

            if($vote_model->isUserVotedToday($user_id))
                throw new Exception("您今天已经投过票了！请明天再来");
            $limit_seconds=intval($var_model->getValue("VOTE_LIMIT_PERIOD"));
            if($limit_seconds>0)
            {
                $limit_count=intval($var_model->getValue("VOTE_LIMIT_TICKET"));
                $votes=$voicer_model->getVoicerVotedInSeconds($voicer_id,$limit_seconds);
                if($votes>=$limit_count)    throw new Exception("当前太多人投TA啦，请稍后再试");
            }
            $flag=$vote_model->vote($user_id,$voicer_id,$_SERVER['REMOTE_ADDR'],json_encode($headers));
            if(!$flag)  throw new Exception("投票失败，系统出错！");

            $one=$voicer_model->getVoicerInfo($voicer_id);
            $data=array("voicerID"=>$one['voicer_id'],
                "name"=>$one['voicer_name'],
                "voteCount"=>$one['vote_count'],
                "rank"=>$one['rank']);

            $return['data']=$data;
            $return['status']=0;
            $this->outputJson($return);

        } catch (Exception $e) {
            $return=array();
            $return['status'] = 1;
            $return['errorMessage'] = $e->getMessage();
            $this->outputJson($return);

        }
    }
}