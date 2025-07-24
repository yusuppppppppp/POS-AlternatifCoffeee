@extends('layouts.app')
@section('title', 'Order List')

@section('content')
<div class="container">
    <h2>History Pembelian Hari Ini</h2>
    @foreach($orders as $order)
        <div class="card" style="margin-bottom: 20px; padding: 15px; background: #fff; border-radius: 10px;">
            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            <p><strong>Type:</strong> {{ $order->order_type }}</p>
            <p><strong>Waktu:</strong> {{ $order->created_at->format('H:i:s') }}</p>
            <p><strong>Total:</strong> Rp. {{ number_format($order->total_amount) }}</p>
            <hr>
            <ul>
                @foreach($order->items as $item)
                    <li>{{ $item->quantity }}x {{ $item->menu_name }} (Rp. {{ number_format($item->price) }})</li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @if($orders->isEmpty())
        <p>Tidak ada pesanan hari ini.</p>
    @endif
</div>
@endsection
