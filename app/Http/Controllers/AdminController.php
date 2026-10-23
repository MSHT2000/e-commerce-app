<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SEO;
use App\Models\Smtp;
use App\Models\SubCategory;
use App\Models\Wharehouse;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4'],
        ]);


        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user) {

            return response()->json([
                'message' => 'If this email exists, a reset link was sent.'
            ]);
        }

        $reset_token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(

            ['email' => $user->email],

            [
                'email' => $user->email,
                'reset_token' => Hash::make($reset_token),
                'created_at' => now(),
            ]
        );

        $resetUrl = env('FRONTEND_URL')
            . '/reset-password?token='
            . $reset_token
            . '&email='
            . urlencode($user->email);

        Mail::raw(

            "Click here to reset your password: {$resetUrl}",

            function ($message) use ($user, $reset_token) {

                $message->to($user->email)
                    ->subject("Reset Password token: $reset_token");
            }
        );

        return response()->json([
            // 'message' => 'If this email exists, a reset link was sent.',
            'email' => $user->email,
            'token' => $reset_token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'reset_token' => ['required'],
            'password' => ['required', 'confirmed'],
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {

            return response()->json([
                'message' => 'Invalid reset request.'
            ], 400);
        }

        $tokenValid = Hash::check(
            $request->reset_token,
            $reset->reset_token
        );

        if (!$tokenValid) {

            return response()->json([
                'message' => 'Invalid token.'
            ], 400);
        }

        if (now()->diffInMinutes($reset->created_at) > 60) {

            return response()->json([
                'message' => 'Token expired.'
            ], 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {

            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $admin->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successful.'
        ]);
    }

    public function changePassword(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Validate request data
        $request->validate([
            'old_password' => ['required', 'min:4'],
            'new_password' => [
                'required',
                'min:4',
                'confirmed',
            ],
        ]);

        // Check current password
        if (!Hash::check($request->old_password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // Prevent using same password again
        if (Hash::check($request->password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'New password cannot be the same as old password'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Optional: revoke all tokens
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully. Please login again.'
        ], 201);
    }

    public function addNewCategory(Request $request)
    {

        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        $new_category = Category::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name, '-'),
        ]);

        return response()->json([
            'message' => 'new categoy is added successfuly',
            'new category data:' => $new_category,
        ], 201);
    }

    public function deleteCategory(int $id)
    {

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'category not found !!!'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'the category is deleted successfuly '
        ], 200);
    }

    public function updateCategory(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'message' => 'category not found !!!'
            ], 404);
        }

        $category->update([
            'category_name' => $validated['category_name'],
            'category_slug' => Str::slug($validated['category_name'], '-'),
        ]);

        return response()->json([
            'message' => 'the category is updated successfuly'
        ], 200);
    }

    public function getAllCategories()
    {
        $categories = Category::all();

        if (!$categories) {
            return response()->json([
                'message' => 'Opss,There are no categories yet !!!'
            ], 404);
        }

        return response()->json([
            'all_sub_categories' => $categories
        ], 200);
    }

    public function getCategory(int $category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return response()->json([
                'meassage' => 'Ops, this Category not found ..'
            ], 404);
        }

        return response()->json([
            'catefory_info' => $category,
        ], 200);
    }

    public function getAllSubCategoriesBelongMainCategory(int $Category_id)
    {
        $category = Category::find($Category_id);

        if (!$category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ]);
        }

        $sub_categories = $category->subCategory;

        if ($sub_categories->isEmpty()) {
            return response()->json([
                'message' => "Oops, there are no subcategories for '{$category->category_name}' category."
            ], 404);
        }



        return response()->json([
            'subcategories' => $sub_categories,
        ], 200);
    }

    public function getSubCategoryBelongMainCategory(int $category_id, int $sub_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        return response()->json([
            'subCategory_info' => $subCategory,
        ], 200);
    }

    public function createNewSubCategory(Request $request, int $category_id)
    {

        $category = Category::find($category_id);

        if (!$category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ]);
        }

        $subCategory_validated = $request->validate([
            'sub_category_name' => 'required|unique:sub_categories',
        ]);

        $newSubCategory = $category->subCategory()->create([
            'sub_category_name' => $subCategory_validated['sub_category_name'],
            'sub_category_slug' => Str::slug($subCategory_validated['sub_category_name'], '-')
        ]);

        return response()->json([
            'message' => 'newSubCategory is successfuly created.',
            'newSubCategory' => $newSubCategory
        ], 201);
    }

    public function updateSubCategory(Request $request, int $category_id, int $sub_category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (!$subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $validated = $request->validate([
            'sub_category_name' => [
                'required',
                Rule::unique('sub_categories', 'sub_category_name')->ignore($subCategory->id),
            ],
        ]);

        $subCategory->update([
            'sub_category_name' => $validated['sub_category_name'],
            'sub_category_slug' => Str::slug($validated['sub_category_name'], '-'),
        ]);

        return response()->json([
            'message' => 'subCategory is updated successfuly.',
            'updatedSubCategory' => $subCategory,
        ], 200);
    }

    public function deleteSubCategory(int $category_id, int $sub_category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (!$subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $subCategory->delete();

        return response()->json([
            'message' => 'subCategory is deleted successfuly.'
        ], 200);
    }

    public function getAllChilldCategoriesBelongsSubCategory(int $category_id, int $sub_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $childCategories = $subCategory->childCategories;

        if ($childCategories->isEmpty()) {
            return response()->json([
                'message' => "Oops, there are no child categories for '{$subCategory->sub_category_name}' subcategory."
            ], 404);
        }

        return response()->json([
            'childCategories' => $childCategories,
        ], 200);
    }

    public function getChildCategoryBelongsSubCategory(int $category_id, int $sub_category_id, int $child_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $childCategory = $subCategory->childCategories()->find($child_category_id);

        if (! $childCategory) {
            return response()->json([
                'message' => 'Opss, this ChildCategory not Found in this SubCategory'
            ], 404);
        }

        return response()->json([
            'childCategory' => $childCategory,
        ], 200);
    }

    public function addNewChilldCategory(Request $request, int $category_id, int $sub_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|unique:child_categories,name',
        ]);

        $childCategory = $subCategory->childCategories()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '-'),
            'category_id' => $category->id,
        ]);

        return response()->json([
            'message' => 'childCategory is successfuly created.',
            'childCategory' => $childCategory,
        ], 201);
    }

    public function updateChilldCategory(Request $request, int $category_id, int $sub_category_id, int $child_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $childCategory = $subCategory->childCategories()->find($child_category_id);

        if (! $childCategory) {
            return response()->json([
                'message' => 'Opss, this ChildCategory not Found in this SubCategory'
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('child_categories', 'name')->ignore($childCategory->id),
            ],
        ]);

        $childCategory->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '-'),
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
        ]);

        return response()->json([
            'message' => 'childCategory is updated successfuly.',
            'childCategory' => $childCategory->fresh(),
        ], 200);
    }

    public function deleteChilldCategory(int $category_id, int $sub_category_id, int $child_category_id)
    {
        $category = Category::find($category_id);

        if (! $category) {
            return response()->json([
                'message' => 'Opss, this Category not Found'
            ], 404);
        }

        $subCategory = $category->subCategory()->find($sub_category_id);

        if (! $subCategory) {
            return response()->json([
                'message' => 'Opss, this SubCategory not Found in this Category'
            ], 404);
        }

        $childCategory = $subCategory->childCategories()->find($child_category_id);

        if (! $childCategory) {
            return response()->json([
                'message' => 'Opss, this ChildCategory not Found in this SubCategory'
            ], 404);
        }

        $childCategory->delete();

        return response()->json([
            'message' => 'childCategory is deleted successfuly.'
        ], 200);
    }

    public function getAllBrands()
    {
        $brands = Brand::all();

        if ($brands->isEmpty()) {
            return response()->json([
                "message" => "Ops, there are no Brands yet"
            ]);
        }
        return response()->json([
            "brands" => $brands
        ], 200);
    }

    // PickupPoint CRUD
    public function getAllPickupPoint()
    {
        $points = PickupPoint::all();

        if ($points->isEmpty()) {
            return response()->json([
                'message' => 'No pickup points found.'
            ], 404);
        }

        return response()->json([
            'pickup_points' => $points
        ], 200);
    }

    public function getPickupPoint(int $id)
    {
        $point = PickupPoint::find($id);

        if (! $point) {
            return response()->json([
                'message' => 'Pickup point not found.'
            ], 404);
        }

        return response()->json([
            'pickup_point' => $point
        ], 200);
    }

    public function addNewPickuPoint(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'phone_two' => 'nullable|string',
        ]);

        $point = PickupPoint::create($validated);

        return response()->json([
            'message' => 'Pickup point created successfully.',
            'pickup_point' => $point,
        ], 201);
    }

    public function updatePickupPoint(Request $request, int $id)
    {
        $point = PickupPoint::find($id);

        if (! $point) {
            return response()->json([
                'message' => 'Pickup point not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string',
            'phone_two' => 'nullable|string',
        ]);

        $point->update($validated);

        return response()->json([
            'message' => 'Pickup point updated successfully.',
            'pickup_point' => $point->fresh(),
        ], 200);
    }

    public function deletePickupPoint(int $id)
    {
        $point = PickupPoint::find($id);

        if (! $point) {
            return response()->json([
                'message' => 'Pickup point not found.'
            ], 404);
        }

        $point->delete();

        return response()->json([
            'message' => 'Pickup point deleted successfully.'
        ], 200);
    }

    public function getBrand(int $brand_id)
    {
        $brand = Brand::find($brand_id);

        if (!$brand) {
            return response()->json([
                'message' => 'this brand not found.',
            ], 404);
        }

        return response()->json([
            'brand' => $brand
        ], 200);
    }

    public function addNewBrand(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:brands,name',
            'logo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $path = $request->file('logo')->store('brands', 'public');
        $logoUrl = Storage::url($path);

        $new_brand = Brand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '-'),
            'logo' => $logoUrl,
        ]);

        if (! $new_brand) {
            return response()->json([
                'message' => 'Ops, Brand did not create.'
            ], 500);
        }

        return response()->json([
            'message' => 'Brand successfully created.',
            'brand' => $new_brand,
        ], 201);
    }

    public function updateBrand(Request $request, int $id)
    {
        $brand = Brand::find($id);

        if (! $brand) {
            return response()->json([
                'message' => 'Brand not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                Rule::unique('brands', 'name')->ignore($brand->id),
            ],
            'logo' => 'sometimes|image|mimes:jpg,jpeg,png,gif',
        ]);

        $data = [];

        if (array_key_exists('name', $validated)) {
            $data['name'] = $validated['name'];
            $data['slug'] = Str::slug($validated['name'], '-');
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo'] = Storage::url($path);

            // attempt to delete old logo from storage
            if ($brand->logo) {
                $oldPath = parse_url($brand->logo, PHP_URL_PATH) ?: $brand->logo;
                $oldPath = preg_replace('#^/storage/#', '', $oldPath);
                Storage::disk('public')->delete($oldPath);
            }
        }

        $brand->update($data);

        return response()->json([
            'message' => 'Brand updated successfully.',
            'brand' => $brand->fresh(),
        ], 200);
    }

    public function deleteBrand(int $id)
    {
        $brand = Brand::find($id);

        if (! $brand) {
            return response()->json([
                'message' => 'Brand not found.'
            ], 404);
        }

        if ($brand->logo) {
            $oldPath = parse_url($brand->logo, PHP_URL_PATH) ?: $brand->logo;
            $oldPath = preg_replace('#^/storage/#', '', $oldPath);
            Storage::disk('public')->delete($oldPath);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully.'
        ], 200);
    }

    public function getAllSeo()
    {
        return response()->json(SEO::all());
    }

    public function addNewSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string',
            'meta_author' => 'nullable|string',
            'meta_tag' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'google_verification' => 'nullable|string',
            'google_analytics' => 'nullable|string',
            'alexa_verification' => 'nullable|string',
            'google_adsense' => 'nullable|string',
        ]);

        $seo = SEO::create($validated);

        return response()->json([
            'message' => 'Seo is created successfully',
            'seo_info' => $seo,

        ], 201);
    }

    public function getSeo(int $seo_id)
    {
        $seo = SEO::find($seo_id);

        if (! $seo) {
            return response()->json(['message' => 'Ops,this Seo Not found'], 404);
        }
        return response()->json([
            'seo_info' => $seo,
        ], 200);
    }

    public function updateSeo(Request $request, int  $seo_id)
    {
        $seo = SEO::find($seo_id);

        if (!$seo) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'meta_title' => 'nullable|string',
            'meta_author' => 'nullable|string',
            'meta_tag' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'google_verification' => 'nullable|string',
            'google_analytics' => 'nullable|string',
            'alexa_verification' => 'nullable|string',
            'google_adsense' => 'nullable|string',
        ]);

        $seo->update($validated);



        return response()->json([
            'message' => 'Seo is updated successfully',
        ], 201);
    }

    public function deleteSeo(int $seo_id)
    {
        $seo = SEO::find($seo_id);

        if (! $seo) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $seo->delete();

        return response()->json(['message' => 'seo is successfully Deleted']);
    }

    public function updateSmtp(Request $request, int $smtp_id)
    {
        $smtp = Smtp::find($smtp_id);

        if (! $smtp) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'mailer' => 'nullable|string',
            'host' => 'nullable|string',
            'port' => 'nullable|string',
            'user_name' => 'nullable|string',
            'password' => 'nullable|string',
            'mail_from_address' => 'nullable|string',
            'mail_from_name' => 'nullable|string',
        ]);

        $smtp->update($validated);

        return response()->json([
            'message' => 'SMTP is updated successfully',
            'smtp_ifo' => $smtp->fresh(),
        ], 201);
    }

    public function getAllSettings()
    {
        $settings = Setting::all();

        if ($settings->isEmpty()) {
            return response()->json([
                'message' => 'There are no settings yet.'
            ], 404);
        }

        return response()->json([
            'settings' => $settings,
        ], 200);
    }

    public function updateSetting(Request $request, int $setting_id)
    {
        $setting = Setting::find($setting_id);

        if (! $setting) {
            return response()->json([
                'message' => 'Setting not found.'
            ], 404);
        }

        $validated = $request->validate([
            'currency' => 'nullable|string',
            'phone_one' => 'nullable|string',
            'phone_two' => 'nullable|string',
            'main_email' => 'nullable|string|email',
            'support_email' => 'nullable|string|email',
            'logo' => 'nullable|string',
            'favicon' => 'nullable|string',
            'address' => 'nullable|string',
            'facebook' => 'nullable|string',
            'twitter' => 'nullable|string',
            'instagram' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'youtube' => 'nullable|string',
        ]);

        $setting->update($validated);

        return response()->json([
            'message' => 'Setting is updated successfully',
            'setting_info' => $setting->fresh(),
        ], 200);
    }

    public function gettAllWharehouses()
    {
        $wharehouses = Wharehouse::all();

        if ($wharehouses->isEmpty()) {
            return response()->json([
                'message' => 'There are no wharehouses yet.'
            ], 404);
        }

        return response()->json([
            'wharehouses' => $wharehouses,
        ], 200);
    }

    public function getWharehoyuse(int $wharehouse_id)
    {
        $wharehouse = Wharehouse::find($wharehouse_id);

        if (! $wharehouse) {
            return response()->json([
                'message' => 'Wharehouse not found.'
            ], 404);
        }

        return response()->json([
            'wharehouse' => $wharehouse,
        ], 200);
    }

    public function createWharhouse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $wharehouse = Wharehouse::create($validated);

        return response()->json([
            'message' => 'Wharehouse is created successfully',
            'wharehouse' => $wharehouse,
        ], 201);
    }

    public function updateWharehouse(Request $request, int $wharehouse_id)
    {
        $wharehouse = Wharehouse::find($wharehouse_id);

        if (! $wharehouse) {
            return response()->json([
                'message' => 'Wharehouse not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $wharehouse->update($validated);

        return response()->json([
            'message' => 'Wharehouse is updated successfully',
            'wharehouse' => $wharehouse->fresh(),
        ], 200);
    }

    public function deleteWharehouse(int $wharehouse_id)
    {
        $wharehouse = Wharehouse::find($wharehouse_id);

        if (! $wharehouse) {
            return response()->json([
                'message' => 'Wharehouse not found.'
            ], 404);
        }

        $wharehouse->delete();

        return response()->json([
            'message' => 'Wharehouse deleted successfully'
        ], 200);
    }

    public function getAllCoupon()
    {
        $coupons = Coupon::all();

        if ($coupons->isEmpty()) {
            return response()->json([
                'message' => 'There are no coupons yet.'
            ], 404);
        }

        return response()->json([
            'coupons' => $coupons,
        ], 200);
    }

    public function getCoupon(int $coupon_id)
    {
        $coupon = Coupon::find($coupon_id);

        if (! $coupon) {
            return response()->json([
                'message' => 'Coupon not found.'
            ], 404);
        }

        return response()->json([
            'coupon' => $coupon,
        ], 200);
    }

    public function addNewCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string|unique:coupons,coupon_code|max:255',
            'valid_date' => 'nullable|string',
            'type' => 'nullable|integer',
            'coupon_amount' => 'required|integer',
            'status' => 'nullable|string',
        ]);

        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Coupon is created successfully',
            'coupon' => $coupon,
        ], 201);
    }

    public function updateCoupon(Request $request, int $coupon_id)
    {
        $coupon = Coupon::find($coupon_id);

        if (! $coupon) {
            return response()->json([
                'message' => 'Coupon not found.'
            ], 404);
        }

        $validated = $request->validate([
            'coupon_code' => 'sometimes|required|string|max:255|unique:coupons,coupon_code,' . $coupon->id,
            'valid_date' => 'nullable|string',
            'type' => 'nullable|integer',
            'coupon_amount' => 'sometimes|required|integer',
            'status' => 'nullable|string',
        ]);

        $coupon->update($validated);

        return response()->json([
            'message' => 'Coupon is updated successfully',
            'coupon' => $coupon->fresh(),
        ], 200);
    }

    public function deleteCoupon(int $coupon_id)
    {
        $coupon = Coupon::find($coupon_id);

        if (! $coupon) {
            return response()->json([
                'message' => 'Coupon not found.'
            ], 404);
        }

        $coupon->delete();

        return response()->json([
            'message' => 'Coupon deleted successfully'
        ], 200);
    }

    public function getAllPages()
    {
        $pages = Page::all();

        if ($pages->isEmpty()) {
            return response()->json([
                'message' => 'There are no more pages'
            ], 404);
        }
        return response()->json([
            'pages' => $pages,
        ], 200);
    }

    public function getPage(int $page_id)
    {
        $page = Page::find($page_id);

        if (! $page) {
            return response()->json([
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'page_info' => $page,
        ], 200);
    }

    public function addNewPage(Request $request)
    {
        $validated = $request->validate([
            'page_name' => ['required', 'string'],
            'page_title' => ['required', 'string'],
            'page_description' => ['required', 'string'],
            'page_position' => ['nullable', 'string'],
        ]);

        $page = Page::create([
            'page_name' => $validated['page_name'],
            'page_title' => $validated['page_title'],
            'page_slug' => Str::slug($validated['page_name'], '-'),
            'page_description' => $validated['page_description'],
            'page_position' => $validated['page_position'] ?? null,
        ]);

        return response()->json([
            'message' => 'Page is created successfully',
            'page_info' => $page,
        ], 201);
    }

    public function updatePage(Request $request, int $page_id)
    {
        $page = Page::find($page_id);

        if (! $page) {
            return response()->json([
                'message' => 'Page not found'
            ], 404);
        }

        $validated = $request->validate([
            'page_name' => ['required', 'string'],
            'page_title' => ['required', 'string'],
            'page_description' => ['required', 'string'],
            'page_position' => ['nullable', 'string'],
        ]);

        $page->update([
            'page_name' => $validated['page_name'],
            'page_title' => $validated['page_title'],
            'page_slug' => Str::slug($validated['page_name'], '-'),
            'page_description' => $validated['page_description'],
            'page_position' => $validated['page_position'] ?? null,
        ]);

        return response()->json([
            'message' => 'Page updated successfully',
            'page' => $page->fresh(),
        ], 200);
    }

    public function deletePage(int $page_id)
    {
        $page = Page::find($page_id);

        if (! $page) {
            return response()->json([
                'message' => 'Page not found'
            ], 404);
        }

        $page->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ], 200);
    }

    public function getAllProducts()
    {
        $products = Product::all();


        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'Ops , There is no any Product'
            ], 404);
        }

        return response()->json([
            'products' => $products
        ], 200);
    }

    public function addNewProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:sub_categories,id',
            'childcategory_id' => 'nullable|integer|exists:child_categories,id',
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'flash_deal_id' => 'nullable|integer',
            'pickup_point_id' => 'required|integer|exists:pickup_point,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code',
            'unit' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'video' => [
                'required',
                'file',
                'mimes:mp4',
                'max:51200',
            ],
            'purchase_price' => 'nullable|string|max:255',
            'selling_price' => 'nullable|string|max:255',
            'discount_price' => 'nullable|string|max:255',
            'stock_quantity' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'images' => ['required', 'array'],

            'images.*' => [

                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'featured' => 'nullable|integer',
            'today_deal' => 'nullable|integer',
            'status' => 'nullable|integer',
            'cash_on_delivery' => 'nullable|integer',
            'product_slider'=>'nullable|integer'
        ]);

        $subCategory = SubCategory::where('id', $validated['subcategory_id'])
            ->where('category_id', $validated['category_id'])
            ->first();

        if (! $subCategory) {
            return response()->json([
                'message' => 'SubCategory does not belong to selected Category'
            ], 422);
        }

        if (! empty($validated['childcategory_id'])) {
            $childCategory = ChildCategory::where('id', $validated['childcategory_id'])
                ->where('sub_category_id', $validated['subcategory_id'])
                ->where('category_id', $validated['category_id'])
                ->first();

            if (! $childCategory) {
                return response()->json([
                    'message' => 'ChildCategory does not belong to selected SubCategory'
                ], 422);
            }
        }

        $thumbnailUrl = null;
        $imagePaths = [];
        $videoPath = null;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('products/photo', 'public');
            $thumbnailUrl = Storage::url($path);
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('products/gallery', 'public');

                $imagePaths[] = Storage::url($path);
            }
        }

        if ($request->hasFile('video')) {

            $path = $request->file('video')
                ->store('products/videos', 'public');
            $videoPath = Storage::url($path);
        }


        $product = Product::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'childcategory_id' => $request->childcategory_id,
            'warehouse_id' => $request->warehouse_id,
            'brand_id' => $request->brand_id,
            'admin_id' => $request->user()->id,
            'flash_deal_id' => $request->admin_id,
            'pickup_point_id' => $request->pickup_point_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'code' => $request->code,
            'unit' => $request->unit,
            'color' => $request->color,
            'size' => $request->size,
            'tags' => $request->tags,
            'video' => $videoPath,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'description' => $request->description,
            'thumbnail' => $thumbnailUrl,
            'images' => $imagePaths,
            'featured' => $request->featured,
            'today_deal' => $request->today_deal,
            'status' => $request->status,
            'cash_on_delivery' => $request->cash_on_delivery,
            'product_slider' => $request->product_slider,
        ]);

        return response()->json([
            'message' => 'Product is created successfully',
            'product' => $product,
        ], 201);
    }

    public function updateProduct(Request $request, int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'subcategory_id' => 'required|integer|exists:sub_categories,id',
            'childcategory_id' => 'nullable|integer|exists:child_categories,id',
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'flash_deal_id' => 'nullable|integer',
            'pickup_point_id' => 'nullable|integer|exists:pickup_point,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code',
            'unit' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'video' => [
                'nullable',
                'file',
                'mimes:mp4',
                'max:51200',
            ],
            'purchase_price' => 'nullable|string|max:255',
            'selling_price' => 'nullable|string|max:255',
            'discount_price' => 'nullable|string|max:255',
            'stock_quantity' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'images' => ['nullable', 'array'],

            'images.*' => [

                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'featured' => 'nullable|integer',
            'today_deal' => 'nullable|integer',
            'status' => 'nullable|integer',
            'cash_on_delivery' => 'nullable|integer',
            'product_slider' => 'nullable|integer',
        ]);
        $productData = $validated;
        $productData['slug'] = Str::slug($validated['name'], '-');



        $targetCategoryId = $validated['category_id'] ?? $product->category_id;
        $targetSubCategoryId = $validated['subcategory_id'] ?? $product->subcategory_id;
        $targetChildCategoryId = array_key_exists('childcategory_id', $validated)
            ? $validated['childcategory_id']
            : $product->childcategory_id;

        $subCategory = SubCategory::where('id', $targetSubCategoryId)
            ->where('category_id', $targetCategoryId)
            ->first();

        if (! $subCategory) {
            return response()->json([
                'message' => 'SubCategory does not belong to selected Category'
            ], 422);
        }

        if (! empty($targetChildCategoryId)) {
            $childCategory = ChildCategory::where('id', $targetChildCategoryId)
                ->where('sub_category_id', $targetSubCategoryId)
                ->where('category_id', $targetCategoryId)
                ->first();

            if (! $childCategory) {
                return response()->json([
                    'message' => 'ChildCategory does not belong to selected SubCategory'
                ], 422);
            }
        }

        if ($request->hasFile('thumbnail')) {
            $this->deleteStoredPublicFile($product->thumbnail);
            $thumbnailPath = $request->file('thumbnail')->store('products/photo', 'public');
            $productData['thumbnail'] = Storage::url($thumbnailPath);
        }

        if ($request->hasFile('video')) {
            $this->deleteStoredPublicFile($product->video);
            $videoPath = $request->file('video')->store('products/videos', 'public');
            $productData['video'] = Storage::url($videoPath);
        }

        if ($request->hasFile('images')) {
            foreach ((array) $product->images as $image) {
                $this->deleteStoredPublicFile($image);
            }

            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $imagePaths[] = Storage::url($path);
            }

            $productData['images'] = $imagePaths;
        }

        $product->update($productData);

        return response()->json([
            'message' => 'Product is updated successfully',
            'product' => $product->fresh(),
        ], 200);
    }
    public function deleteProduct(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $this->deleteStoredPublicFile($product->thumbnail);
        $this->deleteStoredPublicFile($product->video);

        foreach ((array) $product->images as $image) {
            $this->deleteStoredPublicFile($image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }

    public function activeStatus(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'status' => 1,
        ]);

        return response()->json([
            'message' => 'Product status activated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function disActiveStatus(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'status' => 0,
        ]);

        return response()->json([
            'message' => 'Product status deactivated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function activeFeatured(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'featured' => 1,
        ]);

        return response()->json([
            'message' => 'Product featured status activated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function disActiveFeatured(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'featured' => 0,
        ]);

        return response()->json([
            'message' => 'Product featured status deactivated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function activeProductSlider(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'product_slider' => 1,
        ]);

        return response()->json([
            'message' => 'Product slider activated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function disActiveProductSlider(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'product_slider' => 0,
        ]);

        return response()->json([
            'message' => 'Product slider deactivated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function activeTodayDeal(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'today_deal' => 1,
        ]);

        return response()->json([
            'message' => 'Product today deal activated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    public function disActiveTodayDeal(int $product_id)
    {
        $product = Product::find($product_id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update([
            'today_deal' => 0,
        ]);

        return response()->json([
            'message' => 'Product today deal deactivated successfully',
            'product' => $product->fresh(),
        ], 200);
    }

    private function deleteStoredPublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $relativePath = parse_url($path, PHP_URL_PATH) ?: $path;
        $relativePath = preg_replace('#^/storage/#', '', $relativePath);

        if ($relativePath) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    public function productsFilter(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'nullable|string',
            'brand_name' => 'nullable|string',
            'warehouse_name' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $query = Product::query();

        if (! empty($validated['category_name'])) {
            $category = Category::where('category_name', $validated['category_name'])->first();
            if (! $category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            $query->where('category_id', $category->id);
        }

        if (! empty($validated['brand_name'])) {
            $brand = Brand::where('name', $validated['brand_name'])->first();
            if (! $brand) {
                return response()->json(['message' => 'Brand not found'], 404);
            }
            $query->where('brand_id', $brand->id);
        }

        if (! empty($validated['warehouse_name'])) {
            $wharehouse = Wharehouse::where('name', $validated['warehouse_name'])->first();
            if (! $wharehouse) {
                return response()->json(['message' => 'Wharehouse not found'], 404);
            }
            $query->where('warehouse_id', $wharehouse->id);
        }
        if (! empty($validated['warehouse_name'])) {

            $query->where('status', $request->status);
        }

        $filtering_products = $query->get();

        if ($filtering_products->isEmpty()) {
            return response()->json(['message' => 'No products found for provided filters'], 404);
        }

        return response()->json([
            'filtering_products' => $filtering_products,
        ], 200);
    }
}
