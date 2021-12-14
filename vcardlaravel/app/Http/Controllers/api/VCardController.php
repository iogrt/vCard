<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\DefaultCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TransactionResource;
use App\Models\AuthUser;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Models\Transaction;
use App\Rules\CategoryRule;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Vcard;
use App\Http\Resources\VCardResource;
use App\Http\Requests\StoreVCardRequest;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VCardController extends Controller
{
    public function getVCard(String $id){//apenas a nivel de conhecimento de dados
        //return Vcard::all();
        //return Vcard::find($id);//sem usar o resource
        //return VCardResource::collection($id);//usando resources, pois nao devolve o dado diretamente
        return new VCardResource(Vcard::findOrFail($id));//se for um user deletado nao funciona.....
    }

    public function getVcardTransactions(Vcard $vcard){

        return TransactionResource::collection($vcard->transactions);
    }

    public function blockVcard(Vcard $vcard){
        return DB::transaction(function() use($vcard){
            $vcard->blocked ^= 1;

            return new VCardResource($vcard);
        });
    }

    //public function store(StoreVCardRequest $request){
    public function store(StoreVCardRequest $request){

        try {
            DB::beginTransaction();

            $find = Vcard::where('phone_number', $request->phone_number)->first();
            if ($find) {
                return response()->json(['message' => 'This phone cant be used!'], 422);
            }

            $finDel = Vcard::withTrashed()->where('phone_number', $request->phone_number)->whereNotNull('deleted_at')->first();
            if (!is_null($finDel)) {
                return response()->json(['message' => 'This phone was used before in a deleted account!!'], 422);
            }

            $now = Carbon::now();//data corrente

            $newCard = new Vcard;

            $newCard->phone_number = $request->phone_number;
            $newCard->name = $request->name;
            $newCard->email = $request->email;
            $newCard->blocked = 0;
            $newCard->password = bcrypt($request->password);
            $newCard->confirmation_code = bcrypt($request->confirmation_code);
            if ($request->file('photo_url') != null) {
                $path = $request->photo_url->store('public/fotos');
                $newCard->photo_url = basename($path);
            }

            $newCard->save();

            $defaultCategories = DefaultCategory::all();

            $defaultCategories->each(function ($item) use ($request) {
                Category::create([
                    'vcard' => $request->phone_number,
                    'name' => $item->name,
                    'type' => $item->type,
                ]);
            });

            DB::commit();
        }
        catch(\Throwable $th){
            DB::rollBack();
            return response()->json(['message' => 'Internal server Error','error' => $th->getMessage()],500);
        }

        return new VCardResource($newCard);
    }

    public function deleteVcard(Request $request){
        $vcard = Vcard::find(Auth::user()->username);

        $vcardCategories = $vcard->categories;
        $vcardTransactions = $vcard->transactions;

        $vcardCategories->each(function($category,$key){
            $trans = DB::table('transactions')->leftJoin('categories',function($join) use($category){
                $join->on('category_id','=','categories.id');
            })
                ->where('transactions.vcard',Auth::user()->username)
                ->where('categories.name',$category->name)
                ->where('categories.type',$category->type)->get();

            $this->removeCategoryHelper($category->name,$category->type,$trans);
        });

        if(count($vcardTransactions) > 0) {
            $vcard->delete();
        }
        else {
            $vcard->forceDelete();
        }

        return (new AuthController)->logout($request);
    }

    public function getCategories(){
        return CategoryResource::collection(Vcard::find(Auth::user()->username)->categories);
    }

    public function getCategory($id){
        $category = Category::where('vcard',Auth::user()->username)->where('id',$id)->first();

        if($category != null){
            return new CategoryResource($category);
        }

        return response()->json([
            'error' => 'could not find category'
        ],500);
    }

    public function addCategoryFromDefault(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => ['required','exists:default_categories,id,deleted_at,NULL'],
        ],[
            'id.required' => 'Required category',
            'id.exists' => 'Category does not exist in default table',
        ])['id'];


        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        $def = DefaultCategory::find($request->id);

        $category = Category::create([
            'type' => $def->type,
            'name' => $def->name,
            'vcard' => Auth::user()->username,
        ]);

        return new CategoryResource($category);
    }

    public function addNewCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('categories','name')->where(fn($qry) => $qry->where('vcard',Auth::user()->username)->where('type',$request->type))],
            'type' => 'in:C,D'
        ],[
            'name.required' => 'Required category name',
            'name.string' => 'Name must be text',
            'type.in' => 'Type must be either (C)redit or (D)debit',
        ]);

        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        $category = Category::create([
            'vcard' => Auth::user()->username,
            'type' => $request->type,
            'name' => $request->name,
        ]);

        return new CategoryResource($category);
    }

    public function alterCategory(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'type' => 'in:C,D',
        ],[
            'name.string' => 'Name must be text',
            'type.in' => 'Type must be either (C)redit or (D)debit',
        ]);

        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        DB::beginTransaction();

        $category = Category::find($request->id);

        if($category == null){
            return response()->json(["message" => "Invalid Id"],403);
        }

        if($request->name){
            $category->name = $request->name;
        }

        if($request->type){
            $category->type = $request->type;
        }

        $category->update();

        DB::commit();

        return new CategoryResource($category);
    }

    private function removeCategoryHelper($name,$type,$transactions){
        $category = Category::where('name','like',$name)
            ->where('type',$type)
            ->where('vcard',Auth::user()->username)->first();

        if(count($transactions) > 0){
            $category->delete();
        }
        else {
            $category->forceDelete();
        }

        return $category;
    }

    public function removeCategory(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => ['required','string',Rule::exists('categories','name')->where(fn($qry) => $qry->where('vcard',Auth::user()->username)->where('type',$request->type))],
            'type' => 'in:C,D'
        ]);

        if($validator->fails()){
            return response()->json(["message" => "Validation Errors!","errors" => $validator->getMessageBag()],422);
        }

        $vcardTransactions = DB::table('transactions')->leftJoin('categories',function($join) use($request){
            $join->on('category_id','=','categories.id');
        })
            ->where('transactions.vcard',Auth::user()->username)
            ->where('categories.name',$request->name)
            ->where('categories.type',$request->type)->get();
        //dd($vcardTransactions);

        return new CategoryResource($this->removeCategoryHelper($request->name,$request->type,$vcardTransactions));
    }

}




