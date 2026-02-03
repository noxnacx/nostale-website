<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // ใช้สำหรับยิง Webhook

class NewsController extends Controller
{
    // ดึงข่าวหน้าแรก
    public function index() {
        return News::where('is_published', true)->orderBy('created_at', 'desc')->take(5)->get();
    }

    // ดึงข่าวรายตัว
    public function show($id) {
        $news = News::find($id);
        if (!$news) return response()->json(['message' => 'News not found'], 404);
        return response()->json($news);
    }

    // [Admin] เพิ่มข่าว + แจ้งเตือน Discord
    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required',
            'summary' => 'nullable',
            'content' => 'required',
            'category' => 'required',
            'image_url' => 'nullable',
            'image_position_x' => 'nullable|integer|min:0|max:100', // รับค่าแกน X
            'image_position_y' => 'nullable|integer|min:0|max:100'  // รับค่าแกน Y
        ]);

        // ตั้งค่า Default ตรงกลาง (50%) ถ้าไม่ส่งมา
        if (!isset($data['image_position_x'])) $data['image_position_x'] = 50;
        if (!isset($data['image_position_y'])) $data['image_position_y'] = 50;

        if (empty($data['image_url'])) {
            $data['image_url'] = '/images/bg-1.png';
        }

        $news = News::create($data);

        // ✅ เปิดใช้งาน: ยิงเข้า Discord ทันทีที่สร้างเสร็จ
        $this->sendDiscordNotification($news);

        return $news;
    }

    // [Admin] อัปเดตข่าว
    public function update(Request $request, $id) {
        $news = News::find($id);
        if (!$news) return response()->json(['message' => 'ไม่พบข่าวนี้'], 404);

        $data = $request->validate([
            'title' => 'required',
            'summary' => 'nullable',
            'content' => 'required',
            'category' => 'required',
            'image_url' => 'nullable',
            'image_position_x' => 'nullable|integer|min:0|max:100',
            'image_position_y' => 'nullable|integer|min:0|max:100'
        ]);

        // ถ้าไม่มีค่าส่งมา ให้ใช้ค่าเดิมใน Database หรือ Default 50
        if (!isset($data['image_position_x'])) $data['image_position_x'] = $news->image_position_x ?? 50;
        if (!isset($data['image_position_y'])) $data['image_position_y'] = $news->image_position_y ?? 50;

        $news->update($data);
        return response()->json(['message' => 'อัปเดตข่าวสำเร็จ', 'news' => $news]);
    }

    // ลบข่าว
    public function destroy($id) {
        $news = News::find($id);
        if ($news) {
            $news->delete();
            return response()->json(['message' => 'ลบข่าวสำเร็จ']);
        }
        return response()->json(['message' => 'ไม่พบข่าวนี้'], 404);
    }

    // หน้ารวมข่าว (Archive)
    public function archive(Request $request) {
        $query = News::where('is_published', true)->orderBy('created_at', 'desc');
        if ($request->has('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }
        return $query->paginate(9);
    }

    // ==========================================
    // 🔔 ฟังก์ชันส่ง Discord Webhook (แยกตามหมวดหมู่)
    // ==========================================
    private function sendDiscordNotification($news) {
        $webhookUrl = match ($news->category) {
            'General'     => config('services.discord.general'),
            'Update'      => config('services.discord.update'),
            'Event'       => config('services.discord.event'),
            'Maintenance' => config('services.discord.maintenance'),
            default       => null,
        };

        // ถ้าไม่มี URL หรือไม่เจอหมวดหมู่ ให้จบการทำงาน (ไม่ Error)
        if (!$webhookUrl) return;

        // 2. กำหนดสีแถบข้าง (Decimal Color)
        $color = match ($news->category) {
            'General'     => 3447003,  // สีฟ้า (Blue)
            'Update'      => 3066993,  // สีเขียว (Green)
            'Event'       => 16776960, // สีเหลือง (Yellow)
            'Maintenance' => 15158332, // สีแดง (Red)
            default       => 0,
        };

        // 3. จัดการรูปภาพ (แปลง Relative URL เป็น Absolute URL)
        $imageUrl = $news->image_url;
        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
            $imageUrl = url($imageUrl);
        }

        // 4. สร้าง Payload ส่งไป Discord
        $payload = [
            'username' => 'Nostale Infinity Bot',
            'avatar_url' => 'https://i.imgur.com/example-logo.png', // เปลี่ยนเป็นรูปโลโก้เซิร์ฟคุณได้
            'embeds' => [
                [
                    'title' => "📢 " . $news->title,
                    'type' => 'rich',
                    'description' => $news->summary . "\n\n👉 **อ่านรายละเอียดเพิ่มเติม:**\n" . url('/news/' . $news->id),
                    'url' => url('/news/' . $news->id),
                    'color' => $color,
                    'image' => [
                        'url' => $imageUrl
                    ],
                    'footer' => [
                        'text' => 'Nostale Infinity News System • ' . date('d/m/Y H:i')
                    ]
                ]
            ]
        ];

        // 5. ยิง Request
        try {
            Http::post($webhookUrl, $payload);
        } catch (\Exception $e) {
            // กรณีส่งไม่ผ่าน (เช่น เน็ตหลุด) ปล่อยผ่าน
        }
    }
}
