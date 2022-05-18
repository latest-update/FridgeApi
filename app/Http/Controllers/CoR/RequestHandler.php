<?php


namespace App\Http\Controllers\CoR;


use App\Http\Controllers\Custom\ShortResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface RequestHandler
{
    public function link(RequestHandler $next);
    public function handle(Request $request);
    public function next(Request $request);
}
