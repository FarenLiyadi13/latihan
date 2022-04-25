<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// library tambahann
use App\Http\Requests\Dashboard\MyOrder\UpdateMyorderRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Service;
use App\Models\AdvantageService;
use App\Models\AdvantageUser;
use App\Models\Tagline;
use App\Models\ThumbnailService;

class MyOrderController extends Controller
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

        $data = Order::where('freelancer_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('pages.dashboard.order.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $service = Service::where('id', $order['service_id'])->first();
        $advantage_service = AdvantageService::where('id', $order['service_id'])->first();
        $advantage_user = AdvantageUser::where('id', $order['service_id'])->first();
        $thumbnail_service = ThumbnailService::where('id', $order['service_id'])->first();
        $tagline = Tagline::where('id', $order['service_id'])->first();
        return view('pages.dashboard.order.detail', compact('service', 'advantage_service', 'tagline', 'thumbnail_service', 'advantage_user', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('pages.dashboard.order.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMyorderRequest $request, Order $order)
    {
        $data = $request->all();

        if (isset($data['file'])) {
            $data['file'] = $request->file('file')->store(
                'asset/order/attachment',
                'public'
            );
        }

        toast()->success('Your order has been updated !');
        return redirect()->route('member.order.index');
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

    public function accepted($id)
    {
        $order = Order::where('id', $id)->first();

        $order->order_status_id = 2;
        $order->save();
        toast()->success('Order has been accepted !');
        return back();
    }
    public function rejected($id)
    {
        $order = Order::where('id', $id)->first();
        $order->order_status_id = 3;
        $order->save();
        toast()->success('Order has been rejected !');
        return back();
    }
}
