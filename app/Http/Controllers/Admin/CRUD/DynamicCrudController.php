<?php

namespace App\Http\Controllers\Admin\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DynamicCrudController extends Controller
{
    public function create(Request $request)
    {
        $table = $request->table;
        // Initialize $fileName variable
        $fileName = null;
    
        // Merge the filename with request data
        $requestData = $request->all();

        // Check if the request contains a file named 'image'
        if ($request->hasFile('image')) {
            // Generate a unique filename based on current time and file extension
            $fileName = time() . '.' . $request->file('image')->extension();
            
            // Store the uploaded file in the 'public/images/sections' directory with the generated filename
            $request->file('image')->storeAs('public/images/'.$table, $fileName);

        // Merge the filename with request data
        $requestData = array_merge($request->all(), ['image' => 'images/'.$table.'/'.$fileName]);
        }
    
        unset($requestData['table']);

        // Insert data into database using DB facade
        $record = DB::table($table)->insert(array_merge($requestData, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        // Get the ID of the last inserted record
        $lastInsertedId = DB::getPdo()->lastInsertId();

        // Fetch the inserted record from the database
        $data = DB::table($table)->find($lastInsertedId);

        // Return success response
        return response()->json(['message' => 'Record created successfully' , 'data'=> $data], 201);
    }


    public function read(Request $request)
    {
        $table = $request->table;

        // Get Record from the database
        $data = DB::table($table)->find($request->id);

        // Return success response
        return response()->json(['data'=> $data], 201);

    }

    public function update(Request $request)
    {
        $table = $request->table;

        // Get Record from the database
        $data = DB::table($table)->find($request->id);

        // Initialize $fileName variable
        $fileName = null;
    
        // Merge the filename with request data
        $requestData = $request->all();

        // Check if the request contains a file named 'image'
        if ($request->hasFile('image')) {
            // Generate a unique filename based on current time and file extension
            $fileName = time() . '.' . $request->file('image')->extension();
            
            // Store the uploaded file in the 'public/images/sections' directory with the generated filename
            $request->file('image')->storeAs('public/images/'.$table, $fileName);

            // Delete previous image if exists
            if ($data->image) {
                Storage::delete('public/'.$data->image);
            }
            
            // Update section's image path
            $data->image = 'images/'.$table.'/'.$fileName;

        // Merge the filename with request data
        $requestData = array_merge($request->all(), ['image' => 'images/'.$table.'/'.$fileName]);
        }
    
        unset($requestData['table']);
        
        if ($data) {
            DB::table($table)->where('id', $request->id)->update(array_merge($requestData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }else{
            return response()->json(['message'=> 'not found'], 404);
        }
        // Return success response
        return response()->json(['message' => 'Record created successfully' , 'data'=> $data], 201);

    }



    public function delete(Request $request)
    {
        $table = $request->table;

        // Delete record from the database
        $deleted = DB::table($table)->where('id', $request->id)->delete();

        if ($deleted) {
            // If the record was successfully deleted
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } else {
            // If the record was not found or couldn't be deleted
            return response()->json(['message' => 'Record not found or could not be deleted'], 404);
        }
    }

}
