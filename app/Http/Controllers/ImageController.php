<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Storage;

class ImageController extends Controller
{
    public function __construct(){}

    public function create(ImageRequest $request){
        if(!$request->hasFile('file')){
            return response()->json([
                'status'   => 'error',
                'message'   => 'Validation errors',
                'error'      => 'image is required'
            ], 500);
        }
        $image_path = $request->file('file')->store('image', 'public');
        try {
            $data = Image::create([
                'name' => $request->name,
                'file' => $image_path
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Create image unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create image successfull',
            'data' => $data,
        ], 201);
    }

    public function get($id){
        try {
            $data = Image::where('id','=', $id)->first();
            if (!$data){
                return response()->json([
                    'status' => 'error',
                    'message' => 'get data image unsuccessfull ',
                    'error' => 'category id not found',
                ], 400);
            } 

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'get data image unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'get data image successfull',
            'data' => $data,
        ], 201);
    }

    public function list(Request $request){
        $images = Image::orderBy('id', 'DESC');

        $total = $images->count();

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

        $images->skip(($page - 1) * $limit);
        $images->take($limit);
        $images = $images->get();

        $paginate = [
            'per_page' => $limit,
            'page' => $page,
            'next_page' => true,
            'prev_page' => true,
            'total_record' => $total,
            'total_page' => ceil($total/$limit),
        ];

        if (count($images) < $limit){
            $paginate['next_page'] = false;
        }

        if ($page == 1){
            $paginate['prev_page'] = false;
        }

        return response()->json([
            'status' => 'success',
            'categories' => $images,
            'pagination' => $paginate
        ]);
    }

    public function update(ImageRequest $request, $id){
        $data = Image::where('id','=',$id)->first();
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Update image unsuccessfull ',
                'error' => 'image id not found',
            ], 400);
        }
        try {
            $data->name = $request->name;
            if($request->hasFile('file')){
                if(Storage::exists($data->file)){
                    Storage::delete($data->file);
                }
                $data->file = $request->file('file')->store('image', 'public');
            }
            if($request->has('enable')){
                $data->enable = $request->enable;
            }

            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update image unsuccessfull ',
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
        $data = Image::where('id','=',$id)->first();
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Delete image unsuccessfull ',
                'error' => 'category id not found',
            ], 400);
        }
        try {
            if(Storage::exists($data->file)){
                Storage::delete($data->file);
            }
           $data->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete image unsuccessfull ',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Delete '.$data->name.' category successfull',
        ], 200);
    }
}
