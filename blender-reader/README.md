The goal is read the content of a Blender file without launching blender.

There is a working solution on BlenderReaderWithLaunchingBlenderBinary, it launchs blender on every read.

It's not a good solution because it could take a lot of ressources, specialy on the ram usage.

I'm looking for a better way to read the infos.

One solution could be to launch blender and to use the embedded python to read an external blend (without having to actually load it into blender). An another solution could to redo a blend parser from python (or php) (with a python who used to be handle by the BlenderFoundation: https://developer.blender.org/diffusion/B/browse/master/release/scripts/modules/blend_render_info.py ).

I put a phpunit test so you can check if your solution is ok.

To run it:
1. clone the repo :)
2. ```composer install```
3. ```wget -O phpunit https://phar.phpunit.de/phpunit-5.7.phar```
4. ```chmod +x phpunit```
5. ```./phpunit .```

