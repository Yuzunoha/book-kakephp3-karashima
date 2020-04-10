<?php

namespace App\Model\Validation;

use Cake\Validation\Validation;

class CustomValidation extends Validation
{
  /**
   * ユーザ名バリデート
   * @param string $username
   * @return bool
   */
  public static function validateUsername($username)
  {
    // usernameが大文字小文字問わずadminから始まるならfalse
    return (stripos($username, 'admin') !== 0);
  }
}
