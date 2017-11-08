<?php
/**
 * User: imyxz
 * Date: 2017-11-08
 * Time: 14:09
 * Github: https://github.com/imyxz/
 */
class staging extends SlimvcController
{
    function __construct()
    {
        global $Config;
        if(!$Config['Staging'])
            Slimvc::ErrorNotice("No permission",403);
    }
    function setOpenID()
    {
        try {
            $json=$this->getRequestJson();
            if(!isset($json['openID'])) throw new Exception("openID is required!");
            $openid=trim($json['openID']);
            $_SESSION['openid']=$openid;
            $return['status']=0;
            $return['data']=[];

            $this->outputJson($return);

        } catch (Exception $e) {
            $return=array();
            $return['status'] = 1;
            $return['errorMessage'] = $e->getMessage();
            $this->outputJson($return);

        }
    }
    function getOpenID()
    {
        try {
            if(!isset($_SESSION['openid']) || empty($_SESSION['openid']))
                throw new Exception("openID is empty");
            $return['status']=0;
            $return['data']=["openID"=>$_SESSION['openid']];

            $this->outputJson($return);

        } catch (Exception $e) {
            $return=array();
            $return['status'] = 1;
            $return['errorMessage'] = $e->getMessage();
            $this->outputJson($return);

        }
    }

}