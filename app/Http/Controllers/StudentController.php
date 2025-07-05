<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;


class StudentController extends Controller
{
    //

    function list(){
        return Students::all();
    }

   function addstudent(Request $request) {
    // Create new student instance
    $student = new Students();
    
    // Debugging - better to use proper logging
    // print_r($request);
    // die;
    
    // Validate the request data first
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:50',
        'email' => 'required|email|unique:students,email|max:100',
        // Add other fields as needed
    ]);
    
    try {
        // Assign values
        $student->first_name = $request->first_name;
        $student->email = $request->email;
        // Add other fields here
        
        if ($student->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Student added successfully',
                'data' => $student
            ], 201); // 201 Created status code
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save student'
            ], 500); // 500 Internal Server Error
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred',
            'error' => $e->getMessage()
        ], 500);
    }
}
	// first_name	
	// email 

}

