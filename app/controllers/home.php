<?php
class Home extends Controller
{
    public function index($id = '', $slug = ''){
        $user = $this->model('User');
        $user->name = $id;
        // $result = $user->find(2, ['login', 'created_at']);
        // $result = $user->all(['login', 'created_at']);
        // var_dump($result);
        // $result = $user->insert(['login' => 'Mr.Douglass']);
        // // $result = $user->all(['login', 'created_at']);
        // $result2 = $user->update(['login' => 'Mr.Douglass'], ['name' => 'Michael']);
        // var_dump($result);
        // var_dump($result2);
        $this->render('index', ['name' => $user->name]);
    }

    public function add(){
        $this->render('add');
    }
}