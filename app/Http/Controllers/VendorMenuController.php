<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorMenuController extends Controller
{
    /**
     * Constructor - require auth vendor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display list menu vendor
     */
    public function index()
    {
        // Get vendor dari user yang login
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $menus = Menu::where('vendor_id', $vendor->id)
                     ->orderBy('category')
                     ->orderBy('name')
                     ->get();

        return view('vendor.menu.index', compact('menus', 'vendor'));
    }

    /**
     * Show form create menu
     */
    public function create()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        return view('vendor.menu.create', compact('vendor'));
    }

    /**
     * Store menu baru
     */
    public function store(Request $request)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['vendor_id'] = $vendor->id;

        // Handle upload image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('vendor.menu.index')
                         ->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Show form edit menu
     */
    public function edit($id)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $menu = Menu::where('id', $id)
                    ->where('vendor_id', $vendor->id)
                    ->firstOrFail();

        return view('vendor.menu.edit', compact('menu', 'vendor'));
    }

    /**
     * Update menu
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $menu = Menu::where('id', $id)
                    ->where('vendor_id', $vendor->id)
                    ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();

        // Handle upload image baru
        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu->update($data);

        return redirect()->route('vendor.menu.index')
                         ->with('success', 'Menu berhasil diupdate!');
    }

    /**
     * Delete menu
     */
    public function destroy($id)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda bukan vendor!'
            ], 403);
        }

        $menu = Menu::where('id', $id)
                    ->where('vendor_id', $vendor->id)
                    ->firstOrFail();

        // Hapus image jika ada
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil dihapus!'
        ]);
    }

    /**
     * Toggle availability menu (AJAX)
     */
    public function toggleAvailability(Request $request, $id)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda bukan vendor!'
            ], 403);
        }

        $menu = Menu::where('id', $id)
                    ->where('vendor_id', $vendor->id)
                    ->firstOrFail();

        $menu->update([
            'is_available' => !$menu->is_available
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status menu berhasil diubah!',
            'data' => [
                'is_available' => $menu->is_available
            ]
        ]);
    }
}