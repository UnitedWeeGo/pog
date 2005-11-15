<?php 
/* 
* AUTHOR  : Created on 5 janv. 2005 by Jonas Pfenniger 
* LICENSE : You are free to use this code 
* DESC    : Mixins are pretty similar to multiple inheritance. The 
goal is to 
* add capabilites from a class to another class or instance. 
* I don't know what it's usefull for, but I'm sure you'll find out ;) 
* Because of the limitations of the PHP language, I was not able to do 
some 
* things, but it was fun to find workarouds where it's possible.. 
* 
*    This hack was inspired by the Ruby language, who supports mixins 
natively 
*    See: http://www.ruby-lang.org   and   http://www.rubyonrails.com 
* 
*/ 


/** 
* Mixin : allow to inheritate methods and properties of multiple 
objects 
*  - Limitations : 
*    - Cannot inherit __get and __set 
*    - Reflection does not show the new methods 
*    - Some behaviors are still weird 
*/ 
class Object 
{ 
/** 
* Contains class_name => instances 
*/ 
private $mixin_objects = array(); 


/** 
* Contains method_name => calling code 
*/ 
private $mixin_methods = array(); 


/** 
* For the tests 
*/ 
public $hoi = 0; 


/** 
* Method and variable mixing 
* @var string A class name 
*/ 
public function mixin($class_name) 
{ 
if (!class_exists($class_name)) 
trigger_error("Class name $class_name is not loaded", E_USER_ERROR); 
if (array_key_exists($class_name, $this->mixin_objects)) 
{ 
trigger_error("Mixin $class_name allready registered"); 
return; 



} 


// Variable argsnum on constructor 
$args = func_get_args(); 
$c = 'return new '.$class_name.'('; 
for($i=1; $i<count($args); $i++) 
{ 
$c .= '$args['.$i.']'; 
if ($i < count($args) - 1) $c .= ','; 

} 


$c .= ');'; 

// Create instance 
$x = eval($c); 
if ($x instanceof MixinChild) 
{ 
$x->setMixinParent($this); 


} 


$this->mixin_objects[$class_name] = $x; 

// Link methods 
$refl_class = new ReflectionClass($class_name); 
$refl_methods = $refl_class->getMethods(); 
foreach($refl_methods as $refl_method) 
{ 
// TODO : Inherited methods should not be added 


// Do not private and protected methods 
if ($refl_method->isPublic()) 
$this->mixin_methods[$refl_method->getName()] = 
'$this->mixin_objects['. 
$class_name . 
']->'. $refl_method->getName(); 



} 


// Link parameters 
foreach ($x as $k => &$v) 
{ 
if (!isset($this->$k)) 
$this->$k = &$v; 


} 
} 


/** 
* Method overloading 
* @var string Method name 
* @var array Method arguments 
*/ 
public function __call($method_name, $args) 
{ 
if (!array_key_exists($method_name, $this->mixin_methods)) 
{ 
trigger_error("Method $method_name does not exist"); 
return; 


} 


$c = 'return '. $this->mixin_methods[$method_name] .'($args[0]'; 
for($i=1; $i<count($args); $i++) $c .= ',$args['.$i.']'; 
$c .= ');'; 

return eval($c); 



} 


public function hasMixin($class_name) 
{ 
return array_key_exists($class_name, $this->mixin_objects); 


} 


/** 
* For the tests 
*/ 
public function directCall() 
{ 
return; 


} 
} 


/** 
* Use this class if you want the mixed class to have access to the 
parent 
*/ 
abstract class MixinChild extends Object 
{ 
protected $mixin_parent = null; 

public function setMixinParent(Object $mixin_parent) 
{ 
$this->mixin_parent = $mixin_parent; 



} 
} 


/** 
* Implementation example 
*/ 
class Prout extends MixinChild 
{ 
public $woot = 2; 

public function __construct($woot) 
{ 
$this->woot = $woot; 



} 


/** 
* Woot is so cool 
*/ 
public function Woot($a, $b, $c) 
{ 
return "$a, $b and $c are reading /."; 


} 


public function get() 
{ 
return $this->woot; 


} 


public function set($x) 
{ 
$this->woot = $x; 
$this->mixin_parent->hoi = $x; 


} 


public function indirectCall() 
{ 
return; 


} 


public function __set($k, $v) 
{ 
$this->mixin_parent->$k = "PHP"; 


} 


public function __get($k) 
{ 
return "I love ".$this->mixin_parent->$k; 


} 
} 


/***\ 
|***|==> Start demo code HEHE 
\***/ 

define('BR', "<br />\n"); 


echo "<h3>Mixin demo</h3>"; 


$x = new Object(); 


// Constructor assignation 
$x->mixin('Prout', 6); 
echo "Constructor test : ". ($x->woot==6?'true':'false'), BR; 


// Settest 
$x->set(5); 
echo "Set test: " . ($x->woot==5?'true':'false'), BR; 
echo "Set parent test: " . ($x->hoi==5?'true':'false'), BR; 


// Gettest 
$x->woot = 3; 
echo "Get test: ". ($x->get()==3?'true':'false'), BR; 


// Call test 
echo "Method call test: " . $x->Woot('nitro', 'tritoul', 'zimba'), BR; 


// __get and __set test 
$x->notAssignedVar = 3; 
echo "__set and __get test : " . ($x->notAssignedVar == 'I love 
PHP'?'true':'false'), BR; 


//********** BENCHMARKS *********** 


$loops = 10000; 


// Direct call 
$start_time = (float) array_sum(explode(' ', microtime())); 
for($i=0; $i<$loops; $i++) 
{ 
$x->directCall(); 


} 


$end_time = (float) array_sum(explode(' ', microtime())); 
$direct_time = $end_time - $start_time; 
echo "Direct call bench($loops) : ". $direct_time, BR; 

// Mixin call 
$start_time = (float) array_sum(explode(' ', microtime())); 
for($i=0; $i<$loops; $i++) 
{ 
$x->indirectCall(); 


} 


$end_time = (float) array_sum(explode(' ', microtime())); 
$mixin_time = $end_time - $start_time; 
echo "Mixin call bench($loops) : " . $mixin_time, BR; 

// Difference 
echo "Execution time difference : ".$mixin_time / $direct_time, BR; 


echo "<b>the end</b>", BR; 
echo "<pre>"; 
print_r($x); 
echo "</pre>"; 


?> 

