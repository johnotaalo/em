<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class UserManagement extends Controller
{
    public function index(Request $request){
    	$searchQueries = $request->get('query');
        $limit = $request->get('limit');
        $page = $request->get('page');
        // $ascending = 0;
        $ascending = $request->get('ascending');
        $byColumn = $request->get('byColumn');
        $orderBy = $request->get('orderBy');

        $queryBuilder = \App\User::select('id', 'name', 'email', 'created_at', 'updated_at', 'user_type', 'status');
        if ($searchQueries) {
        	$queryBuilder = $queryBuilder->where('name', 'LIKE', "%{$searchQueries}%");
        	$queryBuilder = $queryBuilder->orWhere('email', 'LIKE', "%{$searchQueries}%");
        }

        $count = $queryBuilder->count();
        $queryBuilder->limit($limit)->skip($limit * ($page - 1));

        $records = $queryBuilder->get();

        return [
        	'data' => $records,
            'count' => $count
        ];
    }

    public function add(Request $request){
    	$user = new User();

        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->name = "{$request->first_name} {$request->last_name}";
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        $user->status = ($request->status == "true") ? 1 : 0;

        $user->save();

    	return $user;
    }

    public function editUser(Request $request){
        $user = User::findOrFail($request->id);

        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->name = "{$request->first_name} {$request->last_name}";
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        $user->status = ($request->status == "true") ? 1 : 0;

        $user->save();
        
        return $user;
    }

    public function getUser(Request $request){
        $user = User::findOrFail($request->id);

        return $user;
    }

    public function updateUserStatus(Request $request){
        $id = $request->id;

        $user = User::findOrFail($id);
        if ($user) {
            $status = $user->status;

            $user->status = !$status;

            $user->save();

            return $user;
        }
    }

    public function resetPassword(Request $request){
        $id = $request->id;

        $user = User::findOrFail($id);

        if ($user) {
            $credentials = ['email' =>  $user->email];
            $response = Password::sendResetLink($credentials, function(Message $message){
                $message->subject($this->getEmailSubject());
            });

            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return ['message' => trans($response)];
                    break;
                case Password::INVALID_USER:
                    return ['message'   =>  trans($response)];
            }
        }
    }

    public function validateEmail(Request $request){
        $id = $request->id;
        if(!$id){
    	   return ['exists' => \App\User::where('email', $request->email)->exists()];
        }

        $email = $request->email;
        $user = \App\User::where('email', $email)->first();
        if ($user) {
            if ($user->id == $id) {
                return ['exists'    =>  false];
            }else{
                return ['exists'    =>  true];
            }
        }
    }
}
