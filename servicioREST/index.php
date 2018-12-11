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


// # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
// 									A L B U M S 
// # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


// Retrieves all Genres
$app->get(
	"/api/albums",
		function () use ($app) {
			    //$phql		= "SELECT * FROM Store\\Discs\\Albums ORDER BY name";
			    //$phql = "SELECT 'a.id', 'a.name', 'a.author', 'g.name' as genre FROM Store\\Discs\\Albums a INNER JOIN Store\\Discs\\Genres g ON 'a.genre_id' = 'g.id' ORDER BY name";
				//$albums = $app->modelsManager->executeQuery($phql);
				      $albums = $this->modelsManager->createBuilder()
              ->from(['a'=>'Store\\Discs\\Albums'])
              ->join('Store\\Discs\\Genres','a.genre_id = g.id','g')
              ->columns(['a.id','a.name','a.author','g.name as genre_id'])
              ->getQuery()
              ->execute();

				$data = [];
				foreach ($albums as $album)
				{
					$data[] = [	"id"	=> $album->id, 
								"name" => $album->name,	
								"author" => $album->author,
								"genre" => $album->genre_id,
								];
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

// Adds a new album
$app->post(
		"/api/albums/add",
		function () use($app){
			$album = $app->request->getJsonRawBody();
			$phql = "INSERT INTO Store\\Discs\\Albums (name,author,genre_id) VALUES (:name:,:author:,:genre_id:)";
			$status = $app->modelsManager->executeQuery($phql,[	"name" => $album->name,
																"author" => $album->author,
																"genre_id" => $album->genre
																]);
			// Create a response
			$response = new Response();
			// Check if the insertion was successful
			if ($status->success() === true) 
			{	// Change the HTTP status
				$response->setStatusCode(201, "Created");
				$album->id = $status->getModel()->id;
				$response->setJsonContent(["status" => "OK","data"=>$album,]);
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


// handle chido
$app->handle();
