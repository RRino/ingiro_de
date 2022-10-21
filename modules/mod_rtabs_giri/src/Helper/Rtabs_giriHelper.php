<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_rtabs_giri
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rtabs_giriNamespace\Module\Rtabs_giri\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
$document = Factory::getDocument ();
$session = Factory::getSession (); 
/**
 * Helper for mod_rtabs_giri
 *
 * @since  __BUMP_VERSION__
 */
class Rtabs_giriHelper
{
	/**
	 * Retrieve rtabs_giri test
	 *
	 * @param   Registry        $params  The module parameters
	 * @param   CMSApplication  $app     The application
	 *
	 * @return  array
	 */
	public static function getText()
	{
		return 'Rtabs_giriHelpertest';
	}
 
  
    public static function get_articles_category($catid,$maxart){
    	//$date = Factory::getDate()->format('Y-m-d H:i:s');
    	//$result = null;
    	$db = Factory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('id,title,introtext,publish_down') // add other fields that might interest you
    	->from('#__content')
    	->where($db->quoteName('catid')."=". $db->quote($catid)." AND ".$db->quoteName('state')."=". $db->quote(1))
    	->setLimit($maxart);
    	$query->order('ordering ASC');
    	// $query->order("id ASC");
    	try {
    		$db->setQuery($query);
    		//$result = $db->loadObjectList();
    		$result = $db->loadAssocList();
    		//$result = $db->loadColumn();
    	}
    	catch(RuntimeException $e){
    		echo $e->getMessage();
    	}
    	

    	return $result;
    }
    
    public static function get_text_article($id){
        $articleId = $id;
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
        ->from('#__content')
        ->where($db->quoteName('id')."=". $db->quote($id));
        $db->setQuery($query);
        try {
            $db->setQuery($query);
            $results = $db->loadObject();
            //$results = $db->loadResult();
        }
        catch(RuntimeException $e){
            echo $e->getMessage();
        }
        
        $partArticle = $results->fulltext;
        return $partArticle;
    	
    }
    
    public static function get_article_introtext($id){
    	$articleId = $id;
    	$db =Factory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('introtext')
    	->from('#__content')
    	->where($db->quoteName('id')."=". $db->quote($id));
    	$db->setQuery($query);
    	try {
    	    $db->setQuery($query);
    	    //$results = $db->loadObject();
    	    $results = $db->loadResult();
    	}
    	catch(RuntimeException $e){
    	    echo $e->getMessage();
    	}

    	return $results;
    }
    
    public static function get_article_image($id){
    	$articleId = $id;
    	$db =Factory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('introtext')
    	->from('#__content')
    	->where('id = '. $db->Quote($id));
    	$db->setQuery($query);
    	//$db->execute("SET NAMES 'utf8'");
    	
    	$fullArticle = $db->loadResult();
    	
    	preg_match('/<img[^>]+>/i',$fullArticle, $testor); 
    	
    	//preg_match('/<img (.*?)>/', $fullArticle, $testor);
    	//$intro_image = json_decode($testor[0]);
    	if (isset ( $testor[0] )) {
    		$imag = $testor[0];
    	} else {
    		$imag="";
    	}
    	return $imag;

    }
   
    public static function get_fulltext_noimage($id){
        $articleId = $id;
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
        ->from('#__content')
        ->where('id = '. $db->Quote($id));
        $db->setQuery($query);
        try {
            $db->setQuery($query);
            $results = $db->loadObject();
            //$result = $db->loadResult();
        }
        catch(RuntimeException $e){
            echo $e->getMessage();
        }
        
        $clear = strip_tags($results->fulltext, '');
        // Clean up things like &amp;
        $clear = html_entity_decode($clear);
        // Strip out any url-encoded stuff
        $clear = urldecode($clear);
        // Replace non-AlNum characters with space
        $clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
        // Replace Multiple spaces with single space
        $clear = preg_replace('/ +/', ' ', $clear);
        // Trim the string of leading/trailing space
        $fullArticle= trim($clear);
        
        if(!strlen(trim($fullArticle))) $fullArticle = "No Titolo ";
        $partArticle = substr($fullArticle,0,240);
        
        return $partArticle;
    }
    public static function get_text_title($id){
    	$articleId = $id;
    	$db =Factory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('title')
    	->from('#__content')
    	->where('id = '. $db->Quote($id));
    	$db->setQuery($query);
    	//$db->execute("SET NAMES 'utf8'");
    	
    	$fullArticle = $db->loadResult();
    	
    	$clear = strip_tags($fullArticle, '');
    	// Clean up things like &amp;
    	$clear = html_entity_decode($clear);
    	// Strip out any url-encoded stuff
    	$clear = urldecode($clear);
    	// Replace non-AlNum characters with space
    	$clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
    	// Replace Multiple spaces with single space
    	$clear = preg_replace('/ +/', ' ', $clear);
    	// Trim the string of leading/trailing space
    	$fullArticle= trim($clear);
    	
    	if(!strlen(trim($fullArticle))) $fullArticle = "No Titolo ";
    	$partArticle = substr($fullArticle,0,240);
    	
    	return $partArticle;
    }
    
    public static function get_article_data($id){
    	$date = Factory::getDate()->format('d-m-Y');
    	$db =Factory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('created')
    	->from('#__content')
    	->where('id = '. $db->Quote($id));
    	$db->setQuery($query);

    	$data = $db->loadResult();
    	/*$data = date_create($data);
    	$it_data = date_format($data, 'd-m-Y');*/
    	
    	$temp1 = explode(" ", $data);
    	$temp2 = explode("-", $temp1[0]);
    	$data_formattata = $temp2[2]."/".$temp2[1]."/".$temp2[0];
    	/*echo $data_formattata;  // stampa 31/05/2012*/
    	
    	return $data_formattata;
    }
   
    public static function get_preimage($catid){
    	$db =Factory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('images')
    	->from('#__content')
    	->where('catid = '. $db->Quote($id));
    	$db->setQuery($query);
    	//$db->execute("SET NAMES 'utf8'");
    	try {
    	    $db->setQuery($query);
    	    $result = $db->loadResult();
    	    $intro_image = json_decode($result)->image_intro;
    	}
    	catch(RuntimeException $e){
    	    echo $e->getMessage();
    	}
    	
    	return $intro_image;
    }

    /* testo dell'evento */
    public static function get_id_testo($id,$txt){
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
        ->from('#__content')
        ->where($db->quoteName('state')."=". $db->quote('1').' AND '.$db->quoteName('catid')."=". $db->quote($id));
        $db->setQuery($query);
        $query->order("ordering  ASC");
        try {
            $db->setQuery($query);
            $result = $db->loadRowList();
        }
        catch(RuntimeException $e){
            echo $e->getMessage();
        }
        
        return $result;
    }
    /* testo dell'evento */
    public static function get_testo_intro($id,$txt){
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('introtext')
        ->from('#__content')
        ->where($db->quoteName('id')."=". $db->quote($id));
        $db->setQuery($query);
        $query->order("modified  asc");
        $fullArticle = $db->loadResult();
        
        
        $clear = $fullArticle;
        //pulisce i tag
        $clear = strip_tags($clear, '');
        $clear = html_entity_decode($clear);
        $clear = urldecode($clear);
        $clear = preg_replace('/ +/', ' ', $clear);
        $clear= trim($clear);
        $fullArticle = $clear;
        
        if(!strlen(trim($fullArticle))) $fullArticle = "";
        $partArticle = substr($fullArticle,0,$txt);
        return $partArticle.' ....<br>';
        
    }
    
	public static function get_testo_intro_nop($id,$txt){
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('introtext')
        ->from('#__content')
        ->where($db->quoteName('id')."=". $db->quote($id));
        $db->setQuery($query);
        $query->order("modified  asc");
        $fullArticle = $db->loadResult();
        
        
        $clear = $fullArticle;
        //pulisce i tag
        $clear = strip_tags($clear, '');
        $clear = html_entity_decode($clear);
        $clear = urldecode($clear);
        $clear = preg_replace('/ +/', ' ', $clear);
        $clear= trim($clear);
        $fullArticle = $clear;
        
        if(!strlen(trim($fullArticle))) $fullArticle = "";
        $partArticle = substr($fullArticle,0,$txt);
        return $partArticle;
        
    }

    
    public static function get_text_article_completo($id){
        $articleId = $id;
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('introtext')
        ->from('#__content')
        ->where($db->quoteName('id')."=". $db->quote($id));
        $db->setQuery($query);
        //$query->order("modified  asc");
        
        $fullArticle = $db->loadResult();
       
        //$clear = strip_tags($clear, '<br><div></div><h2></h2><p></p><hr /><h2></h2><span></span>&nbsp;<img />');
        
        return $id.' '.$fullArticle;
    }

    public static function get_testo_intro_banner($id){
        $db =Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('introtext')
        ->from('#__content')
        ->where($db->quoteName('id')."=". $db->quote($id));
        $db->setQuery($query);
        $query->order("modified  asc");
        $fullArticle = $db->loadResult();
        $fullArticle = strip_tags($fullArticle, '<span><a>');

       
        return $fullArticle;
    
        
    }

		
}
