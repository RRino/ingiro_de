<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rtabs_giri
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Rtabs_giriNamespace\Module\Rtabs_giri\Site\Helper\Rtabs_giriHelper;

?>



<style>
/*.grid-mycontainer {
    display: grid;
 
    grid-template-columns: repeat(31, 1fr);
    grid-gap: 0px;
    background-color: #ffffff;
    padding: 10px;
    border-bottom: solid 1px #ccc;
}



.item0 {
    grid-column: 1/2;
    grid-row: 1 / 3;

}

.banner {
    text-align: center;
    margin: 0 auto;
    padding: 0px;

}

*/

* {margin:0;padding:0;border:0 none; position:relative;}
*,*:before,*:after {
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
body {
  width: 100%;
  /*min-height: 100vh;*/
 /* background: #f0f0f0;*/
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
  color: black;
  font-weight: 100;
}

/* The grid*/
.cardg {
			display: grid;
			grid-template-columns: repeat(8, 1fr);
			grid-template-rows: 2;
			grid-template-areas: "t-1 t-2 t-3 t-4 t-5 t-6 t-7 t-8" "ver ver ver ver ver ver ver ver";
}

/* & the labels go to... */
[for*='-1'] {
	grid-area: t-1;
}
[for*='-2'] {
	grid-area: t-2;
}
[for*='-3'] {
	grid-area: t-3;
}
[for*='-4'] {
	grid-area: t-4;
}

[for*='-5'] {
	grid-area: t-5;
}
[for*='-6'] {
	grid-area: t-6;
}
[for*='-7'] {
	grid-area: t-7;
}
[for*='-8'] {
	grid-area: t-8;
}

/* show each content */
:checked + label + article
 {
	grid-area: ver;
	z-index: 1;
}

/* Let´s do it nice & funny */
.card{
	max-width: 100%;
	width: 90%;
	margin: 4rem auto 0;
	 box-shadow: 0 3px 3px rgba(0,0,0,.3);*/
	 border: 1px solid rgba(0,0,0,.3);*/
	border: 0 none;
	overflow: hidden;
}
[class*='tab-'] {
	opacity: 0;
	max-width: 0;
	max-height: 1000px;
	display: flex;
	flex-direction: columns;
	flex-wrap: wrap;
	align-items: center;
	/*margin-top:300px;*/
}
:checked + label + [class*='tab-'] {
	background: #fff;
	opacity: 1;
	max-width: 100%;
	max-height: 100vh;
	animation: show 1s;
}
label {
	cursor: pointer;
	font-variant: small-caps;
	font-size: 1.5rem;
	line-height: 2;
	text-align: center;
	z-index: 1;
}
label:hover {
	background: rgba(0,0,0,.2);
}
input:not(checked) + label {
	background: rgba(0,0,0,.05);
}
input:checked + label {
	background: #fff;
}
.hide {
	display: none;
}
h1 {
	background: #333;
	color: #fff;
	box-shadow: 0 0 3px rgba(0,0,0,.2);
	border: 1px solid #000;
	text-align: center;
	line-height: 2;
	font-weight: 100;
	letter-spacing: 1vw;
}
section {

     margin-top:20px;
}
h2 {
	font-weight: 100;
}
img {
	max-width: calc(25%);
  height: auto;
}
p {
	margin-top: 1rem;
	line-height: 1.5;
}
a {color: #e81178;}

@keyframes show {
	0%, 20% {
		opacity: 0;
		max-height: 0;
	}
	100%{
		opacity: 1;
		max-height: 100vh;
	}
}

label {
    border-top: solid 1px #ccc;
    border-left: solid 1px #ccc;
    border-radius: 5px 5px 0 0;
}
article{
	
}
/*
.tab-1 {
	margin-top:100px;
}

.tab-2 {
	margin-top:500px;
}

.tab-3 {
	margin-top:10px;
}
.tab-4 {
	margin-top:450px;
}

.tab-5 {
	margin-top:10px;
}

.tab-6 {
	margin-top:10px;
}

.tab-7 {
	margin-top:120px;
}

.tab-8 {
	margin-top:10px;
}*/
</style>
<?php $articoli = Rtabs_giriHelper::get_articles_category(11,2);

$art1 = Rtabs_giriHelper::get_article_introtext(1);
?>

<!--Überblick Programm Unterkunft Leistungen Reisebegleitung Gästekommentare Anreise Anfrage-->


<div class='cardg'>
	
	<input class='hide' type="radio" id="tab-1" name="tractor" checked='checked'/>
	<label for='tab-1'>Überblick</label>
	<article class='tab-1'>
		<section class="ses-1">
		<h2>WWF History</h2>
			<p><?php 
			$art1 =  Rtabs_giriHelper::get_text_article_completo(1); 
			echo $art1;?></p>
		</section>
	</article>
	
	<input class='hide' type="radio" id="tab-2" name="tractor"/>
	<label for='tab-2'>Programm</label>	
	<article class='tab-2'>
		<section class="ses-2">
		<h2>WWF History</h2>
			<p><?php 
			$art10 =  Rtabs_giriHelper::get_text_article_completo(10); 
			echo $art10; ?></p>
		</section>
	</article>

	<input class='hide' type="radio" id="tab-3" name="tractor"/>
	<label for='tab-3'>Unterkunft</label>
	
	<article class='tab-3'>		
		<section>
		<p><?php echo  Rtabs_giriHelper::get_text_article_completo(8); ?></p>
		</section>
	</article>
	
	<input class='hide' type="radio" id="tab-4" name="tractor"/>
	<label for='tab-4'>Leistungen</label>
 
	<article class='tab-4'>		
   
		<section>
		<p><?php echo  Rtabs_giriHelper::get_text_article_completo(7); ?></p>
		</section>
	</article>
	</article>

	<input class='hide' type="radio" id="tab-5" name="tractor"/>
	<label for='tab-5'>Reisebegleitung</label>
 
	<article class='tab-5'>		
   
		<section>
		<p><?php echo  Rtabs_giriHelper::get_text_article_completo(3); ?></p>
		</section>
	</article>

	<input class='hide' type="radio" id="tab-6" name="tractor"/>
	<label for='tab-6'>Gästekommentare</label>
 
	<article class='tab-6'>		
   
		<section>
		<p><?php echo  Rtabs_giriHelper::get_text_article_completo(5); ?></p>
		</section>
	</article>


	<input class='hide' type="radio" id="tab-7" name="tractor"/>
	<label for='tab-7'>Anreise</label>
 
	<article class='tab-7'>		
   
		<section>
		<p><?php echo  Rtabs_giriHelper::get_article_introtext(2); ?></p>
		</section>
	</article>


	<input class='hide' type="radio" id="tab-8" name="tractor"/>
	<label for='tab-8'>Anfrage</label>
 
	<article class='tab-8'>		
   
		<section>
		<p><?php echo  Rtabs_giriHelper::get_article_introtext(4); ?></p>
		</section>
	</article>

	</div>

<?php ?>

