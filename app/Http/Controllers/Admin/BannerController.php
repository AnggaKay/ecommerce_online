<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = [
            'home_slider' => 'Home Slider',
            'home_banner' => 'Home Banner',
            'category_banner' => 'Category Banner',
            'sidebar_banner' => 'Sidebar Banner',
        ];
        
        return view('admin.banners.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:50',
            'position' => 'required|string|max:50',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('banners', $filename, 'public');
            $data['image'] = $path;
        }
        
        $data['is_active'] = $request->has('is_active');
        
        Banner::create($data);
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $positions = [
            'home_slider' => 'Home Slider',
            'home_banner' => 'Home Banner',
            'category_banner' => 'Category Banner',
            'sidebar_banner' => 'Sidebar Banner',
        ];
        
        return view('admin.banners.edit', compact('banner', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:50',
            'position' => 'required|string|max:50',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            
            $image = $request->file('image');
            $filename = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('banners', $filename, 'public');
            $data['image'] = $path;
        }
        
        $data['is_active'] = $request->has('is_active');
        
        $banner->update($data);
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        // Delete image
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    /**
     * Toggle the active status of the banner.
     */
    public function toggleActive(Banner $banner)
    {
        $banner->update([
            'is_active' => !$banner->is_active
        ]);
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Status banner berhasil diperbarui.');
    }

    /**
     * Update the sort order of banners.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'integer|exists:banners,id',
        ]);
        
        foreach ($request->orders as $index => $id) {
            Banner::where('id', $id)->update(['sort_order' => $index]);
        }
        
        return response()->json(['success' => true]);
    }
} 