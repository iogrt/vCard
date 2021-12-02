<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\DefaultCategoryRequest;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function listDefaultCategories(){
        return DefaultCategoryResource::collection(DefaultCategory::all());
    }

    public function createDefaultCategory(DefaultCategoryRequest $request){
        $category = DefaultCategory::create($request->validated());

        return new DefaultCategoryResource($category);
    }

    public function deleteDefaultCategory(DefaultCategory $category){
        $category->delete();

        return new DefaultCategoryResource($category);
    }

}
