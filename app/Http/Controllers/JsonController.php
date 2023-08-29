<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JsonData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JsonController extends Controller
{

    public function store(Request $request)
    {

        // Validate that the request contains a JSON payload
        // $validator = Validator::make($request->json()->all(), [
        //     '*' => 'required|non_empty_json',
        // ]);
        // // Check if validation fails
        // if ($validator->fails()) {
        //     return response()->json(['error' => 'Invalid JSON payload'], 400);
        // }


        // If validation passes, continue to store the JSON data
        if (Auth::check()) {
            $userId = Auth::user()->id;
            // You now have the ID of the logged-in user
            $jsonData = new JsonData();
            $jsonData->data = json_encode($request->all());
            $jsonData->user_id = $userId;
            $ok = $jsonData->save();
            if ($ok) {

                return response()->json(['message' => 'JSON data stored successfully']);
            } else {
                return response()->json(['message' => 'This data can not be stored\n ']);

            }
        } else {
            return response()->json(['message' => 'No user is currently logged in']);

        }


    }

    public function get()
    {
        $data = JsonData::all();
        if ($data) {

            return response()->json($data);
        } else {
            return response()->json(['message' => 'No data available']);
        }

    }

    public function shareJson($id)
    {
        // $jsonData = [
        //     'id' => 123,
        //     'name' => 'example',
        //     'category' => 'news',
        // ];

        $jsonData = JsonData::where('id', $id)->value('data');


        // Encode the JSON data
        $encodedData = json_encode($jsonData);

        // URL-encode the JSON data
        $urlEncodedData = urlencode($encodedData);

        // Construct the URL
        $url = route('json.share') . '?data=' . $urlEncodedData;

        return $url;


    }
    public function displayJson(Request $request)
    {
        $encodedData = $request->query('data');
        $jsonData = json_decode(urldecode($encodedData), true);

        return response()->json($jsonData);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {

            $ok = $user->delete();
            if ($ok) {

                return response()->json(['message' => 'User deleted successfully']);
            }
        } else {
            return response()->json(['message' => 'No User exists with id ' . $id]);
        }

    }

    public function reject($id)
    {
        $data = JsonData::find($id);
        if ($data) {

            $ok = $data->delete();
            if ($ok) {

                return back()->with('message', 'Removed JSON Data');
            }
        } else {
            return response()->json(['message' => 'There is no record with id ' . $id]);
        }
    }

}