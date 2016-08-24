<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog;
use Illuminate\Support\Facades\Input;
use File;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Input::get('q')){
            $data['blogs'] = Blog::where('description', 'LIKE', '%'.Input::get('q').'%')->paginate(10);
        }else{
            $data['blogs'] = Blog::paginate(10);
        }        
        
        return view('blogs.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return view('blogs.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        if (Input::file('file')) {
            $path= $this->_uploadFile(Input::file('file'));
            $data = [
                'description' => Input::get('description'),
                'file' => $path
            ];

            
        }else{
            $data = [
                'description' => Input::get('description')
            ];
        }
        
        Blog::insert($data);

        return redirect('blogs');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit()
    {
        if(filter_input(INPUT_GET, 'id')){

            $data['blog'] = Blog::find(filter_input(INPUT_GET, 'id'));

            return view('blogs.edit', $data);
        }    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit()
    {
        if(Input::get('id')){
            
            $blog = Blog::find(Input::get('id'));

            if (Input::file('file')) {
                $path=$this->_uploadFile(Input::file('file'));
                if(!empty($blog->file)){
                    $filename_remove = explode('uploads/', $blog->file);
                    $this->_deleteFile(public_path('uploads/' . '/' . $filename_remove[1]));
                }
                

                $data = [
                    'description' => Input::get('description'),
                    'file' => $path
                ];

                
            }else{
                $data = [
                    'description' => Input::get('description')
                ];
            }

            $blog->update($data);
            return redirect('blogs');
        }
    }

    private function _uploadFile($file)
    {
        $destinationPath = 'uploads';
        $extension = $file->getClientOriginalExtension();
        $fileName = rand(11111,99999).'.'.$extension;

        if(in_array($extension, ['jpg', 'png', 'xls', 'xlsx', 'docx'])){
            
            if(in_array($extension, ['jpg', 'png'])){
                $url = url('/uploads/').'/'.$fileName;
            }else{
                if(in_array($extension, ['xls', 'xlsx'])){
                    $url = url('/uploads/').'/'.'xlsx.png';
                }elseif(in_array($extension, ['docx'])){
                    $url = url('/uploads/').'/'.'docx.jqg';
                }
            }
        }
        
        $file->move($destinationPath, $fileName);
        return $url;
    }

    private function _deleteFile($path)
    {
        if(file_exists($path))
        {
            unlink($path);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDelete()
    {
        if(filter_input(INPUT_GET, 'id')){
            $blog = Blog::find(filter_input(INPUT_GET, 'id'));
            $blog->delete();
            if(!empty( $blog->file)){
                $filename = explode('uploads/', $blog->file);
                $this->_deleteFile(public_path('uploads/' . '/' . $filename[1]));
                
            }
           
            return redirect('blogs');
        }
    }

    public function getAjaxblog()
    {
        if(Input::get('action')){
            
            $action = Input::get('action');
            if($action == 'get_create'){

                return view('blogs.new');

            }elseif($action == 'get_edit'){
                if(filter_input(INPUT_GET, 'id')){

                    $data['blog'] = Blog::find(filter_input(INPUT_GET, 'id'));

                    return view('blogs.edit', $data);
                }    
            }
        }
    }
}
