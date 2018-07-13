<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public $accessToken = '';  //保存AccessToken的属性
    public function __construct() {
        parent::__construct();
        $this->accessToken = get_access_token();
    }
}