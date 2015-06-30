<?php

namespace Admin\Model;
use Think\Model;

class ServiceCategoryModel extends Model{

 	/**
	 * 获取分类，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
	 * @return array          分类树
	 * @author 郑薏玮 <715713881@qq.com>
	 */
		public $cate;
   	 	public $alist;
   		public $treeList = array(); 
	    public function Tree(&$data, $pid = 0, $count = 1) {

            foreach ($data as $key => $value) {
                if ($value['pid'] == $pid) {
                    $value['count'] = $count;
                    $this->treeList [] = $value;
                    //unset($data[$key]);
                    $this->Tree($data, $value['id'], $count + 1);
                }
            }
            return $this->treeList;
		
 	   }

}
