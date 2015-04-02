<?php
class Home extends Controller
{
    public function index($id = '', $slug = ''){
        $user = $this->model('User');
        $user->name = $id;
        // $result = $user->find(2, ['login', 'created_at']);
        // $result = $user->all(['login', 'created_at']);
        // var_dump($result);
        $result = $user->insert(['login' => 'alex']);
        // $result = $user->all(['login', 'created_at']);
        $result = $user->update(['login' => 'alex'], ['name' => 'ALEX']);
        var_dump($result);
        $this->render('index', ['name' => $user->name]);
    }
}