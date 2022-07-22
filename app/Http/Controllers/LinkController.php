<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Services\LinkService;
use Illuminate\Contracts\View\View;

class LinkController extends Controller
{
    /**
     * @param LinkService $service
     * @return View
     */
    public function index(LinkService $service): View
    {
        return view('form');
    }

    /**
     * @param $shortcut
     * @param LinkService $service
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function show($shortcut, LinkService $service)
    {
        if ($url = $service->follow($shortcut)) {
            return redirect($url);
        }

        return response(view('errors.404'), 404);
    }

    /**
     * @param StoreLinkRequest $request
     * @param LinkService $service
     * @return View
     */
    public function store(StoreLinkRequest $request, LinkService $service): View
    {
        return view('form', [
            'link' => $service->shorten($request->origin, $request->expiration, $request->max_follows)
        ]);
    }
}
