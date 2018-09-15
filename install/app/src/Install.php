<?php

class Install {

  private $states = ["welcome", "languages", "server_check", "bdd", "configure", "admin", "finish"];

  public function __construct(){

  }

  public function _getStates(){
    if(empty($_GET['s']) || !in_array($_GET['s'], $this->states)) return $this->states[0];
    return $_GET['s'];
  }

  public function _loadPage(){
    require("app/views/".$this->_getStates().".php");
  }

  public function _getBack(){
    $pos = array_search($this->_getStates(), $this->states);
    if($pos == 0) return null;
    return "?s=".$this->states[$pos - 1];
  }

  public function _getForward(){
    $pos = array_search($this->_getStates(), $this->states);
    if($pos == count($this->states) - 1) return null;
    return "?s=".$this->states[$pos + 1];
  }

  public function _getRefresh(){
    return "?s=".$this->_getStates();
  }

  public function _getServerRequirement(){

    return [
      "php_version" => [
        "title" => "PHP Version",
        "description" => "Need to be 5.6.0 or more",
        "valid" => version_compare(PHP_VERSION, '5.6.0') >= 0
      ],
      "curl_extension" => [
        "title" => "CURL Available",
        "description" => "CURL extension need to be enabled",
        "valid" => function_exists('curl_version')
      ],
      "pdo_available" => [
        "title" => "PDO Available",
        "description" => "PDO need to be enabled",
        "valid" => defined('PDO::ATTR_DRIVER_NAME')
      ],
      "openssl_extension" => [
        "title" => "OpenSSL Available",
        "description" => "OpenSSL need to be enabled",
        "valid" => extension_loaded('openssl') || function_exists('openssl_encrypt')
      ],
      "config_file" => [
        "title" => "Config file writable",
        "description" => "Config file (/config/config.settings.php) writable",
        "valid" => is_writable('../config/config.settings.php')
      ],
      "public_dir" => [
        "title" => "Public directory writable",
        "description" => "Public directoruy (public) writable",
        "valid" => is_writable('../public')
      ],
      "allow_url_fopen" => [
        "title" => "Allow url fopen",
        "description" => "You can follow the guide here : <a target=_bank href='http://community.ovrley.com/topic/41/enable-allow-url-fopen'>Allow url fopen guide</a>",
        "valid" => ini_get('allow_url_fopen')
      ]
    ];

  }

  public function _post($state){
    if(empty($_POST)) return true;
    $_SESSION[$state] = $_POST;
    if($state == "bdd") return $this->_generateBDD();
    if($state == "admin") return $this->_createAdmin();
    return true;
  }

  public function _generateBDD(){

    try {
      $bdd = new PDO('mysql:host='.$_SESSION['bdd']['sql_host'].';dbname='.$_SESSION['bdd']['sql_database_name'], $_SESSION['bdd']['sql_user'], $_SESSION['bdd']['sql_password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));

      $sqlStructure = file_get_contents('assets/sql/krypto.sql');

      $status = $bdd->exec($sqlStructure);

      if($status == 1) throw new Exception("Error : Fail to create database structure", 1);

      return true;
    } catch (\Exception $e) {
      return $e->getMessage();
    }

  }

  private function generateScretkey() {
      $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
      $pass = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < 60; $i++) {
          $n = rand(0, $alphaLength);
          $pass[] = $alphabet[$n];
      }
      return implode($pass); //turn the array into a string
  }

  public function _getConfigureContent(){
    $websitepath = str_replace(['install/app/src', $_SERVER['DOCUMENT_ROOT']], ['', ''], dirname(__FILE__));
    if($websitepath != "/") $websitepath = substr($websitepath, 0, -1);
    return [
      'website_url' => [
        'title' => 'Website url',
        'precontent' => str_replace('/install/index.php?s=configure', '', sprintf( "%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI'] )),
        'disabled' => false,
        'require' => true
      ],
      'website_path' => [
        'title' => 'Website path',
        'precontent' => $websitepath,
        'disabled' => false,
        'require' => false
      ]
    ];

  }

  public function _getLoginFields(){
    return [
      'admin_name' => [
        'title' => 'Name',
        'placeholder' => 'John Miller',
        'disabled' => false
      ],
      'admin_email' => [
        'title' => 'Email',
        'placeholder' => 'admin@domain.tld',
        'disabled' => false
      ],
      'admin_password' => [
        'title' => 'Password',
        'placeholder' => 'Your password',
        'disabled' => false
      ]
    ];
  }

  public function _createAdmin(){
    $bdd = new PDO('mysql:host='.$_SESSION['bdd']['sql_host'].';dbname='.$_SESSION['bdd']['sql_database_name'], $_SESSION['bdd']['sql_user'], $_SESSION['bdd']['sql_password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $req = $bdd->prepare('INSERT INTO user_krypto (email_user, name_user, password_user, created_date_user, admin_user)
                            VALUES (:email_user, :name_user, :password_user, :created_date_user, :admin_user)');

    $status = $req->execute([
          'email_user' => $_SESSION['admin']['admin_email'],
          'name_user' => $_SESSION['admin']['admin_name'],
          'password_user' => hash('sha512', $_SESSION['admin']['admin_password']),
          'created_date_user' => time(),
          'admin_user' => 1
        ]);
    $req->closeCursor();

    $this->_saveSettings();

    return $status;
  }

  public function _saveSettings(){

    $bdd = new PDO('mysql:host='.$_SESSION['bdd']['sql_host'].';dbname='.$_SESSION['bdd']['sql_database_name'], $_SESSION['bdd']['sql_user'], $_SESSION['bdd']['sql_password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $req = $bdd->prepare('UPDATE settings_krypto SET value_settings=:value_settings WHERE key_settings=:key_settings');

    $status = $req->execute([
      'value_settings' => $_SESSION['languages']['language_select'],
      'key_settings' => 'default_language'
    ]);

    $req->closeCursor();

    $fileConfig = "<?php
    define('APP_URL', '".$_SESSION['configure']['website_url']."');
    define('APP_URL_FORCE', false);

    define('FILE_PATH', '".$_SESSION['configure']['website_path']."');

    define('MYSQL_HOST', '".$_SESSION['bdd']['sql_host']."');  // MySQL Database host (localhost, 127.0.0.1, X.X.X.X, domain.tld)
    define('MYSQL_USER', '".$_SESSION['bdd']['sql_user']."');   // MySQL User (Please not use 'root', create a dedicated user with full permision user --> go doc)
    define('MYSQL_PASSWD', '".$_SESSION['bdd']['sql_password']."'); // MySQL Password
    define('MYSQL_PORT', '3306');        // MySQL Port (Set empty for not specify port)
    define('MYSQL_DATABASE', '".$_SESSION['bdd']['sql_database_name']."');        // MySQL Database (Use the file sql.sql for create sql requirement)

    define('CRYPTED_KEY', '".$this->generateScretkey()."');
?>";

    file_put_contents('../config/config.settings.php', $fileConfig);

  }

}

?>
