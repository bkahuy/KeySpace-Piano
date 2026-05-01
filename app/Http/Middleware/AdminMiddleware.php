<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // 2. Kiểm tra xem cột 'role' có phải là 'admin' không
        if (Auth::user()->role !== 'admin') {
            // Nếu là khách mà cố tình vào admin, đuổi về trang chủ
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập khu vực này!');
        }

        // Nếu qua được 2 ải trên, cho phép đi tiếp vào trang Admin
        return $next($request);
    }
}
