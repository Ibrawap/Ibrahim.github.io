prd : less-min

less :
	lessc public/css/less/bootstrap.less public/css/bootstrap.css

less-min :
	lessc -x public/css/less/bootstrap.less public/css/bootstrap.min.css
