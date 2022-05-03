<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RequestStatus;
use App\Casts\Json;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Database\Factories\RequestFactory;

class Request extends Model
{
    protected $guarded = [];

    protected $casts = [
        'request_status' => RequestStatus::class,
        'meta' => Json::class,
    ];

    public function create_user($requestData)
    {
        $meta = $requestData->meta;
        $meta['password'] = Hash::make($meta['password']);
        $user = User::create($meta);

        if($user){
            $requestData->request_status = RequestStatus::COMPLETED;
            $requestData->approved_by = auth()->user()->id;
            $requestData->save();

            return $user;
        }
        
    }

    public function update_user($requestData)
    {
        $user = User::findorFail($requestData->meta['id']);
        $meta = $requestData->meta;
        isset($meta['password']) ?? Hash::make($meta['password']);
        $updated = User::where('id', $user->id)->update($meta);

        if($updated){
            $requestData->request_status = RequestStatus::COMPLETED;
            $requestData->approved_by = auth()->user()->id;
            $requestData->save();

            return $user->refresh();
        }
        
    }

    public function delete_user($requestData)
    {
        $user = User::findorFail($requestData->meta['id']);
        $deleted = $user->delete();

        if($deleted){
            $requestData->request_status = RequestStatus::COMPLETED;
            $requestData->approved_by = auth()->user()->id;
            $requestData->save();

            return $user;
        }
        
    }

    protected static function newFactory()
    {
        return RequestFactory::new();
    }
}
