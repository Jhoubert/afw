<?php // VEFLAT 0.8V

ini_set("display_errors",1);
if (!is_writable(session_save_path())) {
	session_save_path ("veflat/sessions/");
		if (!is_writable(session_save_path())) {
		die ( '<br><b>AFW-ERROR:</b> No se puede guardar las sesiones en <b>"'.session_save_path().'"</b> ni en el directorio definido por defecto! ');
	}
}

// USAR PROTOCOLO HTTPS O HTTP
define('FORCE_PROTOCOL',false);
define('PROTOCOL',"http");

// MYSQL CONFIG.
define('MySQL_U', 'modernizacion');
define('MySQL_P', '1q2w3e');
define('MySQL_S', 'localhost');
define('MySQL_DB', 'denwa');

//USAR MULTI IDIOMAS / lang
define('USE_LANG',False);
define('LANG','es');

//SITE CONFIG
define('URL',PROTOCOL.'://dev.veflat.com.ve/');

// PATH CONFIG.
define('MODULOS', 'modulos/');
define('VISTAS', 'vistas/');
define('MODALES', 'vistas/modales/');
define('LOGS', 'veflat/');
define('PLUGINS', 'plugins/');

//SEGURIDAD
define('SECURITY_SALT','AlienFastWork'); //Concatenar con clave para MD5

// Otras configuraciones.
define('HORA_DEFAULT','12h');
define('TIME_ZONE_DEFAULT','America/Caracas');
define('EDITABLE',true);
define('FULLROWS',true);
define('DEFAULT_LIMIT_PAGINATION',30);

//LOGS
define('MYSQL_LOG', LOGS.'mysql.log');
define('ERROR_LOG', LOGS.'error.log');
define('WARNING_LOG', LOGS.'warning.log');
define('CORE_LOG', LOGS.'core.log');
define('PLUGIN_LOG', LOGS.'plugin.log');

//archivos definidos.
define('LOGIN',"login.html");         // Direccion del formulario de login.
define('ACTUALIZADO',"?actualizado"); // esta constante es el $this->okRedir por defecto al actualizar datos.
define('INSERTADO',"?insertado");
define('REGISTRO',"register.php");
define('ACTUALIZADO_MSG',"Se ha actualizado la informacion correctamente");
define('INSERTADO_MSG',"Se ha guardado la informacion correctamente");

//Archivos importantes.
define('INDEX_MODULO', MODULOS.'inicio.php');
define('ESTRUCTURA',VISTAS.'_estructura.php');

//EMAIL DEL ADMINISTRADOR
define("ADMIN", "admin@veflat.com.ve");
define('EMAIL','contacto@veflat.com.ve');
define("DOMINIO", $_SERVER['SERVER_NAME']);
define("RUTA_BASE", URL);
define("NOMBRE_EMAIL", 'OIL Technical Solutions');
define("NOMBRE_COMPANY", 'OIL Technical Solutions');
define("RUTA_LOGO", URL.'/images/logo.png');
define("EMAIL_GRACIAS", 'Gracias, el equipo de cuentas de '.NOMBRE_COMPANY.'.  Enviado desde '.DOMINIO.'');
DEFINE("CSS_EMAIL",'<style type="text/css">/* CLIENT-SPECIFIC STYLES */#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer *//* RESET STYLES */body{margin:0; padding:0;}img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}table{border-collapse:collapse !important;}body{height:100% !important; margin:0; padding:0; width:100% !important;}/* iOS BLUE LINKS */.appleBody a {color:#68440a; text-decoration: none;}.appleFooter a {color:#999999; text-decoration: none;}/* MOBILE STYLES */@media screen and (max-width: 525px) {/* ALLOWS FOR FLUID TABLES */table[class="wrapper"]{width:100% !important;}/* ADJUSTS LAYOUT OF LOGO IMAGE */td[class="logo"]{text-align: left;padding: 20px 0 20px 0 !important;}td[class="logo"] img{margin:0 auto!important;}/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */td[class="mobile-hide"]{display:none;}img[class="mobile-hide"]{display: none !important;}img[class="img-max"]{max-width: 100% !important;height:auto !important;}/* FULL-WIDTH TABLES */table[class="responsive-table"]{width:100%!important;}/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */td[class="padding"]{padding: 10px 5% 15px 5% !important;}td[class="padding-copy"]{padding: 10px 5% 10px 5% !important;text-align: left !important;}td[class="padding-meta"]{padding: 30px 5% 0px 5% !important;text-align: center;}td[class="no-pad"]{padding: 0 0 20px 0 !important;}td[class="no-padding"]{padding: 0 !important;}td[class="section-padding"]{padding: 10px 15px 10px 15px !important;}td[class="section-padding-bottom-image"]{padding: 50px 15px 0 15px !important;}/* ADJUST BUTTONS ON MOBILE */td[class="mobile-wrapper"]{  padding: 10px 5% 15px 5% !important;}table[class="mobile-button-container"]{margin:0 auto;width:100% !important;}a[class="mobile-button"]{width:90% !important;padding: 15px !important;border: 0 !important;font-size: 16px !important;}}</style></head><body style="margin: 0; padding: 0;">');
if(FORCE_PROTOCOL){
    if(PROTOCOL == "https" && !((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)){header("location:".URL);}
    elseif(PROTOCOL == "http" && ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)){header("location:".URL);}
}



include('veflat/jhou.php');
/*echo "a";*/include('veflat/nork.php');
/*echo "b";*/include('veflat/sesiones.php');
/*echo "c";*/include('veflat/alien.class.php');
/*echo "d";*/include('veflat/mysql.php');
/*echo "e";*/include('veflat/veflat_functions.php');
/*echo "f";*/include('veflat/core.php');
date_default_timezone_set(TIME_ZONE_DEFAULT);
include('veflat/_init.php');
?>
