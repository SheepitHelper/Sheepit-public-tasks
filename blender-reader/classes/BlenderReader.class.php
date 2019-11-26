<?php
/**
 * Copyright (C) 2013 Laurent CLOUET
 * Author Laurent CLOUET <laurent.clouet@nopnop.net>
 **/

abstract class BlenderReader {
	
	/**
	 * Get blend's infos,
	 * return should be an array at least these info:
	 * info = {}
	 * info['scene']
	 * info['start_frame']
	 * info['end_frame']
	 * info['output_file_extension']
	 * info['engine']
	 * info['resolution_percentage']
	 * info['resolution_x']
	 * info['resolution_y']
	 * info['framerate']
	 * info['missing_files']
	 * info['have_camera']
	 * info['scripted_driver']
	 *
	 */
	public abstract function getInfos();
}
