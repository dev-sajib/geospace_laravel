<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Get user list for freelancer
     *
     * @return JsonResponse
     */
    public function userList(): JsonResponse
    {
        try {
            $users = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->select(
                    'u.user_id',
                    'u.email',
                    'u.created_at',
                    'u.last_login',
                    'u.is_active',
                    'r.role_name',
                    'ud.first_name',
                    'ud.last_name',
                    'cd.company_name'
                )
                ->where('u.is_active', true)
                ->where('u.is_verified', true)
                ->orderBy('u.created_at', 'desc')
                ->get();

            // Transform data to match frontend expectations
            $transformedUsers = $users->map(function ($user) {
                return [
                    'UserName' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->company_name ?? $user->email),
                    'Role' => $user->role_name ?? 'Unknown',
                    'Email' => $user->email,
                    'Status' => $user->is_active ? 'Active' : 'Inactive',
                    'JoinedDate' => $user->created_at,
                    'LastActiveDate' => $user->last_login,
                    'user_id' => $user->user_id
                ];
            });

            return response()->json($transformedUsers);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }
}
