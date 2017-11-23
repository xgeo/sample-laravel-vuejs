<?php
namespace App\Http\Controllers\Dashboard;

class IndexController extends DashboardController 
{
    public function index() 
    {
        return $this->getView('index');
    }
}