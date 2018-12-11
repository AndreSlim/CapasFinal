<?php

class AlbumsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
          // Obteniendo los generos para mostrar en la lista
          $url = "http://localhost/discoteca/servicioREST/api/genres";
          $client=curl_init($url);
          curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($client);
          $result = json_decode($response);

         $this->view->generos =  $result;
    }

    public function datatableAction()
    {
                $this->view->disable();

          $url = "http://localhost/discoteca/servicioREST/api/albums";
          $client=curl_init($url);
          curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($client);
          $result = json_decode($response);
          
          curl_close($client);

          $this->response->setJsonContent(["data"=>$result]);
          $this->response->setStatusCode(200, "OK");
          $this->response->send();
      /*
      $this->view->disable();
      $albums = $this->modelsManager->createBuilder()
              ->from(['a'=>'Albums'])
              ->join('Genres','a.genre_id = g.id','g')
              ->columns(['a.name as alname','a.author','g.name as genname','a.id as album_id'])
              ->getQuery()
              ->execute();

    //  $albums = Albums::find();
      $this->response->setJsonContent(["data"=>$albums]);
      $this->response->setStatusCode(200, "OK");
      $this->response->send();
       */
    }

    public function saveAction()
    {
      $this->view->disable();
      
      /*
      if($this->request->isAjax()){
        $option = $this->request->getPost("option");
        if($option === "edit")
        {
          $album = Albums::findFirst($this->request->getPost('idEdit'));
        }
        else
        {
          $album   = new Albums();
        }
       */
      
        $name = $this->request->getPost("nameA");
        $author = $this->request->getPost("autor");
        $genre_id = $this->request->getPost("genre_id");

        $data = json_encode(array("name" => $name, "author" => $author, "genre" => $genre_id));

          $url = "http://localhost/discoteca/servicioREST/api/albums/add";
          $client = curl_init($url);
          curl_setopt($client, CURLOPT_POST, 1);
          curl_setopt($client, CURLOPT_POSTFIELDS, $data);
          $response = curl_exec($client);
          curl_close($client);
          $this->response->redirect("albums");

      /*

        $album->name = $name ;
        $album->author=$author;
        $album->genre_id = $genre_id;
        $album->save();

        $this->response->setJsonContent(["data"=>$name]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      }
       */
    }

    public function deleteAction()
    {
            $this->view->disable();
      if($this->request->isAjax())
      {

          $id = $this->request->getPost("id");
          $data = json_encode(array("id"=>$id));

    $url = "http://localhost/discoteca/servicioREST/api/albums/delete/" . $id;
    $client = curl_init();
    curl_setopt($client, CURLOPT_URL, $url);
    curl_setopt($client, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($client, CURLOPT_POSTFIELDS, $data);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($client);
    $result = json_decode($result);
    curl_close($client);

        $this->response->setJsonContent(["data"=>$id]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      /*
      
      $this->view->disable();
      if($this->request->isAjax())
      {
        $id = $this->request->getPost("id");

        $album = Albums::findFirst($id);
        $album->delete();

        $this->response->setJsonContent(["data"=>$id]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
       */
      }
    }

    public function getByIdAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {
        
        $id = $this->request->getPost('id');
        
          $url = "http://localhost/discoteca/servicioREST/api/albums/" . $id;
          $client=curl_init($url);
          curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($client);
          $result = json_decode($response);

          $this->response->setJsonContent(["album"=>$result]);
          $this->response->setStatusCode(200, "OK");
          $this->response->send();
         //$this->view->generos =  $result;
        /*
        $album = Albums::findFirst($id);
        $this->response->setJsonContent(["album"=>$album]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
         */
      }
    }


}
