<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['twig']['template_dir'] = VIEWPATH;
$config['twig']['template_ext'] = 'twig';
$config['twig']['environment']['autoescape'] = TRUE;
$config['twig']['environment']['cache'] = FALSE;
$config['twig']['environment']['debug'] = FALSE;
$config['twig']['functions_asis'] = [
	'base_url', 'site_url', 'lang', 'assets_url', 'value_by_key', 'log_user_id', 'admin_user_id', 'log_user_type', 'log_user_name', 'theme_folder','log_customer_id'
];
$config['twig']['functions_safe'] = [
	'form_open', 'form_close', 'form_error', 'set_value', 'form_hidden'
];