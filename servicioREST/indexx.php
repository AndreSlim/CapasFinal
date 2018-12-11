<?php
$app = new \Phalcon\Mvc\Micro();

$app->get('/', function() 
		{
			echo "<h1>Bienvenido!</h1><br>";
		}
	);

function saludo($name) 
{
    echo "<h1>¡Hola ".$name."!</h1><br>";
}
//así llamamos a nuestra función y accedemos http://localhost/servicioREST/saludo/nombre
$app->get('/saludo/{name}', "saludo");

$app->handle();
?>
