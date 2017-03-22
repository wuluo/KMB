<?php

class Controller_CurlDemo extends Controller_Base {

    public $publicParam = array();
    public function action_index()
    {
       echo "Hello Request";
    }

    /**
     * get
     */
    public function action_get()
    {
        $uri = "http://demo.phalapi.net/";
        $param = array(
            //'id'=>'5713439b4b70a42e76a4d0da',z
            'aa'=>$this->request->query('a'),
            'showRichText'=>false,
        );
        $getParam = array_merge($this->publicParam, $param);
        try {
            $request = Request::factory($uri)->method('get')->query($getParam)->execute();
        } catch (HTTP_Exception $e) {
            echo $e->getMessage();die;
        } catch (Exception $e) {
            echo $e->getMessage();die;
        }
        echo $request->body();
    }

    /**
     * post
     */
    public function action_post()
    {
        $uri = "http://restify.io/test";
        $param = array(
            'id'=>'5713439b4b70a42e76a4d0da',
            'showRichText'=>false,
        );
        $getParam = $this->publicParam;
        try {
            $request = Request::factory($uri)->method('post')->query($getParam)->post($param)->execute();
        } catch (HTTP_Exception $e) {
            echo $e->getMessage();die;
        } catch (Exception $e) {
            echo $e->getMessage();die;
        }
        echo $request->body();
    }

    /**
     * put
     */
    public function action_put()
    {
        $uri = "http://restify.io/test";
        $param = array(
            'id'=>'5713439b4b70a42e76a4d0da',
            'showRichText'=>false,
        );
        $getParam = $this->publicParam;
        try {
            $request = Request::factory($uri)->method('put')->query($getParam)->body($param)->execute();
        } catch (HTTP_Exception $e) {
            echo $e->getMessage();die;
        } catch (Exception $e) {
            echo $e->getMessage();die;
        }
        echo $request->body();
    }
    /**
     * delete
     */
    public function action_delete()
    {
        $uri = "http://restify.io/test";
        $param = array(
            'id'=>'5713439b4b70a42e76a4d0da',
            'showRichText'=>false,
        );
        $getParam = $this->publicParam;
        try {
            $request = Request::factory($uri)->method('delete')->query($getParam)->body($param)->execute();
        } catch (HTTP_Exception $e) {
            echo $e->getMessage();die;
        } catch (Exception $e) {
            echo $e->getMessage();die;
        }
        echo $request->body();
    }
}
