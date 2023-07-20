<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckTypeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        if($this->checkType($type) || Auth::user()->type == SUPER_ADMIN) {
            return $next($request);
        }
        return response()->json([
            'message' => trans('messages.mes.permission'),
            'status' => Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);
    }

    private function getType() {
        return [
            SUPER_ADMIN => 'super_admin' ,
            COMPANY_MANAGER => 'company_manager',
            HR_MANAGER => 'hr_manager',
            COMPANY =>'company',
            HR => 'hr' ,
        ];
    }

    private function checkType($type)
    {
        $listType = explode('|', $type);
        $types = [
            'super_admin',
            'company_manager',
            'hr_manager',
            'company',
            'hr',
        ];
        if (count(array_diff($listType, $types)) == 0 && in_array($this->getType()[Auth::user()->type], $listType)) {
            return true;
        }
        return false;
    }
}
