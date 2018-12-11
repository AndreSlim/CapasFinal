<?php

class AlbumsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
         $this->view->generos =  Genres::find();
    }

    public function datatableAction()
    {
      $this->view->disable();
      $albums = $this->modelsManager->createBuilder()
              ->from(['a'=>'Albums'])
              ->join('Genres','a.genre_id = g.id','g')
              ->columns(['a.name as alname','a.author','g.name as genname','a.id as album_id'])
              ->getQuery()
              ->execute();

    /*  $albums = Albums::find();*/
      $this->response->setJsonContent(["data"=>$albums]);
      $this->response->setStatusCode(200, "OK");
      $this->response->send();
    }

    public function saveAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {
        $option = $this->request->getPost("option");
        if($option === "edit")
        {
          $album = Albums::findFirst($this->request->getPost('idEdit'));
        }
        else
        {
          $album   = new Albums();
        }
        $name = $this->request->getPost("nameA");
        $author = $this->request->getPost("autor");
        $genre_id = $this->request->getPost("genre_id");

        $album->name = $name ;
        $album->author=$author;
        $album->genre_id = $genre_id;
        $album->save();

        $this->response->setJsonContent(["data"=>$name]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      }
    }

    public function deleteAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {
        $id = $this->request->getPost("id");

        $album = Albums::findFirst($id);
        $album->delete();

        $this->response->setJsonContent(["data"=>$id]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      }
    }

    public function getByIdAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {
        $id = $this->request->getPost('id');
        $album = Albums::findFirst($id);
        $this->response->setJsonContent(["album"=>$album]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      }
    }


}
