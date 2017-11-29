<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\Requests\UploadCsvRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\Requests\CreateProduteRequest;
use App\ProductCategory;
use App\Product;
use Illuminate\Http\UploadedFile;

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

    public function uploadImage(Request $request) 
    {
        $this->makePath('products/images', 'public_path');
        $message = $this->upload($request, 'image', ['folder' => 'images', 'disk' => 'products']);

        return response()->json($message);
    }

    public function import()
    {
        return $this->getView('product.import');
    }

    public function uploadCSV(UploadCsvRequest $request)
    {
        $message = &$request;

        if ($request->all()) {
            $this->makePath('app/csv_files/files', 'storage_path');
            $this->makePath('app/csv_files/imported', 'storage_path');
            $message = $this->upload($request, 'file', ['folder' => 'files', 'disk' => 'csv_files']);
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
            Product::findOrFail($id)->update($request->all());
            $message = 'Product updated!';
        } catch(\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['message' => $message]);
    }

    protected function destroy(int $id) 
    {
        try {
            Product::findOrFail($id)->delete();
            $message = 'Product removed!';
        } catch(\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json(['message' => $message]);
    }

    final private function upload($request, string $field, array $paths) 
    {
        if ($request->hasFile($field)) {
            /** @var UploadedFile $image */
            $image  = $request->file($field);

            $fileName = md5($image . uniqid() . $image->getFilename()) . '.' . $image->getClientOriginalExtension();

            $image->storeAs($paths['folder'], $fileName, $paths['disk']);

            $message = ['file' => $paths['disk'] . '/' . $paths['folder'] . '/' . $fileName, 'message' => 'Upload successfully!'];

            return $message;
        }
    }

    final private function makePath($path, $basePath) 
    {
        if (strpos($path, '/') !== false) {
            $matches = explode('/', $path);
            $fullPath = '';
            foreach($matches as $match) {
                $fullPath .= $match . '/';
                if (!is_dir($basePath($fullPath))) mkdir($basePath($fullPath), 0777);
            }
        } else {
            if (!is_dir($basePath($path))) mkdir($basePath($path), 0777);
        }
    }
}