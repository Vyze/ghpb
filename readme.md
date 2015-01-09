## Github project browser

### Version: 0.1

### License

The github project browser is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)


## Installation:

### 1. Modify your composer.json :
	
-	add to "require" block:

	    "vyze/ghpb": "dev-master"

- 	add to "post-install-cmd" and "post-update-cmd" blocks:
	
        "php artisan config:publish graham-campbell/github",
        "php artisan config:publish vyze/ghpb",
        "php artisan asset:publish --path='vendor/vyze/ghpb/public/' ghpb",
        "php artisan asset:publish --path='vendor/twbs/bootstrap/dist/' bootstrap",

-    modify you app/config/app.php :
	
        'providers' => array(
        // ...
         'GrahamCampbell\GitHub\GitHubServiceProvider', //GitHub API
         'Vyze\Ghpb\GhpbServiceProvider', //GitHub project browser
        )

### 2. config

-   app/config/packages/graham-campbell/config.php:
	add your github api token here:
       
        'main' => array(
            'token'   => 'your-token',
        ), 

-   app/config/packages/vyze/config.php:
	you can set default github vendor/project and the root route for package

### 3. migrations:
	After all abow you need to create a database structure
	
	    php artisan migrate --package='vyze/ghpb'
