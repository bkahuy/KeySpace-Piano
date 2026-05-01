<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 1. Cập nhật thông tin
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu gửi lên
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        // 1. Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // 2. Xử lý đổi mật khẩu (nếu người dùng có nhập)
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Kiểm tra mật khẩu cũ có đúng không
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Mật khẩu hiện tại không chính xác!');
            }
            // Gán mật khẩu mới
            $user->password = Hash::make($request->new_password);
            $user->save();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

        }

        $user->save();

        return redirect()->route('home')->with('success', 'Đã cập nhật thông tin thành công');
    }
}
