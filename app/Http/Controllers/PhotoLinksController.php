<?php

namespace App\Http\Controllers;

use App\Models\PhotoLink;
use App\Models\PhotoType;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver; // 將 Driver 取別名，避免混淆

class PhotoLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($photo_type_id=null)
    {
        $photo_links = PhotoLink::orderBy('order_by','DESC')
            ->get();
        $photo_types = PhotoType::orderBy('order_by')->get();
        foreach($photo_types as $photo_type){
            $photo_type_array[$photo_type->id] = $photo_type->name;
        }
        $photo_type_array[0] = "不分類"; 

        $photo_link_data = [];
        foreach($photo_links as $photo_link){
            $type = ($photo_link->photo_type_id==null)?0:$photo_link->photo_type_id;
            $photo_link_data[$type][$photo_link->id]['id'] = $photo_link->id;
            $photo_link_data[$type][$photo_link->id]['name'] = $photo_link->name;
            $photo_link_data[$type][$photo_link->id]['url'] = $photo_link->url;
            $photo_link_data[$type][$photo_link->id]['image'] = $photo_link->image;
            $photo_link_data[$type][$photo_link->id]['order_by'] = $photo_link->order_by;
            $photo_link_data[$type][$photo_link->id]['user_id'] = $photo_link->user_id;
        }

        $photo_type = PhotoType::orderBy('order_by','DESC')->first();
        if(!empty($photo_type)){
            $new_order_by = $photo_type->order_by+1;
        }else{
            $new_order_by = 1;
        }

        $data = [
            'photo_link_data'=>$photo_link_data,
            'photo_type_array'=>$photo_type_array,
            'photo_types'=>$photo_types,
            'new_order_by'=>$new_order_by,
            'photo_type_id'=>$photo_type_id,
        ];
        return view('photo_links.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($photo_type_id=null)
    {
        $photo_types = PhotoType::orderBy('order_by')->get();
        
        $new_type_order_by = [];

        $photo_link = PhotoLink::where(function ($query) {
            $query->where('photo_type_id',null)->orWhere('photo_type_id','0');
            })->orderBy('order_by','DESC')->first();
        if(!empty($photo_link)){
            $new_link_order_by[0] = $photo_link->order_by+1;
        }else{
            $new_link_order_by[0] = 1;
        }    

        foreach($photo_types as $photo_type){
            $photo_link = PhotoLink::where('photo_type_id',$photo_type->id)->orderBy('order_by','DESC')->first();
            if(!empty($photo_link)){
                $new_link_order_by[$photo_type->id] = $photo_link->order_by+1;
            }else{
                $new_link_order_by[$photo_type->id] = 1;
            }
        }
    

        $data = [
            'photo_types'=>$photo_types,
            'new_link_order_by'=>$new_link_order_by,
            'photo_type_id'=>$photo_type_id,
        ];
        return view('photo_links.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_by'=>'required|numeric',
            'name' => 'required',
            'image' => 'required|mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['image'] = "image";
        $att['order_by'] = $request->input('order_by');
        $att['photo_type_id'] = $request->input('photo_type_id');
        $att['user_id'] = auth()->user()->id;

        $photo_link = PhotoLink::create($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';

        //處理檔案上傳
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];

            $image_name = $photo_link->id.'.'.$info['extension'];
            $image->storeAs($folder,$image_name);

            $att2['image'] = $image_name;
            $photo_link->update($att2);

            //Image::make(storage_path('app/'.$folder.'/'.$image_name))->heighten(500)
            //    ->save(storage_path('app/'.$folder.'/'.$image_name));

            // 1. 根據你印出來的方法，它是用 static::usingDriver() 來初始化
            $manager = ImageManager::usingDriver(new GdDriver());

            // 2. 你的清單裡沒有 read()，但有專門解讀路徑的 decodePath()！
            // 4.x 的這個版本是用 decodePath 來取代傳統的 read 讀取本地檔案
            $image = $manager->decodePath(storage_path('app/'.$folder.'/'.$image_name));

            // 3. 調整高度並儲存
            // 註：如果 $image 噴錯說沒有 heighten()，請改用 $image->scale(height: 500);
            $image->scale(height: 500); 
            $image->save(storage_path('app/'.$folder.'/'.$image_name));
        }

        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";



        
    }

    public function type_store(Request $request)
    {
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        
        PhotoType::create($att);

        return back();
    }

    public function type_update(Request $request, PhotoType $photo_type,$photo_types=null)
    {
        $att = $request->all();
        
        $photo_type->update($att);

        return back();
    }

    public function type_delete(PhotoType $photo_type)
    {
        if(auth()->user()->admin !=1 and $photo_type->user_id != auth()->user()->id){
            return back();
        }
        $att['photo_type_id'] = null;
        PhotoLink::where('photo_type_id',$photo_type->id)->update($att);
        $photo_type->delete();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($photo_type_id=null)
    {
        if($photo_type_id==null){
            $photo_links = PhotoLink::orderBy('created_at','DESC')->orderBy('order_by','DESC')
            ->paginate(24);
        }else{
            $photo_links = PhotoLink::where('photo_type_id',$photo_type_id)->orderBy('order_by','DESC')
            ->paginate(24);
        }
        $photo_types = PhotoType::orderBy('order_by')->get();
        $data = [
            'photo_types'=>$photo_types,
            'photo_type_id'=>$photo_type_id,
            'photo_links'=>$photo_links,
        ];
        return view('photo_links.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PhotoLink $photo_link)
    {
        $photo_types = PhotoType::orderBy('order_by')->get();

        $data = [
            'photo_types'=>$photo_types,
            'photo_link'=>$photo_link,
        ];
        return view('photo_links.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhotoLink $photo_link)
    {
        $request->validate([
            'order_by'=>'required|numeric',
            'name' => 'required',
            'image' => 'mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['order_by'] = $request->input('order_by');
        $att['photo_type_id'] = $request->input('photo_type_id');

        $photo_link->update($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';

        //處理檔案上傳
        if ($request->hasFile('image')) {
            //先刪之前的
            if(file_exists(storage_path('app/'.$folder.'/'.$photo_link->image))){
                unlink(storage_path('app/'.$folder.'/'.$photo_link->image));
            }
            

            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];

            $image_name = $photo_link->id.'.'.$info['extension'];
            $image->storeAs($folder,$image_name);

            $att2['image'] = $image_name;
            $photo_link->update($att2);

            //Image::make(storage_path('app/'.$folder.'/'.$image_name))->heighten(500)
            //    ->save(storage_path('app/'.$folder.'/'.$image_name));

            // 1. 根據你印出來的方法，它是用 static::usingDriver() 來初始化
            $manager = ImageManager::usingDriver(new GdDriver());

            // 2. 你的清單裡沒有 read()，但有專門解讀路徑的 decodePath()！
            // 4.x 的這個版本是用 decodePath 來取代傳統的 read 讀取本地檔案
            $image = $manager->decodePath(storage_path('app/'.$folder.'/'.$image_name));

            // 3. 調整高度並儲存
            // 註：如果 $image 噴錯說沒有 heighten()，請改用 $image->scale(height: 500);
            $image->scale(height: 500); 
            $image->save(storage_path('app/'.$folder.'/'.$image_name));       


        }

        $u = route('photo_links.index',$photo_link->photo_type_id);
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhotoLink $photo_link)
    {
        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';
        if(file_exists(storage_path('app/'.$folder.'/'.$photo_link->image))){
            unlink(storage_path('app/'.$folder.'/'.$photo_link->image));
        }
        
        $photo_link->delete();
        return redirect()->route('photo_links.index');
    }
}
