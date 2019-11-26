<?php
/**
 * Copyright (C) 2009-2011 Laurent CLOUET
 * Author Laurent CLOUET <laurent.clouet@nopnop.net>
 **/

class Logger {
	public static function append($data_, $level_='info') {
		echo '('.strtoupper($level_).') '.$data_."\n";
	}
	
	public static function debug($data_) {
		Logger::append($data_, 'debug');
	}

	public static function info($data_) {
		Logger::append($data_, 'info ');
	}

	public static function warning($data_) {
		Logger::append($data_, 'warn ');
	}

	public static function error($data_) {
		Logger::append($data_, 'error');
	}

	public static function critical($data_) {
		Logger::append($data_, 'critical');
	}
}
