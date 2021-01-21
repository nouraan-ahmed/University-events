<?php

namespace MVC\controllers;

use MVC\core\controller;
use MVC\core\Session;
use MVC\models\category;
use MVC\core\helpers;


class admincategorycontroller extends controller
{
    public $user_data;
    public $category;
    public function __construct()
    {
        Session::Start();
        $this->user_data = Session::Get('user');
        if (empty($this->user_data)) {
            echo 'class not access';
            die;
        }
        $this->category = new category();
    }
    public function index()
    {

        $data = $this->category->GetAllCategory();
        $this->view('back/category', ['title' => 'admin', 'data' => $data]);
    }
    public function DeleteCategory($id)
    {

        $data = $this->category->DeleteCategory($id);

        if ($data) {
            helpers::redirect('admincategory/index');
        }
    }
    public function add()
    {
        $this->view('back/addcategory', ['title' => 'admin']);
    }
    public function postadd()
    {
        $img = $_FILES['img'];
        move_uploaded_file($img['tmp_name'], 'img/' . $img['name']);
        $data = ['name' => $_POST['name'], 'icon' => $_POST['icon'], 'user_id' => $this->user_data->id, 'img' => $img['name']];
        $data = $this->category->AddCategory($data);
        if ($data) {
            helpers::redirect('admincategory/index');
        }
    }
    public function update($id)
    {
        $data = $this->category->GetCategory($id);
        // var_dump($data);
        $this->view('back/updatecategory', ['title' => 'admin', 'data' => $data]);
    }
    public function postupdate()
    {
        if (!empty($_FILES['img']['name'])) {
            $img = $_FILES['img'];
            move_uploaded_file($img['tmp_name'], 'img/' . $img['name']);
            $data = ['name' => $_POST['name'], 'icon' => $_POST['icon'], 'user_id' => $this->user_data->id, 'img' => $img['name']];
        } else {
            $data = ['name' => $_POST['name'], 'icon' => $_POST['icon'], 'user_id' => $this->user_data->id];
        }

        $id = ['id' => $_POST['id']];
        $data = $this->category->UpdateCategory($data, $id);
        if ($data) {
            helpers::redirect('admincategory/index');
        }
    }
}
