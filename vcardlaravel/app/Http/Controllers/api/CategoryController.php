<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\DefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function show_all(){
        return DefaultCategoryResource::collection(DefaultCategory::all());
    }

    public function show(DefaultCategory $category){
        return new DefaultCategoryResource($category);
    }

    public function create(DefaultCategoryRequest $request){
        $category = DefaultCategory::create($request->validated());

        return new DefaultCategoryResource($category);
    }

    public function delete(DefaultCategory $category){
        $category->delete();

        return new DefaultCategoryResource($category);
    }

    public function update(Request $request,DefaultCategory $category){
        $validator = Validator::make($request->all(),[
            'type' => 'nullable|in:C,D',
            'name' => ['nullable',Rule::unique('default_categories','name')->where(fn($qry) => $qry->where('type',$request->type)->whereNull('deleted_at'))],
        ],[
            'type.in' => 'Type must be either credit or debit',
            'name.unique' => 'type and name must be unique'
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'There were validation errors','errors' => $validator->getMessageBag()],422);
        }

        DB::transaction(fn() => $category->update($request->validated()));

        return $category;
    }

}
