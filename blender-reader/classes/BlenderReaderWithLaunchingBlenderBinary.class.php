<?php
/**
 * Copyright (C) 2013 Laurent CLOUET
 * Author Laurent CLOUET <laurent.clouet@nopnop.net>
 *
 **/

require_once(__DIR__.'/BlenderReader.class.php');
require_once(__DIR__.'/Logger.class.php');

class BlendReaderWithLaunchingBlenderBinary extends BlenderReader {
	const script28x = "
import bpy
import json
import os
import sys

def exists(lib):
	if lib.packed_file is None: # it's an external file
		path = lib.filepath
		if path.startswith('//'):
			path = path[2:]
		if path != '' and not os.path.exists(os.path.join(os.path.dirname(os.environ['BLEND_FILE']).encode('utf-8'),  bpy.path.abspath(path).encode('utf-8'))):
			return False
	return True

def list_missing_files():
	ret = []
	for lib in bpy.data.libraries:
		try:
			if exists(lib) == False:
				ret.append(lib.filepath)
		except:
			pass
	for lib in bpy.data.images:
		try:
			if exists(lib) == False:
				ret.append(lib.filepath)
		except:
			pass
	return ret

def has_active_file_output_node():
	try:
		for node in bpy.context.scene.node_tree.nodes:
			if not node.mute and type(node) == bpy.types.CompositorNodeOutputFile or node.type == 'OUTPUT_FILE':
				return True
	except:
		pass
	return False

def can_use_tile():
	use_denoising = False
	for layer in bpy.context.scene.view_layers:
		if layer.cycles.use_denoising == True:
			use_denoising = True
			break
	
	if use_denoising == True:
		return False
	
	if not bpy.context.scene.render.use_compositing:
		return True
	
	# no nodes
	if bpy.context.scene.node_tree is None or bpy.context.scene.node_tree.nodes is None or len(bpy.context.scene.node_tree.nodes.items()) == 0:
		return True

	# todo check if there is active compositing node
	
	return False

def get_samples():
	if bpy.context.scene.cycles.progressive == 'PATH':
		return bpy.context.scene.cycles.samples
	elif bpy.context.scene.cycles.progressive == 'BRANCHED_PATH':
		return bpy.context.scene.cycles.aa_samples
	else:
		# other integrator ??
		return bpy.context.scene.cycles.samples

def total_samples():
	samples = bpy.context.scene.render.resolution_x * bpy.context.scene.render.resolution_y
	samples *= float(bpy.context.scene.render.resolution_percentage) / 100.0
	samples *= float(bpy.context.scene.render.resolution_percentage) / 100.0
	samples *= get_samples()
	if bpy.context.scene.cycles.use_square_samples:
		samples *= get_samples()
	return samples

def list_scripted_driver():
	for o in bpy.data.objects:
		if o.animation_data is not None:
			for driver in o.animation_data.drivers:
				if driver.driver is not None and driver.driver.type == 'SCRIPTED':
					return True
	return False

info = {}
info['scene'] = str(bpy.context.scene.name)
info['start_frame'] = str(bpy.context.scene.frame_start)
info['end_frame'] = str(bpy.context.scene.frame_end)
info['output_file_extension'] = str(bpy.context.scene.render.file_extension)
info['engine'] = str(bpy.context.scene.render.engine)
info['resolution_percentage'] = str(bpy.context.scene.render.resolution_percentage)
info['resolution_x'] = str(bpy.context.scene.render.resolution_x)
info['resolution_y'] = str(bpy.context.scene.render.resolution_y)
info['framerate'] = str(bpy.context.scene.render.fps / bpy.context.scene.render.fps_base)
info['can_use_tile'] = str(can_use_tile())
info['missing_files'] = list_missing_files()
info['have_camera'] = bpy.context.scene.camera != None
info['scripted_driver'] = str(list_scripted_driver())
info['has_active_file_output_node'] = has_active_file_output_node()

if info['engine'] == 'CYCLES':
	info['cycles_samples'] = str(total_samples())
	info['cycles_pixel_samples'] = str(get_samples())
	
f = open(os.environ['OUTPUT_FILE'], 'w')
f.write(json.dumps(info, separators=(',',':')))
f.close()

sys.exit(0)";

	protected $path;
	protected $file;
	protected $gzipped;
	
	protected $blenderPath;
	
	public function __construct($blenderPath) {
		$this->blenderPath = $blenderPath;
		$this->file = NULL;
		$this->gzipped = false;
	}
	
	public function open($path) {
		$file = @fopen($path, 'r');
		if (is_resource($file) == false) {
			//Logger::error('BlendReader::open failed to get data from file '.$path.' exists? '.serialize(file_exists($path)).' readable? '.serialize(is_readable($path)));
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
		global  $config;
		
		if ($this->read(7) != 'BLENDER') {
			//Logger::error('BlendReader::getInfos not a blend file');
			return false;
		}
		
		// BLENDER-v274RENDH.....
		$this->read(2);
		$version = $this->read(3);
		
		$output_file = tempnam($config['tmp_dir'], "out");
		$script_path = tempnam($config['tmp_dir'], "rea");
		
		$path = str_replace(array(' ', '(', ')',"'"), array('\ ', '\(', '\)','\\\''), $this->path);
		
		file_put_contents($script_path, BlendReaderWithLaunchingBlenderBinary::script28x);
		$binary = $this->blenderPath;
		$command = "OUTPUT_FILE=$output_file BLEND_FILE=$path $binary --factory-startup --disable-autoexec -b $path -P $script_path 2>&1";
		
		$output_command = shell_exec($command);
		$contents = file_get_contents($output_file);
		@unlink($output_file);
		@unlink($script_path);
		if ($contents === false) {
			Logger::error('BlendReader::getInfos failed to get data from file '.$this->path.' exists? '.serialize(file_exists($this->path)).' readable? '.serialize(is_readable($path)));
			return false;
		}
		
		$ret = json_decode($contents, true);
		
		if (is_array($ret)) {
			$ret['version'] = 'blender'.$version;
			if (array_key_exists('can_use_tile', $ret) == false) {
				$ret['can_use_tile'] = false;
			}
			else {
				$ret['can_use_tile'] = ($ret['can_use_tile'] == 'True');
			}
		}
		
		return $ret;
	}
}
