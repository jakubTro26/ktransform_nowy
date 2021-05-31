<?php

error_reporting(E_ERROR);
ini_set('display_errors', true);
ini_set('display_error', true);

require dirname(__FILE__) . '/../../vendor/autoload.php';

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'vendor/');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'app/');

require dirname(__FILE__) . '/autoload.php';

class MyConfig {

	//database
	static protected $dbPrefix			= "planning_"; // "prefix_"
	static protected $dbHost			= "localhost";
	static protected $dbLogin			= "cryptoexpo18";
	static protected $dbDatabase		= "cryptoexpo18";
	static protected $dbPass			= "xwe12xcc";
	static protected $dbPort			= 3307;
	
	static protected $memcacheTime		= 3600;
	static protected $adminPass		    = 'admin2016!';
	static protected $adminLogin		= 'admin';

	static protected $hashSecret		= '@#$fs23rrfr';

	static protected $smtpHost		= 'mail.atcomm.pl';
	static protected $smtpPort		= '587';
	static protected $smtpLogin		= 'rezerwacje@pureexpo.pl';
	static protected $smtpPass		= 'atccal123';
	static protected $smtpFrom_email	= 'tickets@pureexpo.pl';
	static protected $smtpFrom_name		= 'Crypto Future Expo';

    static protected $smtpHost_career		= 'mail.atcomm.pl';
    static protected $smtpPort_career		= '587';
    static protected $smtpLogin_career		= 'tickets@pureexpo.pl';
    static protected $smtpPass_career		= 'tickex431';
    static protected $smtpFrom_email_career	= 'tickets@pureexpo.pl';
    static protected $smtpFrom_name_career	= 'Crypto Future Expo';

    static protected $payU_merchant	    = '874444';
    static protected $payU_signature	= '4b47d6530d8fc4f423e52ba2e9f149ba';
    static protected $payU_oAuthId	    = '874444';
    static protected $payU_oAuthSecret	= '33c6d350ce49f4bcb308157cbfedae54';

	static public function getValue($string) {

		if (isset(self::$$string)) {
			return self::$$string;
		}else{
			return "Błąd konfiguracji dla zmiennej: ".$string." z klasy: ".get_class();
		}
	}
}
