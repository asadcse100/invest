<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Boolean;
use App\Models\Language;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class LanguageController extends Controller
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Schema::hasTable('languages')) {
            return back()->with(['notice' => __("Sorry, we are unable to find language system in application.")]);
        }

        $langs = Language::paginate(15);

        return view('admin.language.index', compact('langs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function actions(Request $request, $action = null)
    {
        if (!in_array($action, ['new', 'edit'])) {
            throw ValidationException::withMessages(['error' => __("An error occurred. Please try again.")]);
        }

        $id = !empty($lang_id) ? $lang_id : $request->get('uid');
        $lang = Language::find(get_hash($id));

        if ($action == 'edit' && blank($lang)) {
            throw ValidationException::withMessages(['error' => __("An error occurred. Please try again.")]);
        }

        $update = ($action == 'edit') ? true : false;

        return response()->json(view('admin.language.form', ['lang' => $lang, 'update' => $update])->render());
    }

    /**
     * Store a newly created language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createLang(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:60',
            'code' => 'required|string|min:2|max:10|unique:languages',
            'label' => 'required|string|max:60',
            'short' => 'nullable|string|min:2|max:10',
            'text_direction' => 'string|in:ltr,rtl'
        ], [
            'name.required' => __("The language name is required to add."),
            'code.required' => __("The language code is required to add."),
            'code.unique' => __("The language code should be unique."),
            'label.required' => __("The label of language is required."),
        ]);

        $lang = array_map('strip_tags_map', $validate);

        $lang['code'] = strtolower($lang['code']);
        $lang['rtl'] = $lang['text_direction'] == 'rtl' ? Boolean::YES : Boolean::NO;

        Language::create($lang);

        if($lang) {
            return response()->json(['reload' => true, 'msg' => __("New language successfully added.")]);
        } else {
            return response()->json(['type' => 'warning', 'msg' => __("An error occurred. Please try again.")]);
        }
    }

    /**
     * Update the specified lanaguage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lang  $lang
     * @return \Illuminate\Http\Response
     */
    public function updateLang(Request $request, $lang_id = null)
    {
        $validate = $request->validate([
            'label' => 'required|string|max:60',
            'short' => 'nullable|string|min:2|max:10',
            'status' => 'nullable',
            'text_direction' => 'string|in:ltr,rtl'
        ], [
            'label.required' => __("The label of language is required."),
        ]);

        $data = array_map('strip_tags_map', $validate);
        $data['status'] = isset($data['status']) ? Boolean::YES : Boolean::NO;
        $data['rtl'] = $data['text_direction'] == 'rtl' ? Boolean::YES : Boolean::NO;

        $id = !empty($lang_id) ? $lang_id : $request->get('uid');
        $lang = Language::find(get_hash($id));

        if (!blank($lang)) {

            if ($data['status'] == Boolean::NO) {
                $actived = Language::where('status', Boolean::YES)->count();

                if ($actived <= 1) {
                    throw ValidationException::withMessages([ __("Sorry, you can not inactive this language. Application required one active language.") ]);
                }

                if ( in_array($lang->id, [gss('language_default_system'), gss('language_default_public')]) ) {
                    throw ValidationException::withMessages([__("Sorry, you can not inactive the language as this language used as default language.")]);
                }
            }

            $lang->update($data);
            
            return response()->json(['reload' => true, 'success' => __("The language successfully updated.")]);
        } else {
            return response()->json([
                'type' => 'warning', 'msg' => __("An error occurred. Please try again.")
            ]);
        }
    }

    /**
     * Remove the specified language.
     *
     * @param  \App\Models\Lang  $lang
     * @return \Illuminate\Http\Response
     */
    public function deleteLang(Request $request, $lang_id = null)
    {
        $id = !empty($lang_id) ? $lang_id : $request->get('uid');
        $lang = Language::find(get_hash($id));

        if (!blank($lang)) {
            if ($lang->code == 'en') {
                throw ValidationException::withMessages([ __("Sorry, you can not delete this system language.") ]);
            }

            $total = Language::count();
            if ($total <= 1) {
                throw ValidationException::withMessages([ __("Sorry, you can not delete the language. At-least one language is require to run application.") ]);
            }

            $actived = Language::where('status', Boolean::YES)->count();
            if ($actived <= 1 && $lang->status == Boolean::YES) {
                throw ValidationException::withMessages([ __("Sorry, you can not delete the language. At-least one active language is require.") ]);
            }

            if ( in_array($lang->id, [gss('language_default_system'), gss('language_default_public')]) ) {
                throw ValidationException::withMessages([__("Sorry, you can not delete the language as this language used as default language.")]);
            }

            $lang->delete();
            return response()->json([
                'msg' => __("The language successfully deleted."), 'reload' => true,
            ]);

        } else {
            return response()->json([
                'type' => 'warning', 'msg' => __("An error occurred. Please try again.")
            ]);
        }
    }

    private function syncTranslations($languages)
    {
        try {
            $this->wrapInTransaction(function($languages) {
                foreach ($languages as $language) {
                    $langPath = resource_path('lang/'.$language->code.'.json');
                    if ($this->filesystem->exists($langPath)) {
                        $data = $this->filesystem->get($langPath);
                        if (is_json($data)) {
                            $language->translations = $data;
                            $language->save();
                        } else {
                            throw new \Exception("Invalid json file for: ".$language->code);
                        }
                    }
                }
            }, $languages);

            return response()->json([ 'msg' => __("The language files successfully synced and data updated into database.") ]);
            
        } catch (\Exception $e) {
            save_error_log($e);
            return response()->json([
                'type' => 'warning', 'msg' => __("An error occurred. Please try again.")
            ]);
        }

    }

    private function regenerateTranslations($languages)
    {
        if (!$this->filesystem->isWritable(resource_path('/lang'))) {
            return response()->json([
                'type' => 'warning', 'msg' => __("The directory :path is not writable.", ['path' => resource_path('/lang')])
            ]);
        }

        try {
            $this->wrapInTransaction(function($languages) {
                foreach ($languages as $language) {
                    $langPath = resource_path('lang/'.$language->code.'.json');
                    if (!blank($language->translations)) {
                        $this->filesystem->replace($langPath, $language->translations);
                    }
                }
            }, $languages);

            return response()->json([
                'msg' => __("The language files successfully regenerated."), 'reload' => false,
            ]);
        } catch (\Exception $e) {
            save_error_log($e, $e->getTrace());
            return response()->json([
                'type' => 'warning', 'msg' => __("An error occurred. Please try again.")
            ]);
        }
    }

    public function processLang($action)
    {
        $languages = Language::active()->get();

        if (blank($languages)) {
            return response()->json([
                'type' => 'warning', 'msg' => __("No active language found.")
            ]);
        }

        if ($action == 'sync') {
            return $this->syncTranslations($languages);
        } elseif ($action == 'regenerate') {
            return $this->regenerateTranslations($languages);
        }

        return response()->json([
            'type' => 'warning', 'msg' => __("An error occurred. Please try again.")
        ]);
    }
}
