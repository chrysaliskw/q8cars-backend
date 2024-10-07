<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\DataGrids\Admin\NewsPostDataGrid;
use App\Services\Admin\NewsPostService;
use App\Http\Requests\Admin\NewsPostRequest;

class NewsPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $grid = new NewsPostDataGrid(request()->query());

        return view('admin.news.post.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.news.post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\NewsPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsPostRequest $request)
    {
        $data = $request->validated();
        try {
            $service = new NewsPostService($data);
            $post = $service->handleCreate();
        } catch (Exception $ex) {
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.news.show', $post)
            ->with('success', 'News created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        $viewData = [
            'Title' => $news->title,
            'Brand' => empty($news->brand) ? 'NIL' : $news->brand->name,
            'Car' => empty($news->car) ? 'NIL' : $news->car->model_name,
            'Car Version' => empty($news->carVersion) ? 'NIL' : $news->carVersion->varient_name,
            'Image' => url(file_asset('files-news', $news->image)),
            'Content' => $news->html_content,
            'Media Name' => $news->media_name,
            'Media Logo' => url(file_asset('files-news', $news->media_logo)),
            // 'Scheduled Date' => $news->scheduled_date ? dateTimeFormat($news->scheduled_date) : null,
            'Published Date' => dateTimeFormat($news->posted_time),

            'Expire On' => dateTimeFormat($news->expiry_date),
            //   'Sort Order' => $news->sort_order,
            'Read Time' => $news->read_time . ' Min read',
            'Status' => config('params.news.status')[$news->status],
            'Created At' => dateTimeFormat($news->created_at),
            'Updated At' => dateTimeFormat($news->updated_at),
        ];

        return view('admin.news.post.show', compact('news', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {

        $currentBrand = null;
        $currentBrand = json_encode([
            'id' => $news->brand_id,
            'text' => $news->brand->name
        ]);
        $currentCar = null;
        $currentCar = json_encode([
            'id' => $news->car_id,
            'text' => $news->car->model_name
        ]);
        $currentVersion = null;
        $currentVersion = json_encode([
            'id' => $news->car_version_id,
            'text' => $news->carVersion->varient_name
        ]);

        return view('admin.news.post.edit', compact('news', 'currentBrand', 'currentCar', 'currentVersion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(NewsPostRequest $request, News $news)
    {
        try {
            $service = new NewsPostService($request->validated());
            $service->post = $news;
            $news = $service->handleUpdate();
        } catch (Exception $ex) {
            logger($ex);
            throw ($ex);
            return back()->with('error', __('app.error'));
        }

        return redirect()->route('admin.news.show', $news)->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        try {
            $news->delete();
        } catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully!');
    }

    public function updatebanner(Request $request, News $news)
    {


        try {
            $id = $request->id;
            $carId = News::find($id)->car_id;
            $status = News::find($id)->status;

            if ($status == News::STATUS_ACTIVE) {
                News::where('car_id', $carId)
                    ->update(['show_in_detail_page' => News::NOT_DISPLAY_BANNER]);

                News::where('id', $id)->update(['show_in_detail_page' => News::DISPLAY_BANNER]);
            } else {
                $news->update($request->all());
                return redirect()->route('admin.news.index')->with('error', 'You cant select the banner which are are inactive!');
            }

            return redirect()->route('admin.news.index')->with('success', 'News updated successfully!');
        } catch (Exception $ex) {
            return back()->with('error', __('app.error') . ' ')->withInput();
        }
    }
}
