<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PosController extends Controller
{
    // --- HALAMAN UTAMA ---
    public function index(Request $request)
    {
        $setting = Setting::first();
        $categories = Category::all();
        $query = Product::with('category'); // Eager load category

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->latest()->get();
        return view('pos', compact('products', 'categories', 'setting'));
    }

    // --- PRODUCT CRUD ---
    
    // 1. Create Product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_name' => 'required|string', 
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|max:5120' // Max 5MB
        ]);

        $category = Category::firstOrCreate(['name' => ucwords($request->category_name)]);
        $path = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'name' => $request->name,
            'category_id' => $category->id,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $path
        ]);

        return back()->with('success', 'Asset created successfully.');
    }

    // 2. Update Product (Edit)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'category_name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        // Update Kategori
        $category = Category::firstOrCreate(['name' => ucwords($request->category_name)]);

        // Update Gambar (Jika ada upload baru)
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika bukan link online
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $category->id,
            'price' => $request->price,
            'stock' => $request->stock,
            // Image diupdate otomatis di atas jika ada
        ]);

        return back()->with('success', 'Asset details updated.');
    }

    // 3. Delete Product (Hapus)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus gambar fisik
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return back()->with('success', 'Asset removed from inventory.');
    }

    // --- CATEGORY CRUD ---

    public function storeCategory(Request $request)
    {
        Category::create(['name' => ucwords($request->name)]);
        return back()->with('success', 'Classification added.');
    }

    public function updateCategory(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $cat->update(['name' => ucwords($request->name)]);
        return back()->with('success', 'Classification renamed.');
    }

    public function destroyCategory($id)
    {
        // Set produk yg pake kategori ini jadi NULL (Uncategorized)
        Product::where('category_id', $id)->update(['category_id' => null]);
        Category::destroy($id);
        return back()->with('success', 'Classification deleted.');
    }

    // --- TRANSAKSI & LAINNYA ---

    public function checkout(Request $request)
    {
        $cart = json_decode($request->cart_json, true);
        if (!$cart || count($cart) < 1) return back()->with('error', 'Cart is empty.');

        $trx = DB::transaction(function () use ($cart, $request) {
            $subtotal = $request->total_input; 
            $tax = $request->tax_input ?? 0;
            $discount = $request->discount_input ?? 0;
            $customer = $request->customer_name ?? 'Guest';
            $grand_total = max(0, ($subtotal + $tax) - $discount);

            $transaction = Transaction::create([
                'invoice_code' => 'KSH-' . strtoupper(uniqid()),
                'customer_name' => $customer,
                'total_price' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'grand_total' => $grand_total,
                'cash_amount' => $request->cash_amount,
                'change_amount' => $request->cash_amount - $grand_total
            ]);

            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }
            return $transaction;
        });

        return redirect()->route('pos.print', $trx->id); 
    }

    // 4. HALAMAN LAPORAN (EXECUTIVE DASHBOARD)
    public function report()
    {
        // 1. Data Transaksi Lengkap
        $transactions = Transaction::with('details.product')->latest()->get();
        
        // 2. Ringkasan Keuangan
        $total_income = $transactions->sum('grand_total');
        $total_items_sold = TransactionDetail::sum('qty');
        $today_income = Transaction::whereDate('created_at', now())->sum('grand_total');

        // 3. Produk Terlaris (Best Seller Logic)
        $best_seller = TransactionDetail::select('product_id', DB::raw('sum(qty) as total_qty'))
                        ->groupBy('product_id')
                        ->orderByDesc('total_qty')
                        ->with('product')
                        ->first();

        // 4. Data Grafik (7 Hari Terakhir)
        $chart_data = Transaction::select(
                        DB::raw('DATE(created_at) as date'), 
                        DB::raw('SUM(grand_total) as total')
                      )
                      ->where('created_at', '>=', now()->subDays(7))
                      ->groupBy('date')
                      ->orderBy('date')
                      ->get();

        // Format data untuk Chart.js (Frontend)
        $labels = $chart_data->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M'); 
        });
        $data = $chart_data->pluck('total');

        return view('report', compact(
            'transactions', 
            'total_income', 
            'total_items_sold', 
            'today_income', 
            'best_seller',
            'labels',
            'data'
        ));
    }

    public function updateSettings(Request $request)
    {
        $setting = Setting::first();
        
        // Perbaikan: Gunakan 'except' untuk membuang _token
        $setting->update($request->except('_token'));

        return back()->with('success', 'Store Profile Updated Successfully!');
    }

    public function printStruk($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        $setting = Setting::first();
        return view('struk', compact('transaction', 'setting'));
    }
}