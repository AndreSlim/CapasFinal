<?php

class GenresController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function datatableAction()
    {

          $this->view->disable();

          $url = "http://localhost/discoteca/servicioREST/api/genres";
          $client=curl_init($url);
          curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($client);
          $result = json_decode($response);
          
          curl_close($client);

          $this->response->setJsonContent(["data"=>$result]);
          $this->response->setStatusCode(200, "OK");
          $this->response->send();
    
    } 

    public function saveAction(){


      //if($this->request->isAjax())
      //{
      $this->view->disable();
      /*
      
        $name = $this->request->getPost("nameG");

        $option = $this->request->getPost("option");
        if($option === "edit")
        {
          $genre = Genres::findFirst($this->request->getPost('idEdit'));
        }
        else{
          $genre = new Genres();
        }

        $genre->name = $name ;
        $genre->save();
       */

          $name = $this->request->getPost('nameG');
          $data = json_encode(array("name"=>$name));

          $url = "http://localhost/discoteca/servicioREST/api/genres/add";
          $client = curl_init($url);
          curl_setopt($client, CURLOPT_POST, 1);
          curl_setopt($client, CURLOPT_POSTFIELDS, $data);
          $response = curl_exec($client);
          curl_close($client);
          $this->response->redirect("genre");
          //
          /*
          
        $this->response->setJsonContent(["data"=>$name,"options"=>["option"=>$option,"id"=>$this->request->getPost('idEdit')]]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
           */

        //}
    }

    public function deleteAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {

          $id = $this->request->getPost("id");
          $data = json_encode(array("id"=>$id));

    $url = "http://localhost/discoteca/servicioREST/api/genres/delete/" . $id;
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

      }
    }

    public function getByIdAction()
    {
      $this->view->disable();
      if($this->request->isAjax())
      {
        $id = $this->request->getPost('id');
        $genre = Genres::findFirst($id);
        $this->response->setJsonContent(["genre"=>$genre]);
        $this->response->setStatusCode(200, "OK");
        $this->response->send();
      }
    }

    public function validateSaveResult($model, $bool_result)
    {
      if ($bool_result)
      {
        $this->response->setJsonContent(["data"=>$model]);
        $this->response->setStatusCode(200,"OK");
        $this->response->send();
      }
      else {
        $this->response->setJsonContent(["errors"=>print_r($model->getMessages())]);
        $this->response->setStatusCode(200,"OK");
        $this->response->send();
      }
    }


}
