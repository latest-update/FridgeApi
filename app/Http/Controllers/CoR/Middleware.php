<?php


namespace App\Http\Controllers\CoR;


use Illuminate\Http\Request;

abstract class Middleware implements RequestHandler
{
    protected ?RequestHandler $next;

    public function link(?RequestHandler $next): RequestHandler
    {
        $this->next = $next;
        return $this;
    }

    public function next(Request $request)
    {
        if (isset($this->next)) {
            return $this->next->handle($request);
        }
        return null;
    }
}
