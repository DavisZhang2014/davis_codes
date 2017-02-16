<?php
/**
 * FileName : File.class.php
 * Author : davis
 * Create Time : 2017-02-16
 * Last Update Time: 2017-02-16
 * Reamrk:读取以\n进行行分割，\t进行列分割文件，返回数组
 */

Class File{

	protected $filename;
	protected $title_flag;
	protected $Configs = array(
		'key' => false
		);

	function __construct($filename,$title_flag = false){	//如果文件含有标题，则$title_flag = true
		$this->filename = $filename;
		$this->title_flag = $title_flag;
	}

	function setConfig($Configs){
		if(isset($Configs['key'])){		//选取设置第一维数据Key的列
			$this->Configs['key'] = $Configs['key'];
		}
	}

	function get_content(){
		if(!is_file($this->filename)){
			echo $this->filename . " is not exist.\n";
			die;
		}

		$contentArr = explode("\n", file_get_contents($this->filename));

		if($this->title_flag){	//获取文件每列标题，标题为空的舍弃
			$firstVal = explode("\t",array_shift($contentArr));
			$titles = array();
			foreach ($firstVal as $key => $value) {
				if(!empty($value)){
					$titles[$key] = $value;
				}
			}
			unset($firstVal);
		}

		//获取文件内容部分
		$newContentArr = array();
		foreach ($contentArr as $key => $value) {
			if(!empty($value)){
				$value = explode("\t", $value);
				foreach ($value as $sub_key => $sub_value) {
					if($this->Configs['key'] !== false){
						$key = trim($value[$this->Configs['key']]);
					}
					//如果标题存在,第二维键值为对应标题
					if(!empty($titles)){
						if(isset($titles[$sub_key])){
							$newContentArr[$key][$titles[$sub_key]] = $sub_value;
						}
					}else{
						$newContentArr[$key][$sub_key] = $sub_value;
					}
				}
			}
			
		}
		return $newContentArr;
	}


}