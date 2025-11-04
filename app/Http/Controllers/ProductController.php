<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('supplier');
        $query = Product::query();
        // Cek apakah ada parameter 'search' di request
        if ($request->has('search') && $request->search != '') {
            // Melakukan pencarian berdasarkan nama produk atau informasi
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        $firstProduct = Product::orderBy('created_at', 'asc')->first();
        $lastProduct  = Product::orderBy('created_at', 'desc')->first();

        $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
        $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;

        // Sorting 
        if ($request->has('sort_by') && $request->sort_by != '') {
            $order = $request->has('order') && $request->order == 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $order);
        }

        // Jika tidak ada parameter ‘search’, langsung ambil produk dengan paginasi
        $data = $query->paginate(2)->appends($request->query());
        // return $data;
        return view("master-data.product-master.index-product", compact('data', 'startDate', 'endDate'));

        // $data = Product::paginate(perPage: 2);
        // return view("master-data.product-master.index-product", compact('data'));
        // $data = Product::all();
        // return view('layouts-percobaan.app');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view("master-data.product-master.create-product", compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // validasi input data
            $validasi_data = $request->validate([
                'product_name' => 'required|string|max:255',
                'unit'         => 'required|string|max:50',
                'type'         => 'required|string|max:50',
                'information'  => 'nullable|string',
                'qty'          => 'required|integer',
                'producer'     => 'required|string|max:255',
                'supplier_id'  => 'required|exists:suppliers,id',
            ]);

            // Proses simpan data ke dalam database
            Product::create($validasi_data);

            return redirect()->route('product-index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create product!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail(id: $id);
        return view(view: "master-data.product-master.detail-product", data: compact(var_name: 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('master-data.product-master.edit-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'unit' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'information' => 'nullable|string',
                'qty' => 'required|integer|min:1',
                'producer' => 'required|string|max:255',
            ]);

            $product = Product::findOrFail($id);
            $product->update([
                'product_name' => $request->product_name,
                'unit' => $request->unit,
                'type' => $request->type,
                'information' => $request->information,
                'qty' => $request->qty,
                'producer' => $request->producer,
            ]);

            return redirect()->route('product-index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // // buat ngehapus seluruh data di database
        // Product::truncate();
        // return redirect()->back()->with('error', 'Product tidak ditemukan.');

        // // buat ngehapus berdasar tipe, misal kue
        // $deleted = Product::where('type', 'kue')->delete();
        // if (deleted){
        //     return redirect()->back()->with('success', 'Semua Produk berhasil dihapus');
        // }

        // buat ngehapus berdasarkan id
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Product berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Product tidak ditemukan.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'product.xlsx');
    }

    public function exportPdf()
    {
        $products = Product::all();

        // Ambil tanggal pertama dan terakhir
        $firstProduct = Product::orderBy('created_at', 'asc')->first();
        $lastProduct  = Product::orderBy('created_at', 'desc')->first();

        $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
        $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;

        $pdf = Pdf::loadView('master-data.product-master.export-product', compact('products', 'startDate', 'endDate'));
        return $pdf->download('product.pdf');
    }


    public function exportJpg()
    {
        // Ambil semua produk
        $products = Product::all();

        // Ambil tanggal pertama dan terakhir
        $firstProduct = Product::orderBy('created_at', 'asc')->first();
        $lastProduct  = Product::orderBy('created_at', 'desc')->first();

        $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
        $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;

        $path = storage_path('app/public/product.jpg');

        Browsershot::html(view('master-data.product-master.export-product', [
            'products'  => $products,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ])->render())
            ->setScreenshotType('jpeg')
            ->windowSize(1200, 800)
            ->save($path);

        return response()->download($path);
    }
}
