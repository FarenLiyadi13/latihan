<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// package tambahan
use App\Http\Requests\Dashboard\Service\StoreServiceRequest;
use App\Http\Requests\Dashboard\Service\UpdateServiceRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Tagline;
use App\Models\AdvantageService;
use App\Models\AdvantageUser;

use App\Models\ThumbnailService;



class ServiceController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $service = Service::where('users_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        // dd($service);
        return view('pages.dashboard.service.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.dashboard.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->all();
        $data['users_id'] = Auth::user()->id;

        // add to service
        $service = Service::create($data);

        // add to advantage_service
        foreach ($data['advantage-service'] as $key => $value) {
            $advantage_service = new AdvantageService;
            $advantage_service->service_id = $service->id;
            $advantage_service->advantage = $value;
            $advantage_service->save();
        }
        // add to advantage_user
        foreach ($data['advantage-user'] as $key => $value) {
            $advantage_user = new AdvantageUser;
            $advantage_user->service_id = $service->id;
            $advantage_user->advantage = $value;
            $advantage_user->save();
        }
        // add to tagline
        foreach ($data['tagline'] as $key => $value) {
            $Tagline = new Tagline;
            $Tagline->service_id = $service->id;
            $Tagline->tagline = $value;
            $Tagline->save();
        }

        // add to thumbnail_service
        if ($request->hasFile('thumbnail')) {
            foreach ($request->file('thumbnail') as $file) {
                $path = $file->store(
                    'asset/service/thumbnail/',
                    'public'
                );
                $thumbnail_service = new ThumbnailService;
                $thumbnail_service->service_id = $service->id;
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        toast()->success('Your service has been created !');
        return redirect()->route('member.service.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        // dd($service);
        $advantage_service = AdvantageService::where('service_id', $service['id'])->get();
        $advantage_user = AdvantageUser::where('service_id', $service['id'])->get();
        $tagline = Tagline::where('service_id', $service['id'])->get();
        $thumbnail_service = ThumbnailService::where('service_id', $service['id'])->get();
        return view('pages.dashboard.service.edit', compact('service', 'advantage_service', 'advantage_user', 'tagline', 'thumbnail_service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->all();

        // update ke service
        $service->update($data);

        // update ke advanntage_service
        foreach ($data['advantage-service'] as $key => $value) {
            $advantage_service = AdvantageService::find($key);
            $advantage_service->advantage = $value;
            $advantage_service->save();
        }

        // add advantage_service
        if (isset($data['advantage-service'])) {
            foreach ($data['advantage-service'] as $key => $value) {
                $advantage_service = AdvantageService::find($key);
                $advantage_service->service_id = $service['id'];
                $advantage_service->advantage = $value;
                $advantage_service->save();
            }
        }
        // update ke advanntage_user
        foreach ($data['advantage-user'] as $key => $value) {
            $advantage_user = AdvantageUser::find($key);
            $advantage_user->advantage = $value;
            $advantage_user->save();
        }
        // add ke advantage_user
        if (isset($data['advantage-user'])) {
            foreach ($data['advantage-user'] as $key => $value) {
                $advantage_user = AdvantageUser::find($key);
                $advantage_user->service_id = $service['id'];
                $advantage_user->advantage = $value;
                $advantage_user->save();
            }
        }
        // update ke tagline
        foreach ($data['tagline'] as $key => $value) {
            $tagline = Tagline::find($key);
            $tagline->advantage = $value;
            $tagline->save();
        }
        // add new tagline
        if (isset($data['tagline'])) {
            foreach ($data['tagline'] as $key => $value) {
                $tagline = Tagline::find($key);
                $tagline->service_id = $service['id'];
                $tagline->advantage = $value;
                $tagline->save();
            }
        }

        // update thumbnail
        if ($request->hasFile('thumbnails')) {
            foreach ($request->file('thumbnails') as $key => $value) {
                // get old photo
                $get_photo = ThumbnailService::where('id', $key)->first();

                // store photo ke storage
                $path = $value->store(
                    'asset/service/thumbnail',
                    'public'
                );

                // update thumbnail
                $thumbnail_service = ThumbnailService::find($key);
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();

                // delete old photo
                $data = 'storage/' . $get_photo['thumbnails'];
                if (File::exists($data)) {
                    File::delete();
                } else {
                    File::delete('storage/app/public/' . $get_photo['thumbnails']);
                }
            }
        }

        // add photo baru
        if ($request->hasFile('thumbnail')) {
            foreach ($request->file('thumbnail') as $file) {
                // store file ke storage
                $path = $file->store(
                    'asset/service/thumbnail',
                    'public'
                );
                $thumbnail_service = new ThumbnailService;
                $thumbnail_service->service_id = $service['id'];
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        toast()->success('Your service has been updated');
        return redirect()->route('member.service.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
