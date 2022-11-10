<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $result = ['message' => 'List Book'];
        $query = Book::query();
        $result['total'] = $query->count();
        $result['data'] = BookResource::collection($query->get());

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        $validateRequest = Validator::make($request->all(), 
        [
            'code' => 'required|unique:books',
            'name' => 'required',
            'author_id' => 'required|numeric',
            'publisher_id' => 'required|numeric'
        ]);

        if($validateRequest->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateRequest->errors()
            ], 400);
        }

        $result = ['message' => 'Add to Book successfuly', 'status' => 200];
        $book['uuid'] = Str::uuid();
        $book['code'] = $request->code;
        $book['name'] = $request->name;
        $book['author_id'] = $request->author_id;
        $book['publisher_id'] = $request->publisher_id;

        DB::beginTransaction();
        try {

            $tambah = Book::create($book);
            $result['data'] = new BookResource($tambah);

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to create Book',
                'status' => 400
            ];
        }
        DB::commit();
        
        return response()->json($result, $result['status']);
    }

    public function show($id)
    {

        $query = Book::where("id", $id)->first();

        if ($query == null) {
            $result = ['message' => 'Book not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Get Book successfuly', 'status' => 200];
        $result['data'] = new BookResource($query);

        return response()->json($result, $result['status']);
    }

    public function update($id, Request $request)
    {

        $validateRequest = Validator::make($request->all(), 
        [
            'code' => 'required',
            'name' => 'required',
            'author_id' => 'required|numeric',
            'publisher_id' => 'required|numeric'
        ]);

        if($validateRequest->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateRequest->errors()
            ], 400);
        }

        DB::beginTransaction();
        try {

            $book = Book::where("id", $id)->first();

            if ($book != null) {

                $checkCode = Book::where('code', $request->code)->whereNot('id', $id)->first();
                if($checkCode != null) {
                    $result = ['message' => 'Code for Book is used!', 'status' => 400];
                    return response()->json($result, $result['status']);
                }

                $book['code'] = $request->code;
                $book['author_id'] = $request->author_id;
                $book['publisher_id'] = $request->publisher_id;
                $book['name'] = $request->name;
                $book->save();

                $result = ['message' => 'Update Book successfuly', 'status' => 200];
                $result['data'] = new BookResource($book);
                return response()->json($result, $result['status']);

            } else {
                $result = ['message' => 'Book not found!', 'status' => 404];
                return response()->json($result, $result['status']);
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to update Book',
                'status' => 400
            ];
        }
        DB::commit();

        return response()->json($result, $result['status']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $book = Book::where("id", $id)->first();

        if ($book == null) {
            $result = ['message' => 'Book not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Book has been Deleted', 'status' => 200];
        $book->delete();

        return response()->json($result, $result['status']);
    }
}
