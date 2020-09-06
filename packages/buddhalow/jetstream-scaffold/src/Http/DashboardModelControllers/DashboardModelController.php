<?php
namespace App\Http\DashboardModelControllers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use ReflectionMethod;

class DashboardModelController extends Controller
{
    public function getModel() {
        return "";
    }
    public function getListRoute() {
        return "objects";
    }
    public function getSingleRoute() {
        return "object";
    }
    public function getVueModel() {
        return "Object";
    }
    public function getFields() {
        return array();
    }
    public function getValidationFields() {
        $validationFields = array();
        foreach($this->getFields() as $key => $field) {
            $validationFields[$key] = [$field['type']];
        }
        return $validationFields;
    }
    public function index()
    {
        $allMethod =  new ReflectionMethod("App\Models\\" . $this->getModel(), "all");
        return Inertia::render($this->getVueModel() . '/Index', [
            $this->getListRoute() => $allMethod->invoke(NULL),
            'fields' => $this->getFields(),
            'model' => $this->getModel()
        ]);
    }
    public function view($id)
    {
        $getMethod =  new ReflectionMethod("App\Models\\" . $this->getModel(), "get");
        return Inertia::render($this->getVueModel() . '/View', [
            $this->getSingleRoute() =>$getMethod->invoke(NULL, $id),
            'fields' => $this->getFields(),
            'model' => $this->getModel()
        ]);
    }
    public function edit($id)
    {
        $getMethod =  new ReflectionMethod("App\Models\\" . $this->getModel(), "get");
        return Inertia::render($this->getVueModel() . '/Edit', [
            $this->getSingleRoute() => $getMethod->invoke(NULL, $id),
            'fields' => $this->getFields(),
            'model' => $this->getModel()
        ]);
    }
    public function store()
    {
        $createMethod =  new ReflectionMethod("App\Models\\" . $this->getModel() . "", "create");
        $result = $createMethod->invoke(
            Request::validate(
                $this->getFields()
            )
        );
        return Redirect::route($this->getListRoute());
    }
    public function update($id)
    {
        $createMethod =  new ReflectionMethod("App\Models\\" . $this->getModel(), "update");
        $result = $createMethod->invoke(
            $id,
            Request::validate(
                $this->getFields()
            )
        );
        return Redirect::route($this->getListRoute(), array('model' => $this->getModel(), 'id' => $id));
    }
    public function delete() {
        $deleteMethod =  new ReflectionMethod("App\Models\\" . $this->getModel(), "delete");
        return Redirect::route($this->getListRoute(), ['model' => $this->getModel()]);
    }
}
