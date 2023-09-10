<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct(Request $request)
    {
        try {
            $q = $request->input('q', '');

            $query = Product::orderBy('updated_at', 'desc');
            $query->where('product_name', 'like', '%' . $q . '%');
            $product = $query->get();
            return response()->json(['success' => true, 'data' => $product]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function addProduct(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'product_name' => 'required|string',
                'product_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'product_stock' => 'required|string',
                'product_detail' => 'nullable|string',
                'is_active' => 'nullable|string',
            ], [
                'product_name.required' => 'กรุณากรอกชื่อ',
                'product_stock.required' => 'กรุณากรอกจำนวนสต๊อก',
                'product_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'product_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'product_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $product = new Product();
            $product->product_name = $request->input('product_name');
            $product->product_img = $request->input('product_img');
            $product->product_stock = $request->input('product_stock');
            $product->product_detail = $request->input('product_detail');
            $product->is_active = $request->input('is_active');

            // Upload and save the product_img if provided
            if ($request->hasFile('product_img')) {
                $product->product_img = Helper::uploadFile($request, "product_img");
            }

            $product->save();
            return response()->json(['success' => true, 'data' => $product]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            // Validate the input
            $request->validate([
                'product_name' => 'required|string',
                'product_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'product_stock' => 'required|string',
                'product_detail' => 'nullable|string',
                'is_active' => 'nullable|string',
            ], [
                'product_name.required' => 'กรุณากรอกชื่อ',
                'product_stock.required' => 'กรุณากรอกจำนวนสต๊อก',
                'product_img.image' => 'รูปภาพต้องเป็นไฟล์รูปภาพ (jpeg, png, jpg)',
                'product_img.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
                'product_img.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048 กิโลไบต์',
            ]);

            $product->product_name = $request->input('product_name');
            $product->product_stock = $request->input('product_stock');
            $product->product_detail = $request->input('product_detail');
            $product->is_active = $request->input('is_active');

            // Upload and save the product_img if provided
            if ($request->hasFile('product_img')) {
                Helper::deleteFile($product->product_img);
                $product->product_img = Helper::uploadFile($request, "product_img");
            }

            // Remove file
            if ($request->input('set') === 'file_null') {
                Helper::deleteFile($product->product_img);
                $product->product_img = "";
            }

            $product->save();
            return response()->json(['success' => true, 'data' => $product]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['success' => true], 200);
        } catch (ValidationException $exception) {
            return response()->json(['success' => false, 'errors' => $exception->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
