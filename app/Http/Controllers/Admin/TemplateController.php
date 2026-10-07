<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class TemplateController extends Controller
{
    // 1. Danh sách mẫu thiệp
    public function index()
    {
        $templates = Template::orderBy('sort_order', 'asc')->get();
        return view('admin.templates.index', compact('templates'));
    }

    // 2. Form thêm mẫu thiệp mới
    public function create()
    {
        $viewFiles = [];
        $path = resource_path('views/client/templates');
        
        // Lấy danh sách mẫu từ DB để đối chiếu tên thật (Hoàng Kim Sang Trọng,...)
        $dbTemplates = Template::pluck('name', 'view')->toArray();
        
        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $filename = str_replace('.blade.php', '', $file->getFilename());
                $viewPath = 'client.templates.' . $filename;
                
                // Nếu DB đã có tên đẹp thì lấy tên đẹp, chưa có thì mới dùng tên file
                $displayName = $dbTemplates[$viewPath] ?? $filename;

                $viewFiles[] = [
                    'path' => $viewPath,
                    'name' => $displayName
                ];
            }
        }

        return view('admin.templates.create', compact('viewFiles'));
    }

    // 3. Lưu mẫu thiệp mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'view' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/templates'), $filename);
            $thumbPath = 'uploads/templates/' . $filename;
        }

        Template::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'view' => $request->view,
            'thumbnail' => $thumbPath,
            'is_vip' => $request->has('is_vip') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->integer('sort_order', 0),
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Thêm mẫu thiệp mới thành công!');
    }

    // 4. Form chỉnh sửa mẫu
    public function edit($id)
    {
        $template = Template::findOrFail($id);
        
        // Truyền biến $fileName sang View để tránh lỗi 500
        $fileName = $template->view ? $template->view . '.blade.php' : 'N/A';

        return view('admin.templates.edit', compact('template', 'fileName'));
    }

    // 5. Cập nhật mẫu
    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/templates'), $filename);
            $template->thumbnail = 'uploads/templates/' . $filename;
        }

        $template->name = $request->name;
        if ($request->has('view')) {
            $template->view = $request->view;
        }
        $template->description = $request->description;
        $template->is_vip = $request->input('is_vip', 0);
        $template->is_active = $request->has('is_active') ? 1 : 0;
        $template->sort_order = $request->integer('sort_order', 0);
        $template->save();

        return redirect()->route('admin.templates.index')->with('success', 'Cập nhật mẫu thiệp thành công!');
    }

    // 6. Xóa mẫu thiệp
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Đã xóa mẫu thiệp!');
    }
}