<?php
/**
 * User: imyxz
 * Date: 2017-10-24
 * Time: 19:45
 * Github: https://github.com/imyxz/
 */
class global_helper extends SlimvcHelper
{
    function getOpenID()
    {
        if(!isset($_SESSION['openid']) || empty($_SESSION['openid']))
        {
            $target="https://100steps.withcic.cn/2017_voicers_vote/frontend/index.html";
            $this->jumpTo("https://100steps.withcic.cn/wechat_bbt/Home/Vote/index?state=" . urlencode($target));
            exit;
        }
        return $_SESSION['openid'];
    }
    function getUserID()
    {
        $open_id=$this->getOpenID();
        /** @var user_model $user_model */
        $user_model=$this->model("user_model");
        if(!($user_id=$user_model->getUserIDByOpenID($open_id)))
            $user_id=$user_model->addNewUserByOpenID($open_id);
        return $user_id;

    }
    function jumpTo($url)
    {
        ob_clean();
        $return=array();
        $return['status']=-1;
        $return['redirect']=$url;
        header("Content-type: application/json");
        echo json_encode($return);
    }
    function getRequestHeaders()
    {
        $return=[];
        foreach($_SERVER as $key => &$one)
        {
            if(substr($key,0,5)=="HTTP_")
                $return[substr($key,5)]=$one;
        }
        return $return;
    }

}
