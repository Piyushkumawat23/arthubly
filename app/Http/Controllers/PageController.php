<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Contact;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    // ==========================================
    // ADMIN TEMPLATE & WIDGET VIEWS (Admin Only)
    // ==========================================
    public function theme() { return view('admin.pages.generate.theme'); }
    public function smallBox() { return view('admin.pages.widgets.small-box'); }
    public function infoBox() { return view('admin.pages.widgets.info-box'); }
    public function cards() { return view('admin.pages.widgets.cards'); }

    public function unfixedSidebar() { return view('admin.pages.layout.unfixed-sidebar'); }
    public function fixedSidebar() { return view('admin.pages.layout.fixed-sidebar'); }
    public function customArea() { return view('admin.pages.layout.custom-area'); }
    public function sidebarMini() { return view('admin.pages.layout.sidebar-mini'); }
    public function collapsedSidebar() { return view('admin.pages.layout.collapsed-sidebar'); }
    public function logoSwitch() { return view('admin.pages.layout.logo-switch'); }
    public function layoutRtl() { return view('admin.pages.layout.layout-rtl'); }

    public function generalUI() { return view('admin.pages.UI.general'); }
    public function icons() { return view('admin.pages.UI.icons'); }
    public function timeline() { return view('admin.pages.UI.timeline'); }

    public function generalForms() { return view('admin.pages.forms.general'); }
    public function simpleTables() { return view('admin.pages.tables.simple'); }

    public function login() { return view('admin.pages.examples.login'); }
    public function register() { return view('admin.pages.examples.register'); }
    public function loginV2() { return view('admin.pages.examples.login-v2'); }
    public function registerV2() { return view('admin.pages.examples.register-v2'); }
    public function lockscreen() { return view('admin.pages.examples.lockscreen'); }

    public function docsIntroduction() { return view('admin.pages.docs.introduction'); }
    public function docsColorMode() { return view('admin.pages.docs.color-mode'); }
    public function mainHeader() { return view('admin.pages.docs.components.main-header'); }
    public function mainSidebar() { return view('admin.pages.docs.components.main-sidebar'); }
    public function treeView() { return view('admin.pages.docs.javascript.treeview'); }
    public function browserSupport() { return view('admin.pages.docs.browser-support'); }
    public function howToContribute() { return view('admin.pages.docs.how-to-contribute'); }
    public function faq() { return view('admin.pages.docs.faq'); }
    public function license() { return view('admin.pages.docs.license'); }

    // ==========================================
    // CONTACT LEADS VIEW (Admin/Subadmin Only)
    // ==========================================
    public function contact() { 
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to customer enquiries.');
        }
        $contacts = Contact::paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }
  
    // ==========================================
    // CMS PAGES LIST
    // ==========================================
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access to CMS Pages.');
        }
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }
        return view('admin.pages.create');
    }

    // ==========================================
    // STORE CUSTOM DYNAMIC PAGE
    // ==========================================
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'status' => 'required|in:active,inactive',
            'content' => 'required|string',
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'status' => $request->status,
            'content' => $request->content,
        ]);

        $fileStatus = "Without physical blade file";

        // Check if the 'create_file' checkbox is checked
        if ($request->has('create_file') && $request->create_file == 1) {
            $viewContent = "<h1>{$request->title}</h1><p>{$request->content}</p>";
            $viewFilePath = resource_path("views/user/{$page->slug}.blade.php");

            File::put($viewFilePath, $viewContent);
            $fileStatus = "With physical view component: views/user/{$page->slug}.blade.php";
        }

        // 🟢 CREATE ACTIVITY LOG FOR CMS PAGE CREATION
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create',
            'module' => 'CMS Pages',
            'description' => "Created new web page: '{$page->title}' ($fileStatus)",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    // ==========================================
    // UPDATE CMS PAGE LOGIC
    // ==========================================
    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $page = Page::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'status' => 'required|in:active,inactive',
            'content' => 'required|string',
        ]);

        // 🟢 CAPTURE CHANGES FOR AUDIT TRAIL
        $page->fill([
            'title' => $request->title,
            'slug' => $request->slug,
            'status' => $request->status,
            'content' => $request->content,
        ]);

        $changes = $page->getDirty();
        $oldData = [];
        $newData = [];

        if (!empty($changes)) {
            foreach ($changes as $key => $value) {
                $oldData[$key] = $page->getOriginal($key);
                $newData[$key] = $value;
            }
        }

        $page->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Update',
                'module' => 'CMS Pages',
                'description' => json_encode(['page_title' => $page->title, 'old' => $oldData, 'new' => $newData]),
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('pages.edit', $page->id)->with('success', 'Page updated successfully.');
    }
    
    // ==========================================
    // DESTROY CMS PAGE & COMPONENTS LOGIC
    // ==========================================
    public function destroy(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'subadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $page = Page::findOrFail($id);
        $fileDeletedNotice = "";
    
        // Check if the delete_file checkbox is checked
        if ($request->has('delete_file') && $request->delete_file == 1) {
            // FIX: Yahan template system me physical error tha ($page->title save tha upar $page->slug se save ho rha h)
            $viewFilePath = resource_path("views/user/{$page->slug}.blade.php");
    
            if (File::exists($viewFilePath)) {
                File::delete($viewFilePath);
                $fileDeletedNotice = " and its physical blade view file was also dropped";
            }
        }
    
        // 🟢 CREATE DELETE ACTIVITY LOG BEFORE DROPPING RECORDS
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete',
            'module' => 'CMS Pages',
            'description' => "Deleted dynamic page record: '{$page->title}'$fileDeletedNotice",
            'ip_address' => request()->ip(),
        ]);

        $page->delete();
    
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}