<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Outer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers_count = Chat::all()->count();
        return view('admin.index', ['subscribers_count' => $subscribers_count]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function subscribers()
    {
        $subscribers = Chat::all();
//        return response()->json($subscribers);
        return view('admin.subscribers', ['subscribers' => $subscribers]);
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    public function series()
    {
        $outers = Outer::with(['inners' => function($query) {
            $query->select('seria');
        }])->get();
        return view('admin.series', ['outers' => $outers]);
    }

    public function statistics()
    {
        $statisticsData = Chat::all()
            ->map(function ($item){
                return [
                    'id' => $item->id,
                    'first_name' => $item->first_name,
                    'created_at' => $item->created_at->format('d M Y')
                ];
            })->countBy('created_at');
        return view('admin.statistics', compact('statisticsData'));
    }

    public function statisticsData()
    {
        return Chat::all()
            ->map(function ($item){
                return [
                    'id' => $item->id,
                    'first_name' => $item->first_name,
                    'created_at' => $item->created_at->format('d M Y')
                ];
            })->countBy('created_at');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
