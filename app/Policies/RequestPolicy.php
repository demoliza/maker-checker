<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use App\Enums\RequestStatus;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can approve any request.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, Request $request)
    {
        if($request->request_status === RequestStatus::COMPLETED){
            return Response::deny('Request has already been approved', 401);
        }

        if($user->id != $request->request_by){
            return Response::deny('Sorry, You cannot aprove your own request', 401);
        }

        return Response::allow();
    }

    public function decline(User $user, Request $request)
    {
        return $user->id != $request->request_by ? Response::allow() : Response::deny('Sorry, You cannot decline your own request', 401);
    }
}
