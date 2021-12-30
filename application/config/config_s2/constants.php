<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
|Definidas por Pacarina Media Lab (Español)
*/

define('NOMBRE_APP', 'DistriCatólicas');            //Nombre de la aplicación

//define('URL_IMG', 'https://www.districatolicas.com/tienda/resources/images/');
define('URL_ASSETS', 'https://www.districatolicas.com/tienda/assets/');
define('URL_RECURSOS', 'https://www.districatolicas.com/tienda/resources/20211122/');
//define('URL_UPLOADS', 'http://www.districatolicas.com/tienda/uploads/');

define('RUTA_IMG', 'recursos/imagenes/');
define('RUTA_UPLOADS', 'uploads/');

define('PTL_ADMIN', 'templates/admin_lte/main_v');       //Vista plantilla de administración
define('PTL_ADMIN_F', 'plantillas/admin_lte/');                //Folder de la carpeta de la plantilla de administración
define('PTL_FRONT', 'plantillas/polo/plantilla_v');          //Vista plantilla del front

define('VER_LOCAL', FALSE);                          //Es una versión local

/*
|--------------------------------------------------------------------------
|Definidas por Pacarina Media Lab
*/

define('APP_NAME', 'DistriCatólicas');                  //Nombre de la aplicación
define('APP_DOMAIN', 'districatolicas.com');

define('URL_SYNC', 'https://www.districatolicas.com/tienda/sync/');
define('URL_APP', 'https://www.districatolicas.com/tienda/');
define('URL_ADMIN', 'https://www.districatolicas.com/tienda/');
define('URL_API', 'https://www.districatolicas.com/tienda/');
define('URL_UPLOADS', 'https://www.districatolicas.com/tienda/uploads/');
define('URL_CONTENT', 'https://www.districatolicas.com/tienda/content/');  //URL de la carpeta de contenido de la aplicación
define('URL_RESOURCES', 'https://www.districatolicas.com/tienda/resources/20211122/');
define('URL_IMG', 'https://www.districatolicas.com/tienda/resources/20211122/images/');
define('URL_BRAND', 'https://www.districatolicas.com/tienda/resources/20211122/brands/districatolicas/');

define('BG_DARK', '#1c95d1');

define('ENV', 'production');

define('PATH_CONTENT', 'content/');                         //Carpeta de contenido de la aplicación
define('PATH_UPLOADS', 'content/uploads/');
define('PATH_RESOURCES', 'resources/20211122/');

define('MAX_REG_EXPORT', 5000);                             //Número máximo de registros para exportar en archivo MS-Excel

define('TPL_ADMIN', 'templates/admin_pml/main');            //Vista plantilla del front
define('TPL_FRONT', 'templates/polo/main_v');               //Vista plantilla del front

define('K_RCSK', '6LfwTtgUAAAAAFQYAKdRfPMsTYWHw5SAgX2gwUda');   //ReCaptcha V3 SiteKey
define('K_RCSC', '6LfwTtgUAAAAAJjKiRm-UTwhA_MnDXpAY_Zm4089');   //ReCaptcha V3 Clave Secreta

define('K_FBAI', '629784611201413');                            //Facebook App ID
define('K_FBAK', '9062741e64c3cbe8b9ddb91d2d6921d2');           //Facebook App Secret Key