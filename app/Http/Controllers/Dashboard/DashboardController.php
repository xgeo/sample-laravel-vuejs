<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller 
{

    private $prefix = 'dashboard';

    public function __construct() 
    {
        $this->middleware('auth');
    }

    protected function getView(string $view) 
    {
        return view($this->prefix . '.' . $view);
    }
}