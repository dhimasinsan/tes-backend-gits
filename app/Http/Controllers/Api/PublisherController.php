<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $result = ['message' => 'List Publisher'];
        $query = Publisher::query();
        $result['total'] = $query->count();
        $result['data'] = $query->get();

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|unique:publishers',
            'name' => 'required'
        ]);

        $result = ['message' => 'Add to Publisher successfuly', 'status' => 200];
        $publisher['uuid'] = Str::uuid();
        $publisher['code'] = $request->code;
        $publisher['name'] = $request->name;

        DB::beginTransaction();
        try {

            $tambah = Publisher::create($publisher);
            $result['data'] = $tambah;

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to create Publisher',
                'status' => 400
            ];
        }
        DB::commit();

        return response()->json($result, $result['status']);
    }

    public function show($id)
    {

        $query = Publisher::where("id", $id)->first();

        if ($query == null) {
            $result = ['message' => 'Publisher not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Get Publisher successfuly', 'status' => 200];
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

            $publisher = Publisher::where("id", $id)->first();

            if ($publisher != null) {

                $checkCode = Publisher::where('code', $request->code)->whereNot('id', $id)->first();
                if($checkCode != null) {
                    $result = ['message' => 'Code for Publisher is used!', 'status' => 400];
                    return response()->json($result, $result['status']);
                }

                $publisher['code'] = $request->code;
                $publisher['name'] = $request->name;
                $publisher->save();

                $result = ['message' => 'Update Publisher successfuly', 'status' => 200];
            } else {
                $result = ['message' => 'Publisher not found!', 'status' => 404];
            }

            $result['data'] = $publisher;

        } catch (\Throwable $th) {
            DB::rollBack();
            $result = [
                'message' => 'Failed to update Publisher',
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
        $publisher = Publisher::where("id", $id)->first();

        if ($publisher == null) {
            $result = ['message' => 'Publisher not found!', 'status' => 404];
            return response()->json($result, $result['status']);
        }

        $result = ['message' => 'Publisher has been Deleted', 'status' => 200];
        $publisher->delete();

        return response()->json($result, $result['status']);
    }
}
