<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\RequestTypes;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => "Requests retrieved successfully",
            "data" => RequestModel::where(['request_status'=>'0'])->get()
        ]);
    }

    public function approve(Request $request, RequestModel $requestModel)
    {
        $requestData = RequestModel::findorFail($request->id);
        $this->authorize('approve', $requestData);
        
        $user =  match($requestData->request_type) {
            RequestTypes::CREATE->value => $requestModel->create_user($requestData),
            RequestTypes::UPDATE->value => $requestModel->update_user($requestData),
            RequestTypes::DELETE->value => $requestModel->delete_user($requestData),
            default => throw new \InvalidArgumentException("Invalid request type"),
        };

        return response()->json([
            'success' => true,
            'message' => "Request approved successfully",
            "data" => $user
        ]);
    }

    public function decline(Request $request, RequestModel $requestModel)
    {
        $requestData = RequestModel::findorFail($request->id);
        $this->authorize('decline', $requestData);

        $requestData->delete();

        return response()->json([
            'success' => true,
            'message' => "Request declined successfully"
        ]);
    }
}
