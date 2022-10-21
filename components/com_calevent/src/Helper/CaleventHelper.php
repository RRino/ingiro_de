<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, INGIRO
 * @license     MIT
 */

namespace Joomla\Component\Calevent\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\File;

\defined('_JEXEC') or die('Restricted Direct Access!');

class CaleventHelper extends ContentHelper
{

    public static function test(){
        return 'ok';
    }

    public static function conv_data($it, $en)
    {
        if ($it != '' && $en == 0) {
            return substr($it, 8, 2) . '-' . substr($it, 5, 2) . '-' . substr($it, 0, 4);
        }
    }







    public static function ShowCalendar($m,$y)
{
  if ((!isset($_GET['d']))||($_GET['d'] == ""))
  {
    $m = date('n');
    $y = date('Y');
  }else{
    $m = (int)strftime( "%m" ,(int)$_GET['d']);
    $y = (int)strftime( "%Y" ,(int)$_GET['d']);
    $m = $m;
    $y = $y;
  }
 
  $precedente = mktime(0, 0, 0, $m -1, 1, $y);
  $successivo = mktime(0, 0, 0, $m +1, 1, $y);
 
  $nomi_mesi = array(
    "Gen",
    "Feb",
    "Mar",
    "Apr",
    "Mag",
    "Giu", 
    "Lug",
    "Ago",
    "Set",
    "Ott",
    "Nov",
    "Dic"
  );
  $nomi_giorni = array(
    "Lun",
    "Mar",
    "Mer",
    "Gio",
    "Ven",
    "Sab",
    "Dom"
  );
 
  $cols = 7;
  $days = date("t",mktime(0, 0, 0, $m, 1, $y)); 
  $lunedi= date("w",mktime(0, 0, 0, $m, 1, $y));
  if($lunedi==0) $lunedi = 7;
  echo "<table>\n"; 
  echo "<tr>\n
  <td colspan=\"".$cols."\">
  <a href=\"?d=" . $precedente . "\">&lt;&lt;</a>
  " . $nomi_mesi[$m-1] . " " . $y . " 
  <a href=\"?d=" . $successivo . "\">&gt;&gt;</a></td></tr>";
  foreach($nomi_giorni as $v)
  {
    echo "<td><b>".$v."</b></td>\n";
  }
  echo "</tr>";
 
  for($j = 1; $j<$days+$lunedi; $j++)
  {
    if($j%$cols+1==0)
    {
    echo "<tr>\n";
    }
 
    if($j<$lunedi)
    {
    echo "<td> </td>\n";
    }else{
    $day= $j-($lunedi-1);
    if($day < 10){
     $dayd = '0'.$day;
    }
    if($m < 10){
     $mm= '0'.$m;
    }
    $datad = date($y."-".$mm."-".$dayd);
    $data = strtotime(date($y."-".$m."-".$day));
    $oggi = strtotime(date("Y-m-d"));
 
    $evento = CaleventHelper::getMese($datad);
    if (empty($evento)) {
     $dt =  '';
     $link = $day;
     $colore = '#fff';
    }else{
      $dt =  ' '.substr($evento[0]->title,0,2);
      $link = "<a href='#'>".$day.$dt."</a>";
      $colore = $evento[0]->colore;
    }
     
   
    if($data != $oggi)
    {
      echo "<td style='background:".$colore."'>".$link."</td>";
    }else{
     echo "<td><b>".$link."</b></td>";
    }
    }
 
    if($j%$cols==0)
    {
    echo "</tr>";
    }
  }
  echo "<tr></tr>";
  echo "</table>";
 
 
}

public static function getEventi()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__calevent_eventos');
        $query->where('id <> 9999');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public static function getInizio($data)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('data_inizio,id_link,colore,sigla,data_fine,title');
        $query->from('#__calevent_eventos');
        $query->where($db->quoteName('data_inizio') . "=" . $db->quote($data));
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public static function getFine($data_inizio)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('data_fine');
        $query->from('#__calevent_eventos');
        $query->where($db->quoteName('data_inizio') . "=" . $db->quote($data_inizio));
        $db->setQuery($query);
        $results = $db->loadResult();
        return $results;
    }




}