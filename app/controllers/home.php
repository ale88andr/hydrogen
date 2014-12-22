<?php
class Home extends Controller
{
    public function index($id = '', $slug = ''){
        $user = $this->model('User');
        $user->name = $id;
        $this->render('index', ['name' => $user->name]);
    }
}