<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    // ดึงรายการดาวน์โหลดทั้งหมด (เรียงตามประเภท)
    public function index() {
        return Download::orderBy('created_at', 'desc')->get();
    }

    // [Admin] เพิ่มลิงก์ใหม่
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'link' => 'required|url',
            'version' => 'nullable',
            'file_size' => 'nullable',
            'type' => 'required|in:Client,Patch,Tool'
        ]);

        return Download::create($data);
    }

    // [Admin] ลบลิงก์
    public function destroy($id) {
        $download = Download::find($id);
        if ($download) {
            $download->delete();
            return response()->json(['message' => 'Deleted']);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}
