<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = []; // Akan diganti dengan query orders dari database
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = null; // Akan diganti dengan query order dari database
        return view('orders.show', compact('order'));
    }
} 