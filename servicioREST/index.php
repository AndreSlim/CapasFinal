<?php
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;


// Use Loader() to autoload our model
$loader = new Loader();
$loader->registerNamespaces(
	[
		"Store\\Discs" => __DIR__ . "/models/",
	]);

$loader->register();
$di = new FactoryDefault();
// Set up the database service
$di->set("db",function () {
		return new PdoMysql(
			[
				"host"    => "localhost",
				"username"=> "root",
				"password"=> "1234",
				"dbname"  => "music",
				]
		);
	}
);


$app = new Micro($di);

$app->get("/api/saludo",function()
	{
		echo "<h1>API Index</h1></br>";

	});
// Retrieves all Genres
$app->get(
	"/api/genres",
		function () use ($app) {
			    $phql		= "SELECT * FROM Store\\Discs\\Genres ORDER BY name";
				$genres = $app->modelsManager->executeQuery($phql);
				$data = [];
				foreach ($genres as $genre)
				{
					$data[] = [	"id"	=> $genre->id, "name" => $genre->name,	];
				}//CLOSE foreach
				echo json_encode($data);
	}
);

// Searches for genres with $name in their name
$app->get(
	"/api/genres/search/{name}",
	function ($name) use ($app){
		$phql		= "SELECT * FROM Store\\Discs\\Genres WHERE name=:name: ORDER BY name";
		$genre = $app->modelsManager->executeQuery($phql,["name"=>$name]);				
		echo json_encode($genre);
	}
);

// Retrieves genres based on primary key
$app->get(
	"/api/genres/{id:[0-9]+}",
	function ($id) use ($app) {		
				$phql		= "SELECT * FROM Store\\Discs\\Genres WHERE id=:id: ORDER BY name";
				$genre = $app->modelsManager->executeQuery($phql,["id"=>$id]);				
				echo json_encode($genre);
	}
);

// Adds a new genre
$app->post(
		"/api/genres/add",
		function () use($app){
			$genre = $app->request->getJsonRawBody();
			$phql = "INSERT INTO Store\\Discs\\Genres (name) VALUES (:name:)";
			$status = $app->modelsManager->executeQuery($phql,[	"name" => $genre->name]);
			// Create a response
			$response = new Response();
			// Check if the insertion was successful
			if ($status->success() === true) 
			{	// Change the HTTP status
				$response->setStatusCode(201, "Created");
				$genre->id = $status->getModel()->id;
				$response->setJsonContent(["status" => "OK","data"=>$genre,]);
			} else 
			{	// Change the HTTP status
				$response->setStatusCode(409, "Conflict");
				// Send errors to the client
				$errors = [];
				foreach ($status->getMessages() as $message) 
				{
					$errors[] = $message->getMessage();
				}
				$response->setJsonContent(["status" => "ERROR",	"messages" => $errors,]	);
			}
			return $response;
	}
);

// Updates genres based on primary key
$app->put(
	"/api/genres/{id:[0-9]+}",
	function () {
	}
);

// Deletes genres based on primary key
$app->delete(	 
	"/api/genres/delete/{id:[0-9]+}",
	function ($id) use($app) {
		$phql		= "DELETE FROM Store\\Discs\\Genres WHERE id = :id:";
		$status = $app->modelsManager->executeQuery($phql,["id"=>$id]);				
		
		$response = new Response();
			if ($status->success() === true) 
			{
				$response->setJsonContent([	"status" => "OK"]);
			} else 
			{							
				// Change the HTTP status
				$response->setStatusCode(409, "Conflict");
				$errors = [];
				foreach ($status->getMessages() as $message) 
				{
					$errors[] = $message->getMessage();
				}
				$response->setJsonContent([	"status"=> "ERROR",	"messages" => $errors,]);
			}
		return $response;
	}
);

$app->handle();
