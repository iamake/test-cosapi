<?php

namespace TencentTH\VODApi;

class Testcase {
  public $title;
  public $func;
  public $pass;

  public function __construct($title, $func, $pass) {
    $this->title = $title;
    $this->func = $func;
    $this->pass = $pass;
  }

  public function execute() {
    return ($this->func)();
  }
}
