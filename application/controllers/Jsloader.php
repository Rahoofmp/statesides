<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Jsloader extends Base_Controller {

    function __construct() {
        parent::__construct();
    }  

    public function file($file = null){
        if (!$file) {
            header('HTTP/1.1 404 Not Found');
            return;
        }

        $contents = read_file('./assets/js/language/' . $file);

        if (!$contents) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        $contents = $this->parse_variables($contents, $file);
        
        $this->output
        ->set_content_type('text/javascript')
        ->set_output($contents);
    }

    private function parse_variables($text, $file) {

        $file = explode('_', $file);
        unset($file[0]);
        $language = implode('_', $file);

        $info = new SplFileInfo($language);

        $file = $info->getBasename('.'.$info->getExtension()); 
        
        $lang_details = $this->Base_model->getLanguageDetails($this->software->LANG_ID);

        $this->lang->load($file, $lang_details['directory']);

        preg_match_all("/\[@{0,1}[a-zA-Z0-9_]+[\s]*[a-zA-Z0-9_]*\]/",$text, $matches, PREG_PATTERN_ORDER); 
        
        $script = ' '; 

        foreach ($matches[0] as $match) {

            $lang_key = str_replace('[', '', $match);
            $lang_key = str_replace(']', '', $lang_key);

            $var = ' var '.$lang_key . ' = "' . lang($lang_key) .'";';
            
            $lang_value = lang($lang_key); 
            $script .= $var;
        } 

        return $script;
    }   

}
