<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLoginForm()
    {
        // Nếu đã đăng nhập rồi thì không cho vào trang login nữa
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Hàm Auth::attempt() sẽ tự động mã hóa password và so sánh với CSDL
        if (Auth::attempt($credentials)) {
            // Tạo lại session để bảo mật, chống tấn công Session Fixation
            $request->session()->regenerate();

            // PHÂN QUYỀN CHUYỂN HƯỚNG DỰA VÀO CỘT 'role'
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công với quyền Admin!');
            }

            // Nếu là customer thì về trang chủ
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu sai tài khoản/mật khẩu
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công.');
    }

// đăng kí
    // 1. Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 2. Xử lý lưu dữ liệu
    public function register(Request $request)
    {
        // Chuẩn hóa số điện thoại: bỏ khoảng trắng, dấu chấm, dấu gạch ngang
        $request->merge([
            'phone' => preg_replace('/[\s\.\-]/', '', $request->phone),
        ]);
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => [
                'required',
                'regex:/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/',
                'unique:users,phone',
            ],
            'password' => 'required|string|min:6', // Phải có trường password_confirmation ở form
            'password_confirmation' => 'required|string|min:6|same:password',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp.',
        ]);

        // Tạo tài khoản mới vào Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Bắt buộc phải mã hóa mật khẩu
            'role' => 'customer', // Gán cứng role là khách hàng
        ]);

        // Tự động đăng nhập luôn sau khi đăng ký thành công
        Auth::login($user);

        // Chuyển hướng về trang chủ kèm thông báo
        return redirect()->route('home')->with('success', 'Chào mừng bạn đến với KeySpace Piano!');
    }
}
