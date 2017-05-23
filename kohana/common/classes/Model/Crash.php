<?php

class Model_Crash extends Model_BaseModel {

	public function getCreateTime($format = NULL) {
		return $format ? date($format, $this->create_time) : $this->create_time;
	}

	public function getLevel() {
		switch ($this->level) {
			case 0:
				return '<span>EMERGENCY</span>';
				break;
			case 1:
				return '<span >ALERT</span>';
				break;
			case 2:
				return '<span >CRITICAL</span>';
				break;
			case 3:
				return '<span >ERROR</span>';
				break;
			case 4:
				return '<span >WARNING</span>';
				break;
			case 5:
				return '<span >NOTICE</span>';
				break;
			case 6:
				return '<span >INFO</span>';
				break;
			case 7:
				return '<span >DEBUG</span>';
				break;
			default:
				return '';
		}
		return "";
	}

	public function getFile() {
		return HTML::chars($this->file);
	}

	public function getLine() {
		return HTML::chars($this->line);
	}

	public function getMessage() {
		return HTML::chars($this->message);
	}
}
