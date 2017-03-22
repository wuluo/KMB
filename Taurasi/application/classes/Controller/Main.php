<?php

class Controller_Main extends Controller_Base {
    public function action_index()
    {
        $this->version_key = "live";
        $this->title = "测试页面";
        $this->assign("body", "this is a body view");
        $this->display("index");
    }

}