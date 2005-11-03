<?
class A {
 public $a1;
 function A() {
  $this->a1=1000;
 }
 function c() {
  print_r($this);
 }
}
class B {
 public $b1;
 function B($v) {
  $this->b1 = $v;
 }
 static function d($v,$w,$a1='') {
   $o = new B($v);
   $o->b1 = $w;
 }
}

class C{
	function f($x, $y)
	{
		B::d($x, $y);
	}
}

$o = new A();
$o->c();

$c = new C();
$c->f('a1',$o->a1);

//B::d('a1',$o->a1);
$o->c(); // expect failure
?>
