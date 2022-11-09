<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $result = ['message' => 'List Author'];
        $query = Author::query();
        $result['total'] = $query->count();
        $result['data'] = $query->get();

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|unique:authors',
            'name' => 'required'
        ]);

        $result = ['message' => 'Add to Author successfuly', 'status' => 200];
        $author['uuid'] = Str::uuid();
        $author['code'] = $request->code;
        $author['name'] = $request->name;

        DB::beginTransaction();
        try {

            $tambah = Author::create($author);
            $result['data'] = $tambah;

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to create Author',
                'status' => 400
            ];
        }
        DB::commit();

        return response()->json($result, $result['status']);
    }

    public function show($id)
    {

        $query = Author::where("id", $id)->first();

        if ($query == null) {
            $result = ['message' => 'Author not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Get Author successfuly', 'status' => 200];
        $result['data'] = $query;

        return response()->json($result, $result['status']);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'code' => 'required',
            'name' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $author = Author::where("id", $id)->first();

            if ($author != null) {

                $checkCode = Author::where('code', $request->code)->whereNot('id', $id)->first();
                if($checkCode != null) {
                    $result = ['message' => 'Code for Author is used!', 'status' => 400];
                    return response()->json($result, $result['status']);
                }

                $author['code'] = $request->code;
                $author['name'] = $request->name;
                $author->save();

                $result = ['message' => 'Update Author successfuly', 'status' => 200];
            } else {
                $result = ['message' => 'Author not found!', 'status' => 404];
            }

            $result['data'] = $author;

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to update Author',
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
        $author = Author::where("id", $id)->first();

        if ($author == null) {
            $result = ['message' => 'Author not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Author has been Deleted', 'status' => 200];
        $author->delete();

        return response()->json($result, $result['status']);
    }
}
