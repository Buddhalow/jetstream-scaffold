<?php
namespace App\Http\DashboardModelControllers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use ReflectionClass;
use ReflectionMethod;

class DashboardModelController extends Controller
{
    public function getBelongsToFields($model) {
        return [];
    }
    public function deleteObject($id) {
        throw new Exception("Not implemented");
    }
    public function updateObject($id, $validator) {
        throw new Exception("Not implemented");
    }
    public function saveObject($validator) {
        throw new Exception("Not implemented");
    }
    public function getObject($id) {
        throw new Exception("Not implemented");
    }
    public function getObjects() {
        throw new Exception("Not implemented");
    }
    public function getPluralRoute() {
        return "objects";
    }
    public function getClassModel() {
        return "Model";
    }
    public function getModel() {
        return "";
    }
    public function getLowerCasePlural() {
        return "objects";
    }
    public function getSingularRoute() {
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
        return Inertia::render(
            $this->getVueModel() . '/Index', [
                $this->getLowerCasePlural() => $this->getObjects(),
                'fields' => $this->getFields(),
                'model' => $this->getLowerCaseSingular(),
                'plural' => $this->getPluralRoute()
            ]
        );
    }
    public function view($id)
    {
        return Inertia::render($this->getVueModel() . '/View', [
            $this->getLowerCasePlural() => $this->getObject($id),
            'fields' => $this->getFields(),
            'model' => $this->getLowerCaseSingular(),
            'plural' => $this->getPluralRoute()
        ]);
    }
    public function edit($id)
    {
        $proposals = array();
        $fields = $this->getFields();
        foreach($fields as $key => $field) {
            $proposals[$key] = [];
            if ($field['type'] === 'relation') {

                $proposals[$key] = $this->getBelongsToFields($field["model"]);
            }
        }
        return Inertia::render($this->getVueModel() . '/Edit', [
            $this->getSingularRoute() => $this->getObject($id),
            'fields' => $this->getFields(),
            'model' => $this->getLowerCaseSingular(),
            'plural' => $this->getPluralRoute(),
            'proposals' => $proposals
        ]);
    }
    public function store()
    {
        $result = $this->createObject(
            Request::validate(
                $this->getValidationFields()
            )
        );
        return Redirect::route($this->getLowerCasePlural());
    }
    public function update($id)
    {
        $this->updateObject(
            $id,
            Request::validate(
                $this->getValidationFields()
            )
        );
        return Redirect::route($this->getLowerCasePlural(), array('model' => $this->getModel(), 'id' => $id));
    }
    public function delete() {
        $this->deleteObject();
        return Redirect::route($this->getLowerCasePlural(), ['model' => $this->getModel()]);
    }
}
