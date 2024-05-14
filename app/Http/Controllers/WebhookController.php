<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;

class WebhookController extends Controller
{
    /**
     * Handle webhook request to receive external user data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->json()->all();

        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Store the received user data in the database
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Return a success response
        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function generateRandomUser(Request $request)
    {
        $data = $request->json()->all();

        // Validate incoming data
        $validatedData = $request->validate([
            'link' => 'required|string'
        ]);

        $client = new Client();
        $response = $client->get($validatedData['link']);
        $userData = json_decode($response->getBody()->getContents(), true);
        
        if (isset($userData['results'])){
            foreach ($userData['results'] as $userData) {
                // Store User
                $this->createUser($userData);
            }
            // Return a success response
            return response()->json(['message' => 'User created successfully'], 201);
        } else {
            // Return a failed response
            return response()->json(['message' => 'Failed to create user'], 500);
        }
    }

    private function createUser($userData)
    {
        $user = new User();
        $user->name = $userData['name']['first'];
        $user->email = $userData['email'];
        $user->password = $userData['login']['password'];
        
        $user->save();

        return $user;
    }
}
