<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(){}

    public function create(CategoryRequest $request){
        try {
            $data = Category::create([
                'name' => $request->name,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Create category unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create category successfull',
            'data' => $data,
        ], 201);
    }

    public function get($id){
        try {
            $data = Category::where('id','=', $id)->first();
            if (!$data){
                return response()->json([
                    'status' => 'error',
                    'message' => 'get data category unsuccessfull ',
                    'error' => 'category id not found',
                ], 400);
            } 

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'get data category unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'get data category successfull',
            'data' => $data,
        ], 201);
    }

    public function list(Request $request){
        $categories = Category::orderBy('id', 'DESC');

        $total = $categories->count();

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

        if (count($categories) < $limit){
            $paginate['next_page'] = false;
        }

        if ($page == 1){
            $paginate['prev_page'] = false;
        }

        return response()->json([
            'status' => 'success',
            'categories' => $categories,
            'pagination' => $paginate
        ]);
    }

    public function update(CategoryRequest $request, $id){
        $data = Category::where('id','=',$id)->first();
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Update category unsuccessfull ',
                'error' => 'category id not found',
            ], 400);
        }
        try {
            $data->name = $request->name;
            if($request->has('enable')){
                $data->enable = $request->enable;
            }

            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update category unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update category successfull',
            'data' => $data,
        ], 200);
    }

    public function delete($id){
        $data = Category::where('id','=',$id)->first();
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Delete category unsuccessfull ',
                'error' => 'category id not found',
            ], 400);
        }
        try {
           $data->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete category unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Delete '.$data->name.' category successfull',
        ], 200);
    }
}
