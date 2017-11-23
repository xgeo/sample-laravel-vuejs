<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\DashboardController;

class ProductController extends DashboardController 
{
    public function index() {
        return $this->getView('product.index');
    }

    public function show(int $id) {}
    public function create() {}
    public function edit(int $id) {}
    protected function store() {}
    protected function update() {}
    protected function destroy(int $id) {}
}