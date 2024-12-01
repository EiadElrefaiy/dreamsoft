<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDO;
use Tymon\JWTAuth\Facades\JWTAuth;

class DBTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($guard != null){
            auth()->shouldUse($guard); //shoud you user guard / table
            $token = $request->bearerToken();
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);
            try
            {             
            // Decode the JWT token to extract its payload
            $decoded = JWTAuth::setToken($token)->getPayload();
                
            // Extract the user ID (subject) from the decoded JWT payload
            $id = $decoded['sub'];

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
            $query = $connection->prepare("SELECT COUNT(*), created_at FROM multi_databases WHERE id = :id");

            // Bind parameter
            $query->bindParam(':id', $id);
            
            // Execute the query
            $query->execute();
            
            // Fetch the result
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            // Count of records
            $count = $result['COUNT(*)'];
            
            // Created_at value
            $createdAt = $result['created_at'];
            
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
            $user = JWTAuth::parseToken()->authenticate();            
          }
          catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response() ->json(["status" => false , "msg" => "token expired"]) ;
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response() ->json(["status" => false , "msg" => "Imvalid tokken"]) ;
        }catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            return response() ->json(["status" => false , "msg" => "token not found"]) ;
        }
    }
        return $next($request);
    }
}
