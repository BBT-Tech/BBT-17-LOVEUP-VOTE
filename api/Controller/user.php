<?php
/**
 * User: imyxz
 * Date: 2017-11-08
 * Time: 15:04
 * Github: https://github.com/imyxz/
 */
class user extends SlimvcController
{
    function getVoteStatus()
    {
        try {
            $user_id=$this->helper("global_helper")->getUserID();
            /** @var user_model $user_model */
            $user_model=$this->model("user_model");
            /** @var vote_model $vote_model */
            $vote_model=$this->model("vote_model");
            /** @var var_model $var_model */
            $var_model=$this->model("var_model");

            $status=$vote_model->isUserVotedToday($user_id);
            $return=[];
            if($status)
            {
                $return=["isVote"=>true,
                "voicerID"=>$status];
            }
            else
            {
                $return=["isVote"=>false];
            }
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