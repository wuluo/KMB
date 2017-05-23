<?php

class Controller_Menu extends Controller_Base {
    public function action_index()
    {
		$frontMenus = Dao::factory("Menu")->getAllMenus(1);
		$adminMenus = Dao::factory("Menu")->getAllMenus(0);
		$front = $this->merge($frontMenus);
		$frontM = $this->giveSort($front);
		$admin = $this->merge($adminMenus);
		$adminM = $this->giveSort($admin);
		$this->assign([
			'fmenus'=>$frontM,
			'amenus'=>$adminM,
		]);
		$this->display();
    }

	protected function giveSort($array)
	{
		$newArr = [];
		foreach ($array as $k=>$v){
			$newArr[] = $v;
			if(!empty($v['child'])){
				foreach ($v['child'] as $ke=>$va){
					$va['name'] = '|-'.$va['name'];
					array_push($newArr, $va);
					unset($v['child'][$ke]);
				}
			}
		}
		foreach ($newArr as $k1=>$v1){
			unset($newArr[$k1]['child']);
		}
		return $newArr;
	}
	protected function merge($array,$pid=0){
		$last=array();
		foreach($array as $k=>$v){
			if($v['pid']==$pid){
				$v['child']=$this->merge($array,$v['id']);
				$v['parent_name'] = $v['name'];
				$last[]=$v;
			}
		}
		return $last;
	}

}