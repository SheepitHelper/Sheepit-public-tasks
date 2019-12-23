<?php
/**
 * Copyright (C) 2013 Laurent CLOUET
 * Author Laurent CLOUET <laurent.clouet@nopnop.net>
 *
 **/

require_once(__DIR__.'/BlenderReader.class.php');
require_once(__DIR__.'/Logger.class.php');

class BlendReaderWithPython extends BlenderReader {
	static protected $script28x = "
import blendfile
import sys
import json

def imtype_to_file_extension(i):
	switcher={
		0:'.tga',
		4:'.jpg',
		##define R_IMF_IMTYPE_IRIZ 7
		14: '.tga'
		15: '.avi'
		16: '.avi',
		17: '.png',
		20: '.bmp',
		##define R_IMF_IMTYPE_RADHDR 21
		22: '.tif'
		23: '.exr'
		26: '.cin',
		27: '.dpx'
		28: '.exr'
		29: '.dds',
		30: '.jp2',
		34: '.psd'
	}
	return switcher.get(i, \"Invalid\")

output_file = sys.argv[2]
filepath = sys.argv[1]
with blendfile.open_blend(filepath) as blend:
	scenes = [b for b in blend.blocks if b.code == b'SC']
	for scene in scenes:
		# see RNA_def_property in rna_scene.c
		info = {}
		info['scene'] = str(scene[b'id', b'name'][2:].decode('utf-8'))
		info['start_frame'] = str(scene[b'r', b'sfra']) # bpy.context.scene.frame_start
		info['end_frame'] = str(scene[b'r', b'efra']) # bpy.context.scene.frame_end
		info['step'] = str(scene[b'r', b'frame_step'])
		info['output_file_extension'] = str(imtype_to_file_extension(scene[b'r', b'imtype'])) # bpy.context.scene.render.file_extension)
		info['engine'] = scene[b'r', b'engine'].decode('utf8') #  bpy.context.scene.render.engine)
		
		info['resolution_percentage'] = str(scene[b'r', b'size']) # bpy.context.scene.render.resolution_percentage
		info['resolution_x'] = str(scene[b'r', b'xsch']) # bpy.context.scene.render.resolution_x
		info['resolution_y'] = str(scene[b'r', b'ysch']) # bpy.context.scene.render.resolution_y
		info['framerate'] = str(scene[b'r', b'frs_sec'] / scene[b'r', b'frs_sec_base']) #  bpy.context.scene.render.fps / bpy.context.scene.render.fps_base
		
		
		
		#if info['engine'] == 'CYCLES':
		#	info['cycles_samples'] = str(total_samples())
		#	info['cycles_pixel_samples'] = str(get_samples())
			
		f = open(output_file, 'w')
		f.write(json.dumps(info, separators=(',',':')))
		f.close()
		
		sys.exit(0)";

	protected $path;
	protected $file;
	protected $gzipped;
	
	public function __construct() {
		$this->file = NULL;
		$this->gzipped = false;
	}
	
	public function open($path) {
		$file = @fopen($path, 'r');
		if (is_resource($file) == false) {
			Logger::error('BlendReader::open failed to get data from file '.$path.' exists? '.serialize(file_exists($path)).' readable? '.serialize(is_readable($path)));
			return false;
		}
		
		$this->path = $path;
		$this->file = $file;
		
		$magic = $this->read(2);
		if (bin2hex(substr($magic, 0 , 2)) == '1f8b') { // gzip_magic
			$this->gzipped = true;
			$this->file = gzopen($path, 'r');
		}
		else { // reset the fseek
			$this->file = fopen($path, 'r');
		}
		
		return true;
	}
	
	protected function read($size) {
		if ($this->gzipped) {
			$buf = gzread($this->file, $size);
		}
		else {
			$buf = fread($this->file, $size);
		}
		
		return $buf;
	}
	
	public function getInfos() {
		global $config;
		
		if ($this->read(7) != 'BLENDER') {
			Logger::error('BlendReader::getInfos not a blend file');
			return false;
		}
		
		// BLENDER-v274RENDH.....
		$this->read(2);
		$version = $this->read(3);
		
		$working_dir = tempnam($config['tmp_dir'], "bpy").time();
		mkdir($working_dir);
		
		copy(__DIR__.'/'.'blendfile.py', $working_dir.'/'.'blendfile.py');
		
		
		$output_file = $working_dir.'/'.'out.json';
		$script_path = $working_dir.'/'.'read.py';
		
		$path = str_replace(array(' ', '(', ')',"'"), array('\ ', '\(', '\)','\\\''), $this->path);
		
		file_put_contents($script_path, BlendReaderWithPython::$script28x);
		$command = "python3 $script_path $path $output_file 2>&1";
		Logger::debug(__method__.' command: '.$command);
		
		$output_command = shell_exec($command);
		Logger::debug(__method__.' $output_command: '.$output_command);
		$contents = file_get_contents($output_file);
		$this->rrmdir($working_dir);
		if ($contents === false) {
			Logger::error('BlendReader::getInfos failed to get data from file '.$this->path.' exists? '.serialize(file_exists($this->path)).' readable? '.serialize(is_readable($path)));
			return false;
		}
		
		$ret = json_decode($contents, true);
		
		if (is_array($ret)) {
			$ret['version'] = 'blender'.$version;
//			if (array_key_exists('can_use_tile', $ret) == false) {
//				$ret['can_use_tile'] = false;
//			}
//			else {
//				$ret['can_use_tile'] = ($ret['can_use_tile'] == 'True');
//			}
		}
		
		return $ret;
	}
	
	private function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir."/".$object) && !is_link($dir."/".$object))
						$this->rrmdir($dir."/".$object);
					else
						unlink($dir."/".$object);
				}
			}
			rmdir($dir);
		}
	}
}
