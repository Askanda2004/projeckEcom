<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SellerProfile;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = SellerProfile::firstOrCreate(['user_id' => $user->id ?? $user->user_id], [
            'shop_name' => $user->name, // ค่าเริ่มต้น
        ]);

        return view('seller.profile.edit', compact('profile', 'user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $key  = $user->id ?? $user->user_id;

        $data = $request->validate([
            'shop_name' => ['required','string','max:255'],
            'address'   => ['nullable','string','max:5000'],
            'logo'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'photo'     => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $profile = SellerProfile::firstOrCreate(['user_id' => $key]);
        $profile->shop_name = $data['shop_name'];
        $profile->address   = $data['address'] ?? null;

        // อัปโหลดไฟล์ (เก็บที่ storage/app/public/seller_profiles/{user}/)
        $dir = "seller_profiles/{$key}";
        if ($request->hasFile('logo')) {
            if ($profile->logo_path) Storage::disk('public')->delete($profile->logo_path);
            $profile->logo_path = $request->file('logo')->store($dir, 'public');
        }
        if ($request->hasFile('photo')) {
            if ($profile->photo_path) Storage::disk('public')->delete($profile->photo_path);
            $profile->photo_path = $request->file('photo')->store($dir, 'public');
        }

        $profile->save();

        return back()->with('status','บันทึกโปรไฟล์ร้านเรียบร้อย');
    }
}
