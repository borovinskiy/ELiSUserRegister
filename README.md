# ELiSUserRegister

User registration code sample for ELiS library. See: https://elibsystem.ru/docs/admin/autologin.html.

Пример кода для регистрации пользователя в библиотеке (ЭБС) ELiS из сети организации.

# Install

* Clone to server with PHP 5+ support.
* Configure web server for work with cloned directory.
* Edit conf.php and set your settings. See code conf.php for more instructions.
* Check success e-mail sending from web server without mail authorization.

* Скопируйте файлы на сервер с поддержкой PHP 5+.
* Настройте веб-сервер для работы с клонированной директорией.
* Отредактируйте настройки в файле conf.php. Инструкции по настройкам в самом файле conf.php.
* Проверьте, что с вашего веб-сервера отправляются письма, а отправка не требует авторизации на почтовом сервере.

# Usage

* User must be open index.php and send e-mail (reuqired) and username (optional).
* Server is sending link for autologin.
* User going on URL and logged in on digital library and can set password.

* Пользователь должен открыть index.php на вашем веб-сервере и отправить e-mail (обязательно) и имя пользователя (желательное).
* Сервер отправит на e-mail ссылку, пройдя по которой пользователь попадет на сервер электронной библиотеки.
* Пользователь должен открыть ссылку, залогинеться в электронную библиотеку и установить себе пароль на сервере библиотеки.

# Security

* Do not print to user autologin link on web page. You realy want know, that this user is have sending e-mail. E-mail needed for authentificate user by e-mail.
* You must usage restricted access only from your organization IP-address (see in conf.php param Conf::$allowedIP array). Do not opening this for internet.

* Не выводите пользователю ссылку на автологин на веб-странице. В действительно должны убедиться, что пользователь имеет тот e-mail, на который запрашивает регистрацию. E-mail используется как средство аутентификации пользователя.
* Вы должны использовать ограниченный доступ только для IP-адресов вашей организации (см. в conf.php массив Conf::$alloweIP). Не открывайте доступ для всего интернета.
