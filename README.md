Router
======

Simple PHP RESTful router

## Usage

	$router = new Router();
	
	$router->get('/path', function() {
		echo "Hello GET!";
	});
	
	$router->post('/path', function() {
		echo "Hello POST!";
	});
	
	$router->put('/path', function() {
		echo "Hello PUT!";
	});
	
	$router->delete('/path', function() {
		echo "Hello DELETE!";
	});
	
	$router->notFound('/path', function() {
		echo "Hello 404!";
	});
	
	// Start router
	$router->match();
	
## Params

	$router->get('/user/:name', function($name) {
		echo "Hello $name";
	});
  
