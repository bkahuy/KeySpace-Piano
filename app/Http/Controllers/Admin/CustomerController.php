<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Thêm điều kiện where để lọc đúng vai trò 'customer'
        $customers = User::where('role', 'customer')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Lấy thông tin khách hàng, kèm theo danh sách đơn hàng (sắp xếp đơn mới nhất lên đầu)
        $customer = User::with(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->where('role', 'customer')->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
