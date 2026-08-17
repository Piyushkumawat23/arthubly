<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Tree ko flat list me convert karne wala helper function
    private function flattenMenuNode($menu, &$list)
    {
        $list[] = $menu; // Pehle parent ko list me daalo
        
        // Fir uske bacchon ko (Recursive)
        foreach ($menu->children as $child) {
            $this->flattenMenuNode($child, $list);
        }
    }

    // ==========================================
    // INDEX VIEW
    // ==========================================
    public function index()
    {
        // 🟢 SECURITY CHECK: Sirf Admin/Subadmin hi Menu Builder access kar sakte hain
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to Menu Builder.');
        }

        // 1. Drag & Drop ke liye Tree (Nested Structure)
        $menuTree = Menu::whereNull('parent_id')
                        ->with('children.children') // 3 level tak load
                        ->orderBy('order')
                        ->get();

        // 2. Table View ke liye Sorted List (Hierarchy Wise)
        $menusArray = [];
        foreach ($menuTree as $menu) {
            $this->flattenMenuNode($menu, $menusArray);
        }
        
        $menus = collect($menusArray);
        $pages = Page::where('status', 'active')->get();
        $existingSlugs = Menu::pluck('slug')->toArray();

        return view('admin.menus.index', compact('menus', 'menuTree', 'pages', 'existingSlugs'));
    }

    // ==========================================
    // ADD PAGES TO MENU LOGIC
    // ==========================================
    public function addPagesToMenu(Request $request)
    {
        // 🟢 SECURITY CHECK
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'page_ids' => 'required|array',
            'page_ids.*' => 'exists:pages,id'
        ]);

        $pageIds = $request->input('page_ids');
        $maxOrder = Menu::max('order') ?? 0;
        $addedPagesTitles = [];

        foreach($pageIds as $pageId) {
            $page = Page::find($pageId);
            $maxOrder++;

            Menu::create([
                'title'     => $page->title,
                'slug'      => $page->slug,
                'url'       => '/page/' . $page->slug, 
                'parent_id' => null,
                'order'     => $maxOrder,
                'status'    => 1
            ]);

            $addedPagesTitles[] = $page->title;
        }

        // 🟢 CREATE ACTIVITY LOG FOR BULK ADD PAGES
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Bulk Add Pages',
            'module' => 'Menu Builder',
            'description' => "Added pages to menu: " . implode(', ', $addedPagesTitles),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Selected pages added to menu.');
    }
    
    public function createMenu()
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        $parentMenus = $this->getMenuTree();
        return view('admin.menus.createMenu', compact('parentMenus'));
    }

    // ==========================================
    // STORE CUSTOM MENU LOGIC
    // ==========================================
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'status'    => 'boolean',
        ]);

        $maxOrder = Menu::max('order') ?? 0;
        $newOrder = $maxOrder + 1;

        $menu = Menu::create([
            'menu_category_id' => null,
            'title'     => $validated['title'],
            'slug'      => $validated['slug'],
            'url'       => null,
            'parent_id' => $validated['parent_id'] ?: null,
            'order'     => $newOrder,
            'status'    => $validated['status'] ?? 1,
        ]);

        // 🟢 CREATE ACTIVITY LOG FOR NEW MENU
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'Menu Builder',
            'description' => "Created custom menu item: {$menu->title}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu added successfully.');
    }

    public function edit($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        $menu = Menu::findOrFail($id);
        $parentMenus = $this->getMenuTree($menu->id);
        return view('admin.menus.editMenu', compact('menu', 'parentMenus'));
    }
        
    // ==========================================
    // UPDATE MENU LOGIC
    // ==========================================
    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $menu = Menu::findOrFail($id);
        
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'required|string|max:255',
            'url'       => 'nullable|string|max:255',
            'order'     => 'required|integer', 
            'status'    => 'required|boolean',
            'parent_id' => 'nullable|exists:menus,id',
        ]);
        
        if ($validated['parent_id'] == $menu->id) {
            return back()->with('error', 'A menu cannot be its own parent.');
        }

        // 🟢 CAPTURE CHANGES FOR ACTIVITY LOG
        $menu->fill([
            'title'     => $validated['title'],
            'slug'      => $validated['slug'],
            'url'       => $validated['url'],
            'order'     => $validated['order'],
            'status'    => $validated['status'],
            'parent_id' => $validated['parent_id'] ?: null,
        ]);

        $changes = $menu->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $menu->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $menu->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'Menu Builder',
                'description' => json_encode(['menu_title' => $menu->title, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    // ==========================================
    // DESTROY MENU LOGIC
    // ==========================================
    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $menu = Menu::findOrFail($id);

        // 🟢 CREATE DELETE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'Menu Builder',
            'description' => "Deleted menu item: {$menu->title} and all its sub-menus",
            'ip_address' => request()->ip(),
        ]);

        Menu::where('parent_id', $id)->delete();
        $menu->delete(); 
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted.');
    }

    // ==========================================
    // REORDER (DRAG & DROP AJAX) LOGIC
    // ==========================================
    public function reorder(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.parent_id' => 'nullable|exists:menus,id',
            'menus.*.order' => 'required|integer',
        ]);
    
        $menus = $request->input('menus');
    
        foreach ($menus as $item) {
            Menu::where('id', $item['id'])->update([
                'parent_id' => $item['parent_id'],
                'order'     => $item['order']
            ]);
        }

        // 🟢 CREATE REORDER ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Reorder',
            'module' => 'Menu Builder',
            'description' => "Reordered structure or hierarchy via Drag & Drop",
            'ip_address' => request()->ip(),
        ]);
    
        return response()->json(['success' => true]);
    }
    
    private function getMenuTree($excludeId = null)
    {
        $allMenus = Menu::orderBy('order')->get();
        return $this->buildTree($allMenus, $excludeId);
    }

    private function buildTree($menus, $excludeId, $parentId = null, $prefix = '')
    {
        $list = [];
        foreach ($menus as $menu) {
            if ($excludeId && $menu->id == $excludeId) { continue; }
            if ($menu->parent_id == $parentId) {
                $list[] = [ 'id' => $menu->id, 'title' => $prefix . ' ' . $menu->title ];
                $children = $this->buildTree($menus, $excludeId, $menu->id, $prefix . '--');
                $list = array_merge($list, $children);
            }
        }
        return $list;
    }
}