<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Sir Ideas, C. A.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 **/

class AmResourceControl extends AmControl{

  public function __construct($data = null){
    parent::__construct($data);

    // Obtener la instancida del modelo
    $this->classModel = $model = AmORM::model($this->model);
    if($model)
      $this->table = $model::me();

    // Obtener las columnas si no se han indicado
    if(empty($this->columns) && $this->table){
      $columns = array_keys((array)$this->table->getFields());
      $this->columns = array_combine($columns, $columns);
    }

    $this->columnsNames = array_keys($this->columns);

    // Los campos a setear son los campos de la tabla - los ocultos
    if(empty($this->fields))
      $this->fields = array_diff($this->columnsNames, $this->hides);

    if(empty($this->formName))
      $this->formName = $this->classModel;
  }

  public function action_data(){

    // Obtener el listado de elementos
    $q = $this->table->all()
        ->setSelects(array_combine($this->columnsNames, $this->columnsNames));

    // Return el objeto para la tabla dinamica
    return dinamicTableServer($this->request, $q,
      array($this, 'callback_formatList'), false
    );

  }

  public function action_search(){
    $classModel = $this->classModel;

    // Obtener el texto a buscar
    $txtSearch = strtolower($this->request->search);

    // Obtener la posicion a cargar
    $offset = $this->request->offset * $this->request->limit;
    $q = $classModel::qSearch($txtSearch, $this->request->limit, $offset);
    if(!$q)
      $q = $classModel::q($this->request->limit, $offset);
    
    $this->callback_querySearchSetup($q);
    $haveNext = $q->haveNextPage();

    return array(
      'success'   => true,
      'items'     => $q->getResult('array', array($this, 'callback_formatSearch')),
      'haveNext'  => $haveNext,
    );

  }

  public function callback_querySearchSetup(AmQuery $q){}

  public function callback_newRecord(AmModel &$r){}

  public function callback_setValuesRecord(AmModel &$r){}

  // El formateador agregará una clase al registro dependiendo
  // del estado de la inscripcion
  public function callback_formatList($r){
    $r['cls'] = '';
    return $r;
  }

  // El formateador agregará una clase al registro dependiendo
  // del estado de la inscripcion
  public function callback_formatSearch($r){
    return $r;
  }

  // Procesamiento del formulario new
  public function post_new(){
    $r = new $this->classModel;
    $this->callback_newRecord($r);
    $this->r = $r;
    return $this->handleForm($this->r);
  }

  // Procesamiento del formulario edit
  public function post_edit($id){
    return $this->handleForm($this->r);
  }

  // Accion para eliminar un registro
  public function post_delete($id){
    return self::handleAction($this->r, $this->r->delete());
  }

  // Procesamiento del formulario edit
  public function post_cou(){
    $classModel = $this->classModel;
    $table = $classModel::me();
    $pkValues = AmObject::mask($this->request[$this->formName], $table->getPks());
    $this->r = $table->find($pkValues, $classModel);
    if(!$this->r){
      $r = new $classModel;
      $this->callback_newRecord($r);
      $this->r = $r;
    }
    return $this->handleForm($this->r);
  }

  // Obtener los datos de un registro
  public function post_detail($id){
    return array(
      'success' => true,
      'data' => $this->r->getValues($this->fields)
    );
  }

  // Procesamiento para guardar un formulario
  private function handleForm(AmModel $r){
    // Obtener los datos recibidos por post del formulario
    $data = $this->request[$this->formName];
    $r->setValues($data, $this->fields);
    $this->callback_setValuesRecord($r);
    return self::handleAction($r, $r->save());
  }

  private static function handleAction(AmModel $r, $actionResult = true){
    $ret = array('success' => $actionResult);
    if(!$actionResult)
      $ret['errors'] = $r->getErrors();
    return $ret;
  }

  public function filter_loadRecord($id){

    // Obtener le model con que trabaja el controlador
    $className = $this->classModel;
    $this->r = $className::me()->find($id, $className);

    // Si no se encontró el registro mostrar un mensaje
    if(!$this->r){
      AmFlash::danger('No se encontró el registro');
      return false;
    }
    return true;

  }

}