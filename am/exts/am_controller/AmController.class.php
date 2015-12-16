<?php
/**
 * Amathista - PHP Framework
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

/**
 * -----------------------------------------------------------------------------
 * Clase para controlador estandar. 
 * -----------------------------------------------------------------------------
 */

class AmController extends AmResponse{

  private static

    /**
     * -------------------------------------------------------------------------
     * Callbacks para mezclar atributos
     * -------------------------------------------------------------------------
     */
    $mergeFunctions = array(
      'paths'         => 'array_merge',
      'prefix'        => 'array_merge',
      'actionAllows'  => 'array_merge',
      'headers'       => 'merge_unique',
      'filters'       => 'merge_r_if_snd_first_not_false',
    );

//   protected
//     $root = null,             // Carpeta raiz del controlador
//     $paths = array(),         // Carpetas donde se buscara las vistas
//     $headers = array(),       //
//     $view = null,             // Nombre de la vista a renderizar
//     $filters = array(),       // Filtros agregados
//     $credentials = false,     // Credenciales para el controlador
//     $prefixs = array(),       // Prefijos para diferentes elementos en el controlador
//     $actionAllows = array(),  // Acciones permitidas

//     $response = null,   // Respesta de la peticion
//     $server = null,     // Variables de SERVER
//     $get = null,        // Variables recibidas por GET
//     $post = null,       // Variables recibidas por POST
//     $request = null,    // Todas las variables recibidas
//     $cookie = null,     // Çookies
//     $env = null;        // Variables de entorno

//   public function __construct($data = null){
//     parent::__construct($data);

//     $this->server = new AmObject($_SERVER);
//     $this->get = new AmObject($_GET);
//     $this->post = new AmObject($_POST);
//     $this->cookie = new AmObject($_COOKIE);
//     $this->request = new AmObject($_REQUEST);
//     $this->env = new AmObject($_ENV);

//   }

//   final public function includeHeaders(){
//     foreach($this->headers as $value) {
//       header($value);
//     }
//   }

//   // Devuelve la URL de base del controlador
//   final public function getUrl(){
//     return $this->url;
//   }

//   final public function redirect($url = ''){
//     Am::gotoUrl($this->url . $url);
//   }

//   // Propiedad para get/set para render
//   final protected function getView(){ return $this->view; }
//   final protected function setView($value){ $this->view = $value; return $this; }

//   // Devuelve un array de los paths de ambito del controlador
//   final protected function getPaths(){

//     // PENDIENTE: Revisar mas adelante como llegan los paths aqui.
//     // Se puede presentar un problema pues se invertir el orden de recorrifo
//     // de findFileIn
//     // 
//     $ret = array_filter($this->paths);  // Tomar valores validos
//     $ret = array_unique($ret);          // Valor unicos
//     $ret = array_reverse($ret);         // Invertir array

//     // Agregar carpeta raiz del controlador si existe si existe
//     if(isset($this->root) && ($path = realPath($this->root)))
//       array_unshift($ret, $path);

//     // Agregar carpeta raiz del controlador para vistas
//     // si existe si existe
//     if(isset($this->views) && ($path = realPath($this->root . '/' . $this->views)))
//       array_unshift($ret, $path);

//     // Invertir array,
//     return $ret;

//   }

//   // Devuelve el método de la peticion
//   final protected function getMethod(){
//     return strtolower($this->server->REQUEST_METHOD);
//   }

  /**
   * ---------------------------------------------------------------------------
   * Devuelve el nombre de la vista a renderizar para una acción.
   * ---------------------------------------------------------------------------
   * @param   $action   string  Nombre de la acción de la que se desea obtener
   *                            la vista.
   * @return  string            Nombre de la vista
   */
  final protected static function getViewName($action){
    return "{$action}.view.php";
  }

//   // Devuelve el prefijo para determinado elemento
//   final protected function getPrefix($key){
//     return itemOr($key, $this->prefixs, '');
//   }

//   // Asigna la vista que se renderizará.
//   // Es un Alias de la funcion setView que agrega .view.php al final
//   // del valore recibido.
//   final protected function render($value){
//     // Las vista de las acciones son de extencion .view.php
//     return $this->setView(self::getViewName($value));
//   }

//   // Renderizar la vista
//   final private function renderView(array $vars, $child){

//     // Renderizar vista mediante un callback
//     $ret = Am::call('render.template',

//       // Obtener vista a renderizar
//       $this->getView(),

//       array(
//         'paths' => $this->getPaths(), // Obtener carpetas de ambito para el controlador
//           // Variables en la vista
//         'env' => array_merge($vars, $this->toArray()),
//         'child' => $child,
//       )

//     );

//     // Si no se logra renderizar la vista se imprime
//     // se imprime lo que viene en $child
//     return $ret !== false;

//   }

//   final protected function setResponse($response){
//     $this->response = $response;
//   }

//   // Responder como servicio
//   final private function renderService($content){

//     $type = 'json';

//     isset($content) && is_object($content) AND $content = (array)$content;

//     switch ($type){
//       case 'json':
//         $contentType = 'application/json';
//         $content = json_encode($content);
//         break;
//       default:
//         $contentType = 'text/plain';
//         $content = print_r($content, true);
//         break;
//     }

//     header("content-type: {$contentType}");
//     echo $content;

//   }

//   // Devuelve el array de acciones permitidas
//   final public function getActionAllows(){
//     return $this->actionAllows;
//   }

//   // Indica si una accion esta permitida o no.
//   // Si las acciones permitidas no tiene el item
//   // correspondiente a la acción solicitada entonces
//   // se asume que esta permitida la acción.
//   final public function isActionAllow($action){
//     return isset($this->actionAllows[$action])?
//       $this->actionAllows[$action] : true;
//   }

//   // Revisa si una accion esta permitida. Si la acción no esta
//   // permitida se redirigue a la url raiz del controlador
//   final public function checkIsActionAllow($action){
//     if(!$this->isActionAllow($action))
//       Am::gotoUrl($this->url);
//   }

//   // Despachar una acción
//   final public function dispatch($action, array $params){

//     // Todo lo que se imprimar desde este punto hasta
//     // ob_get_clean() se guardará en una variable.
//     ob_start();

//     // Ejecutar accion con sus respectivos filtros.
//     $ret = $this->executeAction($action, $this->getMethod(), $params);

//     // Para obtener la salida
//     $buffer = ob_get_clean();

//     // Si la salida es indicada como salida de un servicio
//     // o si el último retorno es un array o un objeto se procesa
//     // la salida como un servicio. De lo contrario se renderizará
//     // la vista correspondiente.

//     $this->includeHeaders();
//     // Responder como un sericio
//     if(is_array($ret) || is_object($ret))
//       $this->renderService($ret);

//     else if(isset($this->response)){

//       if(is_array($this->response) || is_object($this->response))
//         $this->renderService($this->response);
      
//       else
//         echo $this->response;

//     }else if($ret !== false)
//       // Renderizar la vista
//       $this->renderView($params, $buffer);

//     else
//       echo $buffer;

//   }

//   // Manejo de filtros para las acciones de los controladores

//   // Agregar un filtro
//   final protected function addFilter($name, $cls, $to = 'all', $except = array(), $redirect = null){

//     // Filtro 'only' para ciertos métodos
//     if(is_array($to)){
//       $scope = 'only';
//       $redirect = $except;
//       $except = array();

//     // Filtro para 'all' métodos o para 'except'
//     }else{
//       $scope = $to;
//       $to = array();
//     }

//     // Si no se ha creado el contenedor del filtro, se crea
//     if(!isset($this->filters[$state][$name])){

//       // Crear array vacío en el state si no existe.
//       if(!isset($this->filters[$state]))
//         $this->filters[$state] = array();

//       // Agregar filtro vacío
//       $this->filters[$state][$name] = array(

//         // A que metodo se aplicara el filtro: 'all', 'only' o 'except'
//         'scope' => $scope,

//         // A quienes se aplicara el filtro en caso de que scope=='only'
//         'to' => array(),

//         // A quienes no se aplicará el filtro en caso de que scope=='except'
//         'except' => $except,

//         // Si la peticion no pasa el filtro rediriguir a la siguiente URL
//         'redirect' => $redirect

//       );

//     }

//     // Mezclar los métodos a los que se aplicará el filtro con los que
//     // ya habian sido agregados y obtener los valores unicos
//     $this->filters[$state][$name]['to'] = array_unique(array_merge(
//       $this->filters[$state][$name]['to'],
//       $to
//     ));

//   }

//   // Agregar un filtro antes de la ejecucion de metodos
//   final protected function addBeforeFilter($name, $to = 'all', $except = array(), $redirect = null){
//     $this->addFilter($name, 'before', $to, $except, $redirect);
//   }

//   // Agregaun filtro antes de la ejecucion de métodos GET
//   final protected function addBeforeGetFilter($name, $to = 'all', $except = array(), $redirect = null){
//     $this->addFilter($name, 'before_get', $to, $except, $redirect);
//   }

//   // Agregaun filtro antes de la ejecucion de métodos POST
//   final protected function addBeforePostFilter($name, $to = 'all', $except = array(), $redirect = null){
//     $this->addFilter($name, 'before_post', $to, $except, $redirect);
//   }

//   // Agregaun filtro despues de la ejecucion de métodos
//   final protected function addAfterFilter($name, $to = 'all', $except = array()){
//     $this->addFilter($name, 'after', $to, $except);
//   }

//   // Agregaun filtro despues de la ejecucion de métodos GET
//   final protected function addAfterGetFilter($name, $to = 'all', $except = array()){
//     $this->addFilter($name, 'after_get', $to, $except);
//   }

//   // Agregaun filtro despues de la ejecucion de métodos POST
//   final protected function addAfterPostFilter($name, $to = 'all', $except = array()){
//     $this->addFilter($name, 'after_post', $to, $except);
//   }

//   // Ejecuta los filtros correspondiente para un método.
//   // state: Indica el estado que se ejecutara: before, before_get, bofore_post, after, after_get, after_post
//   // methodName: Nombre del metodo del que se desea ejecutar los filtros.
//   // estraParams: Parámetros extras para los filtros.
//   final protected function executeFilters($state, $methodName, $extraParams){

//     // Si no hay filtro a ejecutar para dicha peticion salir
//     if(!isset($this->filters[$state]))
//       return true;


//     // Recorrer los filtros del peditoestado
//     foreach($this->filters[$state] as $filterName => $filter){

//       // Si el filtro no se aplica a todos y si el metodo solicitado no esta dentro de los
//       // métodos a los que se aplicará el filtro actual continuar con el siguiente filtro.
//       if($filter['scope'] != 'all' && !in_array($methodName, $filter['to']))
//         continue;

//       // Si el método esta dentro de las excepciones del filtro
//       // continuar con el siguiente filtro
//       if(isset($filter['except']) && in_array($methodName, $filter['except']))
//         continue;

//       // Obtener le nombre real del filtro
//       $filterRealName = $this->getPrefix('filters') . $filterName;

//       // Llamar el filtro
//       $ret = call_user_func_array(array(&$this, $filterRealName), $extraParams);

//       // Si la accion pasa el filtro o no se trata de un filtro before
//       // se debe continuar con el siguiente filtro
//       if($ret !== false || $state != 'before')
//         continue;

//       // Si se indica una ruta de redirección se lleva a esa ruta
//       if(isset($filter['redirect']))
//         Am::gotoUrl($filter['redirect']);

//       // Si no retornar false para indicar que no se pasó el filtro.
//       return false;

//     }

//     // Si todos los filtros pasaron retornar verdadero.
//     return true;

//   }

//   // Ejecuta una accion determinada
//   final protected function executeAction($action, $method, array $params){

//     // Chequear si esta permitida o no la acción
//     $this->checkIsActionAllow($action);

//     // Verificar las credenciales
//     Am::getCredentialsHandler()
//       ->checkCredentials($action, $this->credentials);

//     // Valor de retorno
//     $ret = null;

//     // Si el metodo existe llamar
//     if(method_exists($this, $actionMethod = 'action'))
//       call_user_func_array(array($this, $actionMethod), $params);

//     // Ejecutar filtros para la acción
//     if(!$this->executeFilters('before', $action, $params))
//       return false;

//     // Si el metodo existe llamar
//     if(method_exists($this, $actionMethod = $this->getPrefix('actions') . $action)){
//       $retTmp = call_user_func_array(array($this, $actionMethod), $params);
//       // Sobre escribir la salida
//       if($retTmp){
//         echo $ret;
//         $ret = $retTmp;
//       }
//     }

//     // Ejecutar filtros para la acción por el método enviado
//     if(!$this->executeFilters("before_{$method}", $action, $params))
//       return false;

//     // Si el metodo existe llamar correspondiente al metodo de la peticion
//     if(method_exists($this, $actionMethod = $this->getPrefix("{$method}Actions") . $action)){
//       $retTmp = call_user_func_array(array($this, $actionMethod), $params);
//       // Sobre escribir la salida
//       if($retTmp){
//         echo $ret;
//         $ret = $retTmp;
//       }
//     }

//     $this->executeFilters("after_{$method}", $action, $params);
//     $this->executeFilters('after', $action, $params);

//     return $ret;

//   }

  /**
   * ---------------------------------------------------------------------------
   * Para despachar la petición.
   * ---------------------------------------------------------------------------
   * @return [type] [description]
   */
  public function make(){
    var_dump($this);
  }

  /**
   * ---------------------------------------------------------------------------
   * Método para mezclar dos configuraciones.
   * ---------------------------------------------------------------------------
   * La mezcla se basa en los parámetros de 
   * @param   array  $confToRewrite   Configuración a sobreescribir.
   * @param   array  $conf            Configuración a agregar.
   * @return  array                   Configuraciones mezcladas.
   */
  private static function mergeConf(array $confToRewrite, array $conf){

    // Agregar items de
    foreach ($confToRewrite as $key => $value)

      // Si no existe en la configuraicon hija se asigna.
      if(!isset($conf[$key]))
        $conf[$key] = $confToRewrite[$key];

      // De lo contrario mezclar los datos
      else if(isset(self::$mergeFunctions[$key]))

        $conf[$key] = call_user_func_array(self::$mergeFunctions[$key],
          array(
            $confToRewrite[$key],
            $conf[$key]
          )
        );

    return $conf;

  }

  /**
   * ---------------------------------------------------------------------------
   * Incluye un controlador.
   * ---------------------------------------------------------------------------
   * @param   string   $control   Nombre del controlador a incluir.
   * @return  array               Configuración del controlador
   */
  public static function includeController($controller){

    // Obtener configuraciones del controlador
    $confs = Am::getProperty('controllers');

    // Obtener valores por defecto
    $defaults = itemOr('defaults', $confs, array());

    // Si no existe configuracion para el controlador
    $conf = itemOr($controller, $confs, array('root' => ''));

    // Si no es un array, entonces el valor indica el path del controlador
    if(is_string($conf))
      $conf = array('root' => $conf);
      
    $conf['root'] = realPath($conf['root']);

    if(is_file($realFile = "{$conf['root']}/am.init.php"))
      require_once $realFile;

    // Mezclar con el archivo de configuracion en la raiz del
    // controlador.
    if(is_file($realFile = "{$conf['root']}/am.conf.php"))
      $conf = self::mergeConf($conf, require($realFile));

    // Si tiene no tiene padre o si el padre esta vacío
    // y se mezcla con la configuracion por defecto
    if(!isset($conf['parent']) || empty($conf['parent'])){

      // Mezclar con valores por defecto
      $conf = self::mergeConf($defaults, $conf);

      // Obtener el nombre real del controlador
      $controllerName = itemOr('name', $conf, $controller);

    // Mezclar con configuracion del padre
    }else{

      // Obtener la configuracion del padre
      $confParent = self::includeController($conf['parent']);

      // Agregar carpeta de vistas por defecto del padre.
      $confParent['paths'][] = $confParent['root'];
      $confParent['paths'][] = $confParent['root'] . '/' . $confParent['views'];

      // Obtener el nombre real del controlador antes de mezclar con el padre
      $controllerName = itemOr('name', $conf, $controller);

      // Mezclar con la configuracion del padre
      $conf = self::mergeConf($confParent, $conf);

    }

    // Obtener la ruta del controlador
    // Incluir controlador si existe el archivo
    if(is_file($file = "{$conf['root']}/{$controllerName}.control.php")){
      $conf['name'] = $controllerName;
      require_once $file;
    }

    // Incluir como extension
    Am::load($conf['root'] . '/');

    // Retornar la configuracion obtenida
    return $conf;

  }

  /**
   * ---------------------------------------------------------------------------
   * Obtiene el controlador y la accion de un cadena de caracteres.
   * ---------------------------------------------------------------------------
   * @param  string       $actionStr  String con la accion en formato
   *                                  'controlador@accion'
   * @return false/array              Array asociativo con el controlador y
   *                                  la acción. Si no coincide con el formato
   *                                  devuelve false.
   */
  private static function getControllerAndAction($actionStr){

    if(preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)@([a-zA-Z_][a-zA-Z0-9_]*)$/',
          $actionStr, $m))
      return array(
        'controller' => $m[1],
        'action' => $m[2]
      );
    return false;

  }

  /**
   * ---------------------------------------------------------------------------
   * Pre procesador de rutas.
   * ---------------------------------------------------------------------------
   * Verifica y transforma la ruta del formato 'controlador@acction'.
   * @param  array  $route  Ruta a evaluar.
   * @return array          Ruta transformada.
   */
  public static function routePreProcessor($route){

    // Obtener acción
    $action = self::getControllerAndAction($route['']);

    // Si es una acción válida se asigna
    if(false !== $action)
      $route['controller'] = $action;

    // Si no es una acción válida se se verifica ahora en el indice controller
    elseif(isset($route['controller'])){
      // Obtener la acción
      $action = self::getControllerAndAction($route['controller']);

      // Si es una acción válida se asigna
      if(false !== $action)
        $route['controller'] = $action;
      
    }
    
    return $route;

  }

  /**
   * ---------------------------------------------------------------------------
   * Manejador para el evento response.controller.
   * ---------------------------------------------------------------------------
   * Funcion para atender las respuestas por controlador. Recive , un array con
   * el entorno y un array con los parámetros obtenidos de la ruta.
   * @param   string  $action   La acción a ejecutar en formato del controlador
   *                            (controlador@accion).
   * @param   array  $env       Variables de entorno.
   * @param   array  $params    Argumentos obtenidos de la ruta.
   */
  public static function response($action, array $env = array(),
                                  array $params = array()){

    $controller = $action['controller'];
    $action = $action['action'];

    // Valores por defecto
    $conf = array_merge(
      // Incluye el controlador y devuelve la configuracion para el mismo
      self::includeController($controller),

      // Entorno
      $env,

      // Asignar vista que se mostrará,
      array(
        'view' => self::getViewName($action),
        '_action' => $action,
        '_params' => $params,
      )
    );

    // Obtener la instancia del controlador
    $controller = Am::getInstance("{$conf['name']}Controller", $conf);  

    // Si no se puede instanciar el controlador retornar false.
    
    if(null === $controller)
      return Am::e404(Am::t('AMCONTROLLER_ACTION_NOT_FOUND',
        $controller, $action));

    // Devolver controlador
    return $controller;

  }

}
