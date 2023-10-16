<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Tạo key cache từ URL request và phương thức request
        $cacheKey = md5('response:' . $request->fullUrl() . ':' . $request->method());
        // Kiểm tra nếu response đã được cache
        if (Cache::has($cacheKey)) {
            // Lấy response từ cache và trả về
            return Cache::get($cacheKey);
        }

        // Xử lý request và lấy response
        $response = $next($request);
//        dd($cacheKey);
        // Lưu response vào cache trong một khoảng thời gian nhất định (ví dụ: 1 giờ)
        Cache::put($cacheKey, $response, now()->addMinute());

        // Trả về response
        return $response;
    }
}
