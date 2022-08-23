<?php


namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Enums\Boolean;
use App\Enums\PageStatus;
use App\Filters\PageFilter;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\WrapInTransaction;
use Illuminate\Support\Arr;

class ManagePagesController extends Controller
{
    use WrapInTransaction;
    
    public function index(PageFilter $filter)
    {
        if (!Schema::hasColumn('pages', 'pid')) {
            return back()->with(['notice' => __("To continue, please install the update and migrate the application database.")]);
        }

        $query = Page::main()->filter($filter);
        $sortby = user_meta('page_sortpg', 'date');
        $ordered = user_meta('page_order', 'asc');

        switch ($sortby) {
            case 'date':
                $query->orderBy('created_at', $ordered);
                break;
            case 'visible':
                $query->orderBy('status', $ordered);
                break;
            default:
                $query->orderBy('name', $ordered);
        }

        $iState = (object) get_enums(Boolean::class, false);
        $iStatus = (object) get_enums(PageStatus::class, false);

        $pages = $query->paginate(user_meta('page_perpage', 10))->onEachSide(0);

        return view('admin.manage-content.pages.list', compact('pages', 'iState', 'iStatus'));
    }

    private function getPagesList()
    {
        if (!Schema::hasColumn('pages', 'pid')) {
            return back()->with(['notice' => __("To continue, please install the update and migrate the application database.")]);
        }

        return Page::main()->select('id', 'name', 'slug')->get();
    }

    public function create()
    {
        $pages = $this->getPagesList();
        $lName = 'English';

        $iState = (object) get_enums(Boolean::class, false);
        $iStatus = (object) get_enums(PageStatus::class, false);

        return view('admin.manage-content.pages.form', compact('pages', 'iState', 'iStatus', 'lName'));
    }

    public function edit(Request $request, $id)
    {
        $pages = $this->getPagesList();
        $pageDetails = Page::findOrFail($id);
        $langs = Language::select(['name', 'code'])->get();
        $lName = 'English';

        if (data_get($pageDetails, 'pid')) {
            return redirect()->route('admin.manage.pages.edit', ['id' => data_get($pageDetails, 'pid')]);
        }

        $slug = data_get($pageDetails, 'slug');

        if ($request->lang && ($request->lang != $pageDetails->lang)) {
            $lName = data_get($langs->where('code', data_get($request, 'lang'))->first(), 'name');
            if (empty($lName)) {
                return redirect()->route('admin.manage.pages.edit', ['id' => data_get($pageDetails, 'id')]);
            }
            $translatedPage = $pageDetails->translatedPage()->where('lang', $request->lang)->first();
            
            if (is_null($translatedPage)) {
                $slug = data_get($pageDetails, 'slug') . '-' . $request->lang;
            }
            
            $pageDetails = $translatedPage;
        }

        $iState = (object) get_enums(Boolean::class, false);
        $iStatus = (object) get_enums(PageStatus::class, false);

        return view('admin.manage-content.pages.form', compact('pages', 'pageDetails', 'iState', 'iStatus', 'langs', 'slug', 'lName'));
    }

    public function validatePageSlug(Request $request)
    {
        $slug = Str::slug($request->get('slug'), '-');
        $request->merge(['slug' => $slug]);

        try {
            $request->validate([
                'slug' => 'bail|required|string|unique:pages,slug,'.$request->get('id')
            ]);
            return response()->json(['error' => false, 'note' => __("Slug is valid to save.")]);
        } catch(ValidationException $e) {
            return response()->json(['error' => true, 'note' => __("Slug should be unique.")]);
        }

    }

    public function save(Request $request)
    {
        $slug = Str::slug(strip_tags($request->get('slug')), '-');
        $request->merge(['slug' => $slug]);

        $input = $request->validate([
            'name' => 'required|string|max:190',
            'slug' => 'required|string|max:190|unique:pages,slug,'.$request->get('id'),
            'menu_name' => 'required|string|max:190',
            'menu_link' => 'nullable|url',
            'title' => 'nullable|string|max:190',
            'subtitle' => 'nullable|string|max:190',
            'content' => 'required',
            'seo' => 'array',
            'params' => 'array',
            'public' => 'nullable',
            'pid' => 'nullable|integer',
            'lang' => 'nullable|string'
        ], [
            'slug.unique' => __("Please enter a unique and valid page slug.")
        ]);

        $input['status'] = ($request->get('status') == PageStatus::ACTIVE) ? PageStatus::ACTIVE : PageStatus::INACTIVE;
        $input['slug'] = Str::slug($input['slug'],'-');
        $input['name'] = strip_tags($input['name']);
        $input['menu_name'] = strip_tags($input['menu_name']);
        $input['title'] = strip_tags($input['title']);
        $input['subtitle'] = strip_tags($input['subtitle']);
        $input['seo'] = array_map('strip_tags_map', $input['seo']);
        $input['pid'] = $input['pid'] ?? 0;
        $input['lang'] = $input['lang'] ?? 'en';
        $input['public'] = ($request->get('public') == Boolean::NO) ? Boolean::NO : Boolean::YES;

        if ($id = $request->get('id')) {
            $this->wrapInTransaction(function ($id, $input) {
                $page = Page::where('id', $id)->first();

                if (!empty($page)) {
                    if (data_get($page, 'pid')) {
                        $input = Arr::except($input, ['slug', 'menu_link', 'public', 'status']);
                    }
                    $page->update($input);

                    if ($page->pid == 0) {
                        $page->translatedPage->map(function ($translatedPage) use ($page)
                        {
                            $translatedPage->slug = $page->slug . '-' . $translatedPage->lang;
                            $translatedPage->public = $page->public;
                            $translatedPage->menu_link = $page->menu_link;
                            $translatedPage->save();
                        });
                    }
                } else {
                    throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.')]);
                }
            }, $id, $input);
        } else {
            if ($input['pid']) {
                $page = Page::where('id', $input['pid'])->first();
                if (!empty($page)) {
                    $input['slug'] = data_get($page, 'slug') . '-' . $input['lang'];
                    $input['menu_link'] = $page->menu_link;
                    $input['public'] = $page->public;
                    $input['status'] = $page->status;
                }
            }
            $page = Page::create($input);
            $redirect = route('admin.manage.pages.edit', $page->id);

            if (data_get($page, 'pid')) {
                $redirect = route('admin.manage.pages.edit', ['id' => data_get($page, 'pid'), 'lang' => data_get($page, 'lang')]);
            }
            return response()->json([
                'msg' => __('The page has been successfully :status.', ['status' => __("created")]),
                'redirect' => $redirect
            ]);
        }

        return response()->json(['title' => __("Page Updated"), 'msg' => __('The page has been successfully :status.', ['status' => __("updated")])]);
    }

    public function deletePage(Request $request, $id=null) {
        $pid = ($id) ? $id : (int) $request->get('uid');
        if(empty($pid)) {
            throw ValidationException::withMessages(['invalid' => __('An error occurred. Please try again.')]);
        }
        
        $page = Page::where('id', $pid)->first();

        if(!blank($page)) {
            if ($page->trash == Boolean::YES) {
                return $this->wrapInTransaction(function ($request, $page)
                {
                    $reload = ($request->get('reload') == 'true') ? true : false;
                    $redirect = ($request->get('redirect') == 'true') ? route('admin.manage.pages') : false;

                    if (data_get($page, 'pid') == 0) {
                        Page::destroy($page->translatedPage->modelKeys());
                    }

                    Page::destroy($page->id);

                    return response()->json([
                        'title' => __("Page Deleted"), 'msg' => __('The page has been successfully :status.', ['status' => __("deleted")]), 'timeout' => 1200, 'reload' => $reload, 'redirect' => $redirect
                    ]);
                }, $request, $page);
            }
            throw ValidationException::withMessages(['delete' => __('Sorry, this page can not be deleted.')]);
        }
        throw ValidationException::withMessages(['delete' => __('Sorry, the page is not found or invalid id.')]);
    }
}
