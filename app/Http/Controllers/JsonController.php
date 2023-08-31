<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JsonData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class JsonController extends Controller
{

    public function store(Request $request)
    {


        // if (Auth::check()) {
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
        // } else {
        //     return response()->json(['message' => 'No user is currently logged in']);

        // }
        // if (Auth::check()) {
        //     // Debugging
        //     dd('User is logged in');
        //     // Rest of your code
        // } else {
        //     // Debugging
        //     dd('User is not logged in');
        //     // Rest of your code
        // }


    }

    public function get()
    {
        $data = JsonData::all();
        if ($data) {

            return response($data);
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
        // $encodedData = json_encode($jsonData);

        // // URL-encode the JSON data
        // $urlEncodedData = urlencode($encodedData);

        // // Construct the URL
        // $url = route('json.share') . '?data=' . $urlEncodedData;


        $token = Str::random(32); // Generate a random token
        $jsonData = JsonData::where('id', $id)->first(); // Fetch the JSON data

        // Store the token and associated JSON data in a cache or database table
// For example, you can use Laravel's Cache or Eloquent to store this information
        cache()->put($token, $jsonData, now()->addHours(1));
        $url = route('json.share', ['token' => $token]);

        return $url;


    }
    public function displayJson(Request $request, $token)
    {


        Log::info('Received token: ' . $token);

        // Step 1: Verify if the token is in the cache
        $jsonData = cache()->get($token);

        if (!$jsonData) {
            Log::warning('Data not found for token: ' . $token);
            abort(404); // Data not found
        }

        // Process and return the JSON data as needed
        return response()->json($jsonData);
    }

    public function delete($id)
    {
        // if (Auth::check()){

        $user = User::find($id);
        if ($user) {

            $ok = $user->delete();
            if ($ok) {

                return response()->json(['message' => 'User deleted successfully']);
            }
        } else {
            return response()->json(['message' => 'No User exists with id ' . $id]);
        }
        // }else{
        //         return response()->json(['message' => 'Login First']);
        // }

    }

    public function reject($id)
    {
        // if (Auth::check()){
        $data = JsonData::find($id);
        if ($data) {

            $ok = $data->delete();
            if ($ok) {

                return back()->with('message', 'Removed JSON Data');
            }
        } else {
            return response()->json(['message' => 'There is no record with id ' . $id]);
        }
        // }else{
        //         return response()->json(['message' => 'Login First']);
        // }
    }


}