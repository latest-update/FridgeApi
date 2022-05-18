<?php


namespace App\Http\Controllers\Factory\Relations;


use Illuminate\Database\Eloquent\Model;

class OneToMany
{
    private Model $firstModel;
    private Model $secondModel;
    private string $relation;

    public function __construct(Model $destination, Model $attach, string $relation)
    {
        $this->firstModel = $destination;
        $this->secondModel = $attach;
        $this->relation = $relation;
    }

    public function attach()
    {
        $relation = $this->relation;
        return $this->firstModel->$relation()->associate($this->secondModel);
    }
}
