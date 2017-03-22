<?php

class Business_Demo extends Business
{
    public function getUsers()
    {
		$object = Dao::factory('Demo')->getUsers();
        return Dao::factory('Demo')->getUsers()->current();
    }
}