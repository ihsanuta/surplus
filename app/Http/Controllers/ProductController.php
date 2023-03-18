<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Image;

class ProductController extends Controller
{
    public function __construct(){}

    public function create(ProductRequest $request){
        $exists = Category::where('name','=',$request->name)->first();
        if($exists){
            return response()->json([
                'status' => 'error',
                'message' => 'Create product unsuccessfull ',
                'error' => "product exists",
            ], 500);
        }
        $category = Category::where('id','=',$request->category_id)->first();
        $image = Image::where('id','=',$request->image_id)->first();
        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $productcategory = CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $request->category_id,
            ]);

            $productimage = ProductImage::create([
                'product_id' => $product->id,
                'image_id' => $request->image_id,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Create product unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        $product->category = $category;
        $product->image = $image;

        return response()->json([
            'status' => 'success',
            'message' => 'Create product successfull',
            'data' => $product,
        ], 201);
    }

    public function get($id){
        try {
            $data = Product::where('product.id','=', $id)
            ->join('category_product','product.id','=','category_product.product_id')
            ->join('category','category.id','=','category_product.category_id')
            ->join('product_image','product_image.product_id','=','product.id')
            ->join('image','image.id','=','product_image.image_id')
            ->select('product.id','product.name','product.description','category.name','image.file','category_product.category_id','product_image.image_id')
            ->first();

            if (!$data){
                return response()->json([
                    'status' => 'error',
                    'message' => 'get data product unsuccessfull ',
                    'error' => 'product id not found',
                ], 400);
            } 

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'get data product unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        $data->category = Category::where('id','=',$data->category_id)->first();
        $data->image = Image::where('id','=',$data->image_id)->first();

        unset($data->category_id);
        unset($data->image_id);

        return response()->json([
            'status' => 'success',
            'message' => 'get data product successfull',
            'data' => $data,
        ], 201);
    }

    public function list(Request $request){
        $products = Product::join('category_product','product.id','=','category_product.product_id')
        ->join('category','category.id','=','category_product.category_id')
        ->join('product_image','product_image.product_id','=','product.id')
        ->join('image','image.id','=','product_image.image_id')
        ->select('product.id','product.name','product.description','category.name','image.file')
        ->orderBy('product_id', 'DESC');

        $total = $products->count();

        if ($request->has('limit')) {
            $limit = $request->input('limit');
        } else {
            $limit = 10;
        }

        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }

        $categories->skip(($page - 1) * $limit);
        $categories->take($limit);
        $categories = $categories->get();

        $paginate = [
            'per_page' => $limit,
            'page' => $page,
            'next_page' => true,
            'prev_page' => true,
            'total_record' => $total,
            'total_page' => ceil($total/$limit),
        ];

        if (count($products) < $limit){
            $paginate['next_page'] = false;
        }

        if ($page == 1){
            $paginate['prev_page'] = false;
        }

        return response()->json([
            'status' => 'success',
            'products' => $products,
            'pagination' => $paginate
        ]);
    }

    public function update(ProductRequest $request, $id){
        $data = Product::where('product.id','=', $id)
        ->join('category_product','product.id','=','category_product.product_id')
        ->join('category','category.id','=','category_product.category_id')
        ->join('product_image','product_image.product_id','=','product.id')
        ->join('image','image.id','=','product_image.image_id')
        ->select('product.id','product.name','product.description','category.name','image.file','category_product.category_id','product_image.image_id')
        ->first();

        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Update product unsuccessfull ',
                'error' => 'product id not found',
            ], 400);
        }
        try {
            DB::beginTransaction();
            $data->name = $request->name;
            $data->description = $request->description;
            if($request->has('enable')){
                $data->enable = $request->enable;
            }

            if($data->category_id != $request->category_id){
                $category = Category::where('id','=',$request->category_id)->first();
                $category->category_id = $request->category_id;
                $category->save();
            }else{
                $category = Category::where('id','=',$data->category_id)->first();
            }

            if($data->image_id != $request->image_id){
                $image = Image::where('id','=',$request->image_id)->first();
                $image->image_id = $request->image_id;
                $image->save();
            }else{
                $image = Image::where('id','=',$data->image_id)->first();
            }

            $data->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Update product unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        $data->category = $category;
        $data->image = $image;

        unset($data->category_id);
        unset($data->image_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Update product successfull',
            'data' => $data,
        ], 200);
    }

    public function delete($id){
        $data = Product::where('id','=',$id)->first();
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Delete product unsuccessfull ',
                'error' => 'category id not found',
            ], 400);
        }
        try {
           $data->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete product unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Delete '.$data->name.' product successfull',
        ], 200);
    }
}
