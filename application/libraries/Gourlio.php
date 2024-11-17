<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gourlio {

	public $BOX = [];
	public $userID = null;
	public $userFormat = 'COOKIE';
	public $orderID = '';
	public $amountUSD = 0;
	public $period = 'NOEXPIRY';
	public $def_language = 'en';
	public $def_coin = 'bitcoin';
	public $para_1 = null;
	public $para_2 = null;
	public $redirect = '';

	public function __construct($config = array())
	{	
		empty($config) OR $this->initialize($config, FALSE);
		log_message('info', 'Gourlio Class Initialized');

	}

	public function initialize(array $config = array(), $reset = TRUE)
	{

		if( empty($config) ) {
			echo "Please initialize the basic config";
			die();
		}

		foreach ($config as $key => &$value)
		{
			if ($key[0] !== '')
			{ 
				$this->$key = $value; 
			}
		}

		return $this;
	}

	public function config_initialize( )
	{ 
		
		$CI =& get_instance();
		DEFINE("CRYPTOBOX_BASE_URL", $CI->config->base_url());
		
		DEFINE("CRYPTOBOX_PHP_FILES_PATH", CRYPTOBOX_BASE_URL."cryptoapi_php/lib/");  
		DEFINE("CRYPTOBOX_IMG_FILES_PATH", CRYPTOBOX_BASE_URL."assets/plugins/cryptoapi/images/"); 
		DEFINE("CRYPTOBOX_JS_FILES_PATH", CRYPTOBOX_BASE_URL."assets/plugins/cryptoapi/js/");

		DEFINE("CRYPTOBOX_LANGUAGE_HTMLID", "alang");
		DEFINE("CRYPTOBOX_COINS_HTMLID", "acoin");
		DEFINE("CRYPTOBOX_PREFIX_HTMLID", "acrypto_");

		require_once(FCPATH . "cryptoapi_php/lib/cryptobox.class.php" );


		// List of coins that you accept for payments
		$coins = array('bitcoin' );

		$all_keys = array(	"bitcoin" => array(	"public_key" => "51889AAFsYLKBitcoin77BTCPUBW2LS5I3BqbGsQLDzbxj1Dfd",  
			"private_key" => "51889AAFsYLKBitcoin77BTCPRVB7Z5G5R00Bqo9uVUQtRsJgK"),
		"speedcoin" => array(	"public_key" => "20116AA36hi8Speedcoin77SPDPUBjTMX31yIra1IBRssY7yFy", 
			"private_key" => "20116AA36hi8Speedcoin77SPDPRVNOwjzYNqVn4Sn5XOwMI2c"));

		// Re-test - all gourl public/private keys
		$def_coin = strtolower( $this->def_coin );
		if (!in_array($def_coin, $coins)) $coins[] = $def_coin;  
		foreach($coins as $v)
		{
			if (!isset($all_keys[$v]["public_key"]) || !isset($all_keys[$v]["private_key"])) die("Please add your public/private keys for '$v' in \$all_keys variable");
			elseif (!strpos($all_keys[$v]["public_key"], "PUB"))  die("Invalid public key for '$v' in \$all_keys variable");
			elseif (!strpos($all_keys[$v]["private_key"], "PRV")) die("Invalid private key for '$v' in \$all_keys variable");
			elseif (strpos(CRYPTOBOX_PRIVATE_KEYS, $all_keys[$v]["private_key"]) === false) 
				die("Please add your private key for '$v' in variable \$cryptobox_private_keys, file /lib/cryptobox.config.php.");
		}

		// Current selected coin by user
		$coinName = cryptobox_selcoin($coins, $def_coin);

		// Current Coin public/private keys
		$public_key  = $all_keys[$coinName]["public_key"];
		$private_key = $all_keys[$coinName]["private_key"];

		/** PAYMENT BOX **/

		$options = array(
		    "public_key"  	=> $public_key,	    // your public key from gourl.io
		    "private_key" 	=> $private_key,	// your private key from gourl.io
		    "webdev_key"  	=> "DEV1587G26ADEC64592F2DBG127525770", 			    // optional, gourl affiliate key
		    "orderID"     	=> $this->orderID,	// order id or product name
		    "userID"     	=> $this->userID ,	// unique identifier for every user
		    "userFormat"  	=> $this->userFormat,
		    "amount"   	  	=> 0,			    // product price in btc/bch/bsv/ltc/doge/etc OR setup price in USD below
		    "amountUSD"   	=> $this->amountUSD, // we use product price in USD
		    "period"      	=> $this->period, 	// payment valid period
		    "def_coin"      => $def_coin, 	// payment valid period
		    "coins"      	=> $coins, 	// payment valid period
		    "language"	  	=> $this->def_language    // text on EN - english, FR - french, etc
		); 
		// Initialise Payment Class
		$this->BOX = new Cryptobox ($options);

		return $options;

	}

	public function payment_initialize( $options )
	{ 

		$html = '<!DOCTYPE html>
		<html lang="en">
		<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<title>Payment Box</title>


		<!-- Bootstrap4 CSS - -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">   

		<!-- JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" crossorigin="anonymous"></script>
		<script src="'. CRYPTOBOX_JS_FILES_PATH .'support.min.js" crossorigin="anonymous"></script> 

		<!-- CSS for Payment Box -->
		<style>
		html { font-size: 14px; }
		@media (min-width: 768px) { html { font-size: 16px; } .tooltip-inner { max-width: 350px; } }
		.mncrpt .container { max-width: 980px; }
		.mncrpt .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
		img.radioimage-select { padding: 7px; border: solid 2px #ffffff; margin: 7px 1px; cursor: pointer; box-shadow: none; }
		img.radioimage-select:hover { border: solid 2px #a5c1e5; }
		img.radioimage-select.radioimage-checked { border: solid 2px #7db8d9; background-color: #f4f8fb; }
		</style>
		</head>

		<body>';
            // Text above payment box
		$custom_text  = "<p class='lead'>". $this->para_1 ."</p>";
		$custom_text .= "<p class='lead'>". $this->para_2 ."</p>";

            // Display payment box  
		
		$html .= $this->BOX->display_cryptobox_bootstrap( $options['coins'], $options['def_coin'], $this->def_language, $custom_text, 70, 200, true, "default", "default", 250, $this->redirect, "ajax", $options['debug']);
		$html .=  '</body>
		</html>';

		echo  $html;
	}
}
?>
