<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\UserRecords;
use Exception;
use Illuminate\Support\Facades\Log;

class UserRecordController extends Controller
{
    public function getUserdata(Request $request)
    {
        try {
            // Attempt to get user records from Redis
            $getUserRecords = Redis::get('user_records');

            // If Redis doesn't have the data, retrieve from the database
            if (!$getUserRecords) {
                $getUserRecords = UserRecords::all();
                // Cache the data in Redis without expiration
                Redis::set('user_records', $getUserRecords->toJson());
            } else {
                $getUserRecords = json_decode($getUserRecords, true);
            }
            return response()->json([
                'status' => 1,
                'success' => true,
                'data' => $getUserRecords,
                'message' => 'User record added successfully'
            ], 200);
        } catch (Exception $e) {
            // Log the error message
            Log::error('Error fetching user data: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'Failed to fetch data. Please try again later.'
            ], 500);
        }
    }

    public function addUserRecord(Request $request)
    {
        try {
            // Validate the given request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            // Add new user to the database
            $user = new UserRecords();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = $validatedData['password'];
            $user->save();

            // Fetch the updated list of users from the database
            $getUserRecords = UserRecords::all();

            // Set the updated user records in Redis
            Redis::set('user_records', $getUserRecords->toJson());

            return response()->json([
                'status' => 1,
                'success' => true,
                'data' => $user,
                'message' => 'User record added successfully'
            ], 201);
        } catch (Exception $e) {
            Log::error('Error adding user record: ' . $e->getMessage());
            // Handling the validation errors
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 500);
            }

            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'Failed to add user record. Please try again later.'
            ], 500);
        }
    }
}
