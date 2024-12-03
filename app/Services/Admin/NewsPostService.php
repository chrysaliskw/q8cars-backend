<?php

namespace App\Services\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\News;
use Illuminate\Support\Facades\DB;
use App\Services\UniqueCodeService;
use Illuminate\Support\Facades\Storage;
use App\Jobs\Admin\News\NotifyOnPublishJob;

final class NewsPostService
{
    public $post;
    private $data;
    private $publishDate;
    private $expiryDate;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->publishDate = Carbon::now();
        $this->expiryDate = Carbon::parse($this->data['expiry_date'])->addHours(23)->addMinutes(59)->addSeconds(59);
    }

    /**
     * @return NewsPost
     * 
     * @throws Exception
     */
    public function handleCreate()
    {
        try{
            $this->createNewsPost();

            return $this->post;
        }
        catch (Exception $ex) {
            throw $ex;
        }
    }

     /**
     * @return NewsPost
     * 
     * @throws Exception
     */
    public function handleUpdate()
    {
       
        try
        {
            $this->updateNewsPost();
          
            return $this->post;
        }
        catch (Exception $ex) {
         
            throw $ex;
        }
    }

    private function createNewsPost()
    {
       
        $this->post = new News();
        //  dd($this->data['content']);
        $oldImageName = $this->post->image;
        $oldAuthorImageName = $this->post->media_logo;
        // $this->post->code = (new UniqueCodeService())->generate();
        $this->post->title = htmlspecialchars_decode($this->data['title']);
        $this->post->content = htmlspecialchars_decode($this->data['content']);
        $this->post->content  = strip_tags($this->post->content);
        $this->post->content= str_replace("&nbsp", " " ,$this->post->content );
        $this->post->html_content ='<p style="text-align:left;">'.$this->data['content'].'</p>';
        $this->post->media_name =  $this->data['media_name'];
        $this->post->brand_id = $this->data['brand_id'];
        $this->post->car_id = $this->data['car_id'];
        $this->post->car_version_id = $this->data['car_version_id'] ??  null;
        $this->post->posted_time = $this->publishDate;
        $this->post->expiry_date = $this->expiryDate;
        $this->post->image = $this->moveUploadedNewsImage();
        $this->post->media_logo =  $this->moveUploadedAuthorImage();
        $this->post->read_time = $this->data['read_time'];
       // $this->post->sort_order = $this->data['sort_order'];
        $this->post->status = $this->data['status'];
        // $this->post->language = $this->data['language'];
        if (! empty($this->data['type'])) {
            $this->post->type = $this->data['type'];
        }
        // if (! empty($this->data['youtube_video_link'])) {
        //     $this->post->video_link = $this->data['youtube_video_link'];
        //     $this->post->type = News::VIDEO_STORY;
        // }
        // else {
        //     $this->post->type = News::NOT_VIDEO_STORY;
        // }

        $this->post->created_at = Carbon::now();
        $this->post->saveOrFail();
        // if(isset($this->data['notification'])){
        //     if($this->data['notification']== 1){
        //         NotifyOnPublishJob::dispatch($this->post,NewsPost::POSTED_BY_MEFRIEND)->afterResponse();
        //     }   
        // }
        if($oldImageName != $this->post->image)
        {
            Storage::delete(News::FILE_DIR . DIRECTORY_SEPARATOR . $oldImageName);
           // Storage::delete('zebra' . DIRECTORY_SEPARATOR . NewsPost::DOC_DIR . DIRECTORY_SEPARATOR . 'thumbnail' , DIRECTORY_SEPARATOR . $oldImageName);
        }
        if($oldAuthorImageName != $this->post->author_image)
        {
            Storage::delete(News::FILE_DIR . DIRECTORY_SEPARATOR . $oldAuthorImageName);
        }
      
    }

    private function updateNewsPost()
    {
        $oldImageName = $this->post->image;
        $oldAuthorImageName = $this->post->author_image;
        $this->post->fill($this->data);
        $this->post->title = htmlspecialchars_decode($this->data['title']);
        $this->post->content = htmlspecialchars_decode($this->data['content']);
        $this->post->content  = strip_tags($this->post->content);
        $this->post->content= str_replace("&nbsp;", " " ,$this->post->content );
        // $this->post->html_content='<p style="text-align:left;">'.$this->data['content'].'</p>';
        $this->post->html_content='<p style="text-align:left;">'.$this->data['content'].'</p>';
        $this->post->read_time = $this->data['read_time'];
        $this->post->is_trending = $this->data['is_trending'];

        if($this->post->image) {
            $this->post->image = $this->moveUploadedNewsImage();
        }
        if($this->post->media_logo) {
            $this->post->media_logo = $this->moveUploadedAuthorImage();
        }
              //  $this->post->sort_order = $this->data['sort_order'];
        $this->post->status = $this->data['status'];
      
        $this->post->expiry_date = $this->expiryDate;
        // if (! empty($this->data['type'])) {
        //     $this->post->type = $this->data['type'];
        // }
        // if (! empty($this->data['youtube_video_link'])) {
        //     $this->post->video_link = $this->data['youtube_video_link'];
        //     $this->post->type = NewsPost::VIDEO_STORY;
        // }
        // else {
        //     $this->post->type = NewsPost::NOT_VIDEO_STORY;
        // }
        $this->post->updated_at = Carbon::now();
        $this->post->saveOrFail();
        //TODO
        if(isset($this->data['notification'])){
            if($this->data['notification'] == 1){
                // NotifyOnPublishJob::dispatch($this->post,News::POSTED_BY_Q8CARS)->afterResponse();
            }   
        }
        if($oldImageName != $this->post->image)
        {
            Storage::delete(News::FILE_DIR . DIRECTORY_SEPARATOR . $oldImageName);
        }
        if($oldAuthorImageName != $this->post->media_logo)
        {
            Storage::delete(News::FILE_DIR . DIRECTORY_SEPARATOR . $oldAuthorImageName);
        }

    }

    private function moveUploadedNewsImage()
    {
        if (empty($this->data['image'])) {
            return $this->post->image;
        }
        compressAndResizeImage($this->data['image']->path(), $this->data['image']->path());
        $this->data['image']->store(News::FILE_DIR);
      
        return $this->data['image']->hashName();
    }
    
    private function moveUploadedAuthorImage()
    {
        if (empty($this->data['media_logo'])) {
            return $this->post->media_logo;
        }
        compressAndResizeImage($this->data['media_logo']->path(), $this->data['media_logo']->path());
        $this->data['media_logo']->store(News::FILE_DIR);

        return $this->data['media_logo']->hashName();
    }

  
}