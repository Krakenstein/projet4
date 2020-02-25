<?php
declare(strict_types=1);

namespace Projet4\Tools;

class Request
{
  private $_post;
  private $_get;

  public function __construct()
  {
    $this->_post = $_POST;
    $this->_get = $_GET;
  }

  public function post(string $key = null, $default = null)
  {
      return $this->checkGlobal($this->_post, $key, $default);
  }

  public function get(string $key = null, $default = null)
  {
      return $this->checkGlobal($this->_get, $key, $default);
  }

  private function checkGlobal($global, $key = null, $default = null)
  {
    if ($key) {
      if (isset($global[$key])) {
        return htmlspecialchars($global[$key]);
      } else {
        return $default ?: null;
      }
    }
    return $global;
  }
}

