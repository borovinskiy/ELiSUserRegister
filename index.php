<?php namespace ELiS;
  require_once('api.php'); 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='theme-color' content='#0981B3'/>
    <meta property="og:title" content="<?php echo ELiSClientApi::$organiztionTitle; ?>">
    <meta property="og:locale" content="ru_RU">
    <link rel="stylesheet" href="style.css">
    <title>Регистрация в <?php echo ELiSClientApi::$organiztionTitle; ?></title>
  </head>
  <body>
    <header>
      <img class="logo" src="<?php echo ELiSClientApi::$logoUrl; ?>"/>
      <br/>
      <h1>Регистрация в ЭБС от <?php echo ELiSClientApi::$organiztionTitle; ?></h1>
    </header>
      
    <main>
      
      <?php
      if (!ELiSClientApi::isRegistrationAllowed()) {
        print "<p class='info'>Регистрация из вашей сети не разрешена.<br/> Вероятно вам надо быть в сети организации для возможности регистрации.</p>";
      } else if (isset($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_REQUEST['email'];
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $link = ELiSClientApi::getAuthLinkForUser($email, $username); // Ссылку пользователю выводить не надо, иначе он сможет залогинеться под любым пользователем с известным e-mail
        $is_sended = ELiSClientApi::sendLinkToEmail($link, $email);
        if ($is_sended) print "<p class='info'>На " . htmlspecialchars ($email) . " отправлено письмо со ссылкой для входа.</p>";
      } else { 
      ?>
        <form method='POST'>
          
          <label>e-mail</label>
          <input type='email' placeholder='ivanov@yandex.ru' title='На какой адрес провести регистрацию' name='email' required/>
          
          <label>Желаемое имя пользователя</label>
          <input type="text" placeholder='Иванов Иван Иванович' title='Желаемое имя пользователя' name='username'>
          
          <button type='submit' class="button">Отправить ссылку для входа</button>
          
        </form>
      <?php  
      }  
      ?>
    </main>
  </body>
</html>
