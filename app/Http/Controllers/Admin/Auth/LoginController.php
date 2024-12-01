<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{
    public function checkDatabaseCredentialsExist($license)
    {   
    try {
        // Hardcoded database connection details
        $master_host = 'localhost';
        $master_port = '3306';
        $master_database = 'sellfora1';
        $master_username = 'root';
        $master_password = '';
        
        // Establish a new database connection to the master database
        $connection = new PDO("mysql:host=$master_host;port=$master_port;dbname=$master_database", $master_username, $master_password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query to check if the provided credentials exist in the multi_databases table
        $query = $connection->prepare("SELECT COUNT(*), created_at, host, port, database_name, username, password FROM multi_databases WHERE license = :license");
        $query->bindParam(':license', $license);
        $query->execute();

        // Fetch the result
        $result = $query->fetch(PDO::FETCH_ASSOC);
            
        // Count of records
        $count = $result['COUNT(*)'];

        // If count > 0, credentials exist; otherwise, they don't exist
        return [
            'count' => $count,
            'host' => $result["host"],
            'port' => $result["port"],
            'database_name' => $result["database_name"],
            'username' => $result["username"],
            'password' => $result["password"],
            'created_at' => $result["created_at"]
        ];
    } catch (PDOException $e) {
        // Handle database connection errors
        // You might want to log the error or handle it differently based on your requirements
        return false;
    }
}

    public function login(Request $request)
    {    
        try {
            $license = $request->license;
            if ($this->checkDatabaseCredentialsExist($license)["count"] > 0) {

            // Hardcoded database connection details
            $host = $this->checkDatabaseCredentialsExist($license)["host"];
            $port = $this->checkDatabaseCredentialsExist($license)["port"];
            $database = $this->checkDatabaseCredentialsExist($license)["database_name"];
            $username = $this->checkDatabaseCredentialsExist($license)["username"];
            $password = $this->checkDatabaseCredentialsExist($license)["password"];

            // Created_at value
            $createdAt = $this->checkDatabaseCredentialsExist($license)["created_at"];
            
            // Parse the created_at timestamp into a Carbon instance
            $createdAtDate = Carbon::parse($createdAt);

            // Add 2 days to the created_at date
            $expirationDate = $createdAtDate->addDays(2);

            // Check if the expiration date is in the past
            $isExpired = $expirationDate->isPast();

            if ($isExpired) {
                // The created_at timestamp has expired after 2 days
                // Perform your desired action here
                return response() ->json(["status" => false , "msg" => "license expired"]) ;
            } 

            // Establish a new database connection using hardcoded details
            $connection = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
            // Set PDO to throw exceptions on error
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

            // Dynamically set the database connection configuration
            config(['database.connections.mysql' => [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]]);

            // Reconnect to the database with the new configuration
            DB::purge('mysql');
            DB::reconnect('mysql');

            
            // Prepare and execute the query to fetch admin based on phone
            $query = $connection->prepare("SELECT * FROM admins WHERE phone = :phone");
            $phone = $request->input('phone');
            $query->bindParam(':phone', $phone);
            $query->execute();
    
            $adminData = $query->fetch(PDO::FETCH_ASSOC);
    
            // Check if admin exists and credentials are correct
            if ($adminData && password_verify($request->password, $adminData['password'])) {
                // Admin found and password matches, retrieve admin from the Admin model
                $admin = Admin::find($adminData['id']);
    
                // Now you can use the $admin Eloquent model instance as usual
                // For example, you can retrieve additional data or perform further actions
                // Generate a JWT token for the authenticated admin
                $token = Auth::guard('api-admin')->attempt(['phone' => $request->phone, 'password' => $request->password]);

                $payload = [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'host' => $host,
                    'port' => $port,
                    'database' => $database,
                    'username' => $username,
                    'license' => $license,
                    'created_at' => $createdAt,
                ];

                // Append database credentials and license to the JWT token payload
                $tokenWithCredentials = Auth::guard('api-admin')->claims($payload)->attempt(['phone' => $request->phone, 'password' => $request->password]);


                // Authentication successful, return admin data
                return response()->json(['token' => $tokenWithCredentials ,'admin' => $admin], 200);
            } else {
                // Authentication failed
                return response()->json(['error' => 'Invalid phone or password'], 401);
            }
          }else{
            return response()->json(['error' => 'Database credentials do not exist'], 401); 
          }
        } catch (PDOException $e) {
            // Handle database connection errors
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }
}    
