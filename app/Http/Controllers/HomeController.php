<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folder;
use App\Image;
use Storage;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Folder List
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $parent_id = $request->parent_id ?? 0;
        $user = $request->user();
        $folders = $user->folders()
                ->where('parent_id', $parent_id)
                ->get();

        $images = $user->images()
                ->where('folder_id', $parent_id)
                ->get();
        return view('home', compact('folders', 'images'));
    }
    
    /**
     * Show Add New Form
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function addNew(Request $request)
    {
        $user = $request->user();
        $options_list = $user->folders()->select('id', 'folder_name')->get();
        return view('add', compact('options_list'));
    }
    
    /**
     * Add New Folder With Images
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function addFolder(Request $request)
    {
        $request->validate([
            'parent_id' => 'required',
            'folder_name' => 'nullable|max:255',
            'uploadedFiles' => 'required'
        ],[
            'parent_id.required' => 'Please select folder from dropdown.',
            'uploadedFiles.required' => 'Please add atleast one file.',
        ]);
        try {
            $user = $request->user();
            $data = $request->all();
            $data['user_id'] = $user->id;
            $data['folder_id']  = $data['parent_id'];
            if(isset($data['folder_name']) && $data['folder_name'] != ''){
                $folder = new Folder;
                $folder->folder_name = $data['folder_name'];
                $folder->parent_id = $data['parent_id'];
                $folder->user_id = $data['user_id'];
                $folder->save();
                $data['folder_id'] = $folder->id;
            }
            $total_files = count($data['uploadedFiles']);
            for($i=0;$i<$total_files;$i++) {
                $data['image_name'] = $data['uploadedFiles'][$i];
                $data['image_path'] = $data['filePath'][$i];
                $data['slug'] = $data['fileSlug'][$i];
                Image::create($data);
            }
            return back()->with('success', 'Added Image Successfully.');
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
    
    /**
     * Add new image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addImage(Request $request)
    {
        try {
            $user = $request->user();
            $file = $request->file('files')[0];
            if($file) {
                $file_name = uniqid().'.'.$file->getClientOriginalExtension();
                Storage::disk('public')->put($file_name, file_get_contents($file->getRealPath()), 'public');
                $path = Storage::url($file_name);
                $data = [
                    "deleteType" => "DELETE",
                    "deleteUrl" => route('delete-image', $file_name),
                    "name" => $file->getClientOriginalName(),
                    "size" => $file->getClientSize(),
                    "thumbnailUrl" => asset($path),
                    "type" => $file->getClientMimeType(),
                    "url" => asset($path),
                    "path" => $path,
                    "slug" => $file_name
                ];
                return response()->json(["files" => [$data]]);
            }
            return ["files" =>[]];
        } catch (Exception $ex) {
            return response()->json(['status' => 400]);
        }
    }
    
    /**
     * Delete image by name
     *
     * @param Request $request
     * @return array
     */
    public function deleteImage(Request $request)
    {
        $file_name = $request->image_name;
        Storage::disk('public')->delete($file_name);
        return [$file_name];
    }
}
