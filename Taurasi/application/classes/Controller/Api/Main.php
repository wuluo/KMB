<?php

class Controller_Api_Main extends Controller_Base {
    public function action_index()
    {
        $user = Business::factory('Demo')->getUsers();
        echo "12123213";
        $this->response->body(View::factory('demo/index', [
            'user' => $user
        ]));
    }
}