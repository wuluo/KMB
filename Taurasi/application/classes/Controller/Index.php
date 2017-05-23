<?php

class Controller_Index extends Controller_Base {
    public function action_index()
    {
    	$this->assign("k", "12");
		$this->display();
    }


}