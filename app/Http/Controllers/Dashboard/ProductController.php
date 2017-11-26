<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\Requests\CreateProduteRequest;
use App\ProductCategory;
use App\Product;

class ProductController extends DashboardController 
{
    public function index() 
    {
        return $this->getView('product.index');
    }
    
    public function list(Product $product) 
    {
        return $product->with('productCategories')->get();
    }

    public function find(int $id) 
    {
        return Product::find($id);
    }

    public function create() 
    {
        return $this->getView('product.create');
    }

    public function upload(Request $request) 
    {
        $message = 'Not found!';

        if ($request->hasFile('image')) {
            $image  = $request->file('image');
            $uid    = uniqid();
            
            $fileName = md5($image . $image->getFilename());
            
            if (!is_dir(public_path('products/'))) mkdir(public_path('products'), 0777);
            if (!is_dir(public_path('products/images/'))) mkdir(public_path('products/images/'), 0777);

            $image->storeAs('images', $fileName, 'products');

            $message = ['file' => 'products/images/' . $fileName];
        }

        return response()->json($message);
    }

    public function listCategories() 
    {
        return response()->json(ProductCategory::get(['id', 'display']));
    }
        
    protected function store(CreateProduteRequest $request, ProductCategory $productCategory) 
    {
        $message = &$request;

        if ($data = $request->all()) {
            $productCategory->find($data['product_categories_id'])
                            ->products()
                            ->create($data);

            $message = 'Product save!';
        }

        return response()->json(['message' => $message]);
    }
    
    protected function update($id, Request $request) 
    {
        try {
            $product = Product::findOrFail($id)->update($request->all());
            $message = 'Product updated!';
        } catch(Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['message' => $message]);
    }

    protected function destroy(int $id) 
    {
        try {
            $product = Product::findOrFail($id)->delete();
            $message = 'Product removed!';
        } catch(Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['message' => $message]);
    }
}