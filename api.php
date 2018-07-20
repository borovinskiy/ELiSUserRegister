<?php namespace ELiS;

require_once('conf.php');

/**
 * Класс для аутентификации пользователей
 *
 * @author Arsen I. Borovinskiy
 */
class ELiSClientApi extends Conf {
  
  static public function getBaseUrl() {
    return self::$proto . '://' . self::$hostname;
  }
  
  /**
   * Возвращает ссылку на аутентификацию пользователя по $email.
   * $username и $redirect - опциональные параметры.
   * Выданная ссылка произведет логин пользователя в email если пользователь
   * существует и прикреплен к данной организации или создаст нового пользователя.
   */
  static public function getAuthLinkForUser($email, $username = '', $redirect = '') {
    return self::generateAuthLink(array(
      'email'=>$email, 'username'=>$username, 'redirect'=>$redirect
    ));
  }

  /**
   * Генерирует ссылку для аутентификации.
   * @param array $options массив с опциями. Обязательной является опция "email":  array("email"=>"user@example.com").
   * @return string ссылка, переход по которой залогинет пользователя.
   */
  static protected function generateAuthLink($options) {
    
    // Извлекаем опции настойки
    $email = isset($options['email']) ? $options['email'] : '';           // обязательный параметр
    $baseUrl = isset($options['baseUrl']) ? $options['baseUrl'] : self::getBaseUrl(); // из конфигурации
    $orgId = isset($options['orgId']) ? $options['orgId'] : self::$orgId;             // из конфигурации
    $secret = isset($options['secret']) ? $options['secret'] : self::$secret;         // из конфигурации
    $username = isset($options['username']) ? $options['username'] : '';  // не обязательный параметр
    $redirect = isset($options['redirect']) ? $options['redirect'] : '';  // не обязательный параметр
    
    $time = time();

    $str = $email . $orgId . $time . $username . $redirect . $secret;
    $sign = hash("sha1", $str);     // подпись сформирована

    // Создаем URL из обязательных параметров
    $autologinUrl = $baseUrl . "/autologin?email=" . urlencode($email) . "&orgid=$orgId&time=$time&sign=" . urlencode($sign);
    // Добавляем опциональные параметры, если заданы
    if ($redirect && strlen($redirect)) $autologinUrl .= "&redirect=" . urlencode($redirect);
    if ($username && strlen($username)) $autologinUrl .= "&username=" . urlencode($username);

    return $autologinUrl;
  }

  /**
   * Отправляет ссылку для аутентификации на e-mail.
   * @param string $link
   * @param string $email
   * @return bool результат отправки письмо на указанный email.
   */
  static public function sendLinkToEmail($link, $email) {
    $subject = "Ссылка на вход в электронную библиотеку от " . self::$organiztionTitle;
    
    $headers = "From: " . self::$robotName . "\r\n";
    $headers .= "Reply-To: ". self::$robotMail . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $message = "<html><body>";
    $message .= "Вы получили это письмо для входа на сайт электронной библиотеки от организации " . self::$organiztionTitle . ".<br/>\r\n";
    $message .= "Пройдите по ссылке <a href='$link' target='_blank'>$link</a> для перехода в электронную библиотеку в течении 24 часов. \r\n";
    $message .= "</body></html>\r\n";
    
    return mail($email, $subject, $message, $headers);
  }
  
  /**
   * Производит валидацию, что текущему пользователю можно регистрироваться.
   * @return bool TRUE если регистрация для текущего пользователя разрешена и FALSE в противном случае.
   */
  static public function isRegistrationAllowed() {
    // Просто проверяем, что текущий пользователь в списке разрешенных IP-адресов организации.
    return self::userFromAllowedIP();
  }
  
  /**
   * Проверяет, что пользователь в списке с разрешенными адресами
   * @return boolean TRUE если пользовательский IP попадает в любой диапазон из массива Conf::allowedIP, иначе FALSE.
   */
  static protected function userFromAllowedIP() {
    $userIP = ip2long($_SERVER['REMOTE_ADDR']);
    foreach (self::$allowedIP as $ipRange) {
      $fromIP = ip2long($ipRange[0]);
      $toIP = ip2long($ipRange[1]);
      if ($fromIP >= $userIP && $userIP <= $toIP) {
        return true;
      }
    }
    return false;
  }
  
}
