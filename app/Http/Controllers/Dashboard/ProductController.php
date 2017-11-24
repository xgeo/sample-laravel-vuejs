<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\Requests\CreateProduteRequest;
use App\ProductCategory;

class ProductController extends DashboardController 
{
    public function index() 
    {
        return $this->getView('product.index');
    }
    
    public function list() {}

    public function show(int $id) {}

    public function create() 
    {
        return $this->getView('product.create');
    }

    public function upload(Request $request) 
    {
        if ($request->hasFile('image')) {
            $image  = $request->file('image');
            $uid    = uniqid();
            
            $fileName = md5($image . $image->getFilename());
            
            if (!is_dir(public_path('products/'))) mkdir(public_path('products'), 0777);
            if (!is_dir(public_path('products/images/'))) mkdir(public_path('products/images/'), 0777);

            $image->storeAs('images', $fileName, 'products');

            return ['file' => 'products/images/' . $fileName];
        }
    }

    public function edit(int $id) {}

    public function listCategories() 
    {
        return response()->json(ProductCategory::get(['id', 'display']));
    }
        
    protected function store(CreateProduteRequest $request, ProductCategory $productCategory) 
    {
        if ($data = $request->all()) {
            $productCategory->find($data['product_categories_id'])
                            ->products()
                            ->create($data);

            return response()->json(['message' => 'Product save!']);
        }

        return response()->json($request);
    }
    
    protected function update() {}
    protected function destroy(int $id) {}
}