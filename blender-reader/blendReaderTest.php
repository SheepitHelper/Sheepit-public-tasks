<?php

require_once(__DIR__.'/vendor/autoload.php');

class blendReaderTest extends PHPUnit_Framework_TestCase {
	private $reader;
	
	protected function setUp() {
		// replace the reader by your
		$this->reader = new BlendReaderWithLaunchingBlenderBinary('/usr/bin/blender');
	}

	public function testFileDoesNotExist() {
		$blender_file = __DIR__.'/'.time().'.blend';

		while (file_exists($blender_file)) {
			$blender_file .= '.blend';
		}

		$ret = $this->reader->open($blender_file);
		$this->assertFalse($ret);
	}

	public function testNotABlenderFile() {
		$blender_file = __DIR__.'/data/blend/000000.png';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertFalse($infos);
	}

	public function test274cycles1920x108040pcstart10end100() {
		$blender_file = __DIR__.'/data/blend/274-cycles-1920x1080-40pc-start10-end100.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(10, $infos['start_frame']);
		$this->assertEquals(100, $infos['end_frame']);
		$this->assertEquals('Scene', $infos['scene']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals((int)(1920*1080*0.40*0.40*10), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function test274cycles200x100100pcstart1end250compressed() {
		$blender_file = __DIR__.'/data/blend/274-cycles-200x100-100pcstart1-end250-compressed.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(1, $infos['start_frame']);
		$this->assertEquals(250, $infos['end_frame']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals(200, $infos['resolution_x']);
		$this->assertEquals(100, $infos['resolution_y']);
		$this->assertEquals((int)(200*100*1*1*10), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function test274cycles530x260100pcstart1end100() {
		$blender_file = __DIR__.'/data/blend/274-cycles-530x260-100pc-start1-end100.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(1, $infos['start_frame']);
		$this->assertEquals(100, $infos['end_frame']);
		$this->assertEquals('Scene', $infos['scene']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals((int)(530*260*1*1*150), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function test274cycles530x26050pcstart1end100SquareSamples() {
		$blender_file = __DIR__.'/data/blend/274-cycles-530x260-50pc-start1-end100-square-samples.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(1, $infos['start_frame']);
		$this->assertEquals(100, $infos['end_frame']);
		$this->assertEquals('Scene', $infos['scene']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals((int)(530*260*0.50*0.50*1500*1500), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function testPathWithQuote() {
		$blender_file = __DIR__."/data/blend/path-with-'quote'-274-cycles-530x260-50pc-start1-end100-square-samples.blend";

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(1, $infos['start_frame']);
		$this->assertEquals(100, $infos['end_frame']);
		$this->assertEquals('Scene', $infos['scene']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals((int)(530*260*0.50*0.50*1500*1500), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function testPathWithSpace() {
		$blender_file = __DIR__."/data/blend/path-with- -274-cycles-530x260-50pc-start1-end100-square-samples.blend";

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('start_frame', $infos));
		$this->assertTrue(array_key_exists('end_frame', $infos));
		$this->assertTrue(array_key_exists('scene', $infos));
		$this->assertTrue(array_key_exists('output_file_extension', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('resolution_percentage', $infos));
		$this->assertTrue(array_key_exists('resolution_x', $infos));
		$this->assertTrue(array_key_exists('resolution_y', $infos));
		$this->assertTrue(array_key_exists('missing_files', $infos));
		$this->assertTrue(array_key_exists('scripted_driver', $infos));
		$this->assertTrue(array_key_exists('cycles_samples', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));

		$this->assertEquals('blender274', $infos['version']);
		$this->assertEquals(1, $infos['start_frame']);
		$this->assertEquals(100, $infos['end_frame']);
		$this->assertEquals('Scene', $infos['scene']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals((int)(530*260*0.50*0.50*1500*1500), $infos['cycles_samples']);
		$this->assertTrue($infos['have_camera']);
	}

	public function test278cycles24fps() {
		$blender_file = __DIR__.'/data/blend/278-cycles-24fps.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('framerate', $infos));
		$this->assertTrue(array_key_exists('have_camera', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));

		$this->assertEquals('blender278', $infos['version']);
		$this->assertTrue($infos['have_camera']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals(24, $infos['framerate']);
	}

	public function testCyclesPathTracingIntegrator() {
		$blender_file = __DIR__.'/data/blend/path-tracing-integrator.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));

		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('cycles_pixel_samples', $infos));

		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertEquals(1000, $infos['cycles_pixel_samples']);
	}

	public function testBlender27XCanUseTileCyclesWithCompositing() {
		$blender_file = __DIR__.'/data/blend/278-cycles-compositing.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertFalse($infos['can_use_tile']);
	}

	public function testBlender27XCanUseTileCyclesWithoutCompositing() {
		$blender_file = __DIR__.'/data/blend/278-cycles-no-compositing.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender27XCanUseTileCycleCompositingDisabled() {
		$blender_file = __DIR__.'/data/blend/278-cycles-compositing-disabled.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender27XCanUseTileCycleCompositingEnableUseNodeDisabled() {
		$blender_file = __DIR__.'/data/blend/278-cycles-compositing-disabled-use-node-disabled.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}
	
	public function testBlender27XCanUseTileCyclesWithoutDenoising() {
		$blender_file = __DIR__.'/data/blend/279-cycles-no-denoising.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('blender279', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender27XCanUseTileCycleDenoising() {
		$blender_file = __DIR__.'/data/blend/279-cycles-denoising.blend';

		$ret = $this->reader->open($blender_file);
		$this->assertTrue($ret);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('blender279', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertFalse($infos['can_use_tile']);
	}

	public function test280Cycles() {
		$blender_file = __DIR__.'/data/blend/280-cycles.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
	}

	public function test280Eevee() {
		$blender_file = __DIR__.'/data/blend/280-eevee.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('BLENDER_EEVEE', $infos['engine']);
	}

	public function test280Workbench() {
		$blender_file = __DIR__.'/data/blend/280-workbench.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('BLENDER_WORKBENCH', $infos['engine']);
	}

	public function testBlender280CanUseTileCyclesWithCompositing() {
		$blender_file = __DIR__.'/data/blend/280-cycles-compositing.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));
		$this->assertTrue(array_key_exists('version', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertFalse($infos['can_use_tile']);
	}

	public function testBlender280CanUseTileCyclesWithoutCompositing() {
		$blender_file = __DIR__.'/data/blend/280-cycles-no-compositing.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));
		$this->assertTrue(array_key_exists('version', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender280CanUseTileCycleCompositingDisabled() {
		$blender_file = __DIR__.'/data/blend/280-cycles-compositing-disabled.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));
		$this->assertTrue(array_key_exists('version', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender280CanUseTileCycleCompositingEnableUseNodeDisabled() {
		$blender_file = __DIR__.'/data/blend/280-cycles-compositing-disabled-use-node-disabled.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));
		$this->assertTrue(array_key_exists('version', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender280CanUseTileCyclesWithoutDenoising() {
		$blender_file = __DIR__.'/data/blend/280-cycles-no-denoising.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertTrue($infos['can_use_tile']);
	}

	public function testBlender280CanUseTileCycleDenoising() {
		$blender_file = __DIR__.'/data/blend/280-cycles-denoising.blend';

		$this->assertTrue($this->reader->open($blender_file), 'Failed to open blend file '.$blender_file);

		$infos = $this->reader->getInfos();

		$this->assertTrue(is_array($infos));
		$this->assertTrue(array_key_exists('version', $infos));
		$this->assertTrue(array_key_exists('engine', $infos));
		$this->assertTrue(array_key_exists('can_use_tile', $infos));

		$this->assertEquals('blender280', $infos['version']);
		$this->assertEquals('CYCLES', $infos['engine']);
		$this->assertFalse($infos['can_use_tile']);
	}
}
