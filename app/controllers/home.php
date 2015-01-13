<?php
class Home extends Controller
{
    public function index($id = '', $slug = ''){
        $user = $this->model('User');
        $user->name = $id;
        // $result = $user->find(2, ['login', 'created_at']);
        $result = $user->all(['login', 'created_at']);
        var_dump($result);
        // var_dump($result);
        $this->render('index', ['name' => $user->name]);
    }
}