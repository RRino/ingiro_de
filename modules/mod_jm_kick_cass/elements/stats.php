<?php
/**
 * @version     1.5
 * @package     mod_jm_kick_cass
 * @copyright   Copyright (C) 2021. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @author      Maarten Blokdijk / www.kickstartcassiopeia.com
 */
use Joomla\CMS\Factory;

defined('JPATH_BASE') or die;
$app = Factory::getApplication();


				function build_table($array){
					// start table
					$html = '<div class=container><div class=row><div class=col><table id=stats class=table table-hover>';
					// header row
					$html .= '<tr>';
					foreach($array[0] as $key=>$value){
									$html .= '<th>' . htmlspecialchars(str_replace("_"," ",str_replace("cf_","",$key))) . '</th>';
							}
					$html .= '</tr>';

					// data rows
					foreach( $array as $key=>$value){
							$html .= '<tr>';
							foreach($value as $key2=>$value2){
									$html .= '<td>' . htmlspecialchars($value2) . '</td>';
							}
							$html .= '</tr>';
					}

					// finish table and return it

                    $html .= '</table></div></div></div>';
                   
                    
					return $html;
				}

				class JmodulesFormFieldStats extends JFormField
				{

					protected function getInput()
					{
						$db = Factory::getDbo();
						$id=$_GET['id'];
					

								$query = $db->getQuery(true)
									->select('DATE_FORMAT(jn_date,"%d-%m-%Y") as Date, jn_stats as Hits')
                                    ->from($db->quoteName('#__jn_kick_cass_stats'))
                                    ->WHERE ($db -> quoteName('jn_module_id')."=".$id)
                                    ->order('id DESC')
                                    ->setlimit(30);

                                $db->setQuery($query);
                               
								$results = $db->loadObjectList();
							
								echo build_table($results);
								
					}
				}
