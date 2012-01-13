prd : less-min classmap

less :
	lessc public/css/less/bootstrap.less public/css/bootstrap.css

less-min :
	lessc -x public/css/less/bootstrap.less public/css/bootstrap.min.css

classmap: classmap-modules

classmap-modules:
	for module in module/*; do cd $$module && php ../../bin/Zend/classmap_generator.php; cd ../..; done
