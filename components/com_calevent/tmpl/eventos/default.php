<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Component\Calevent\Site\Helper\CaleventHelper;

//$wa = $this->document->getWebAssetManager();
//$wa->useStyle('com_calevent.styles');
$input = new JInput;
$session = Factory::getSession();

$document = Factory::getDocument();

$param = ComponentHelper::getParams('com_calevent');
$show_sigla = $param->get('sigla');
$show_link_sotto = $param->get('link_nomi');

?>

<div class="com-calevent view-eventos <?php echo $this->pageclass_sfx; ?>">
    <style>
     .tbmese {
        border:solid 1px #ccc;
    }
    .tbmese td {
        width: 45px;
        border:solid 1px #ccc;
    }

    .mese {
        width: 50px;
    }
    .anno {
        width: 50px;
    }
    .giorno{
      text-align:center;

    }
    #ng {
    padding-left: 25%;
    padding-right: 25%;
}

.giornol{
      text-align:left;

    }
    #ngl {
    padding-left: 0%$colorear0%;
}

a:link {
  text-decoration: none;
}
.com-calevent.view-eventos {
    max-width: 300px;
}
    </style>


    <?php

$prelink = "index.php?option=com_content&view=article&id=";

$y = $session->get('anno');
$m = $session->get('mese');
$set_inizio = 0;
$link = 0;
$titolo;
$colorear = array();
$titoli = array();
$links = array();
$coeve = 0;

$setMese = $session->get('mese');
if ($setMese === null) {
    $session->set('mese', date('m'));
} else {

}

$setAnno = $session->get('anno');
if ($setAnno === null) {
    $session->set('anno', date('Y'));
} else {

}

$nomi_mesi = array(
    "",
    "Gen",
    "Feb",
    "Bes",
    "Apr",
    "Mag",
    "Unt",
    "Jul",
    "Nad",
    "Sat",
    "Okt",
    "Nov",
    "Dez",
);

//CaleventHelper::ShowCalendar($m,$y);

$post = $input->getArray($_POST);
if (isset($post["precedente"]) && $post["precedente"] != "") {
    $precedente = mktime(0, 0, 0, $m - 1, 1, $y);
    $m = (int) strftime("%m", $precedente);
    $y = (int) strftime("%Y", $precedente);
    $session->set('mese', $m);
    $session->set('anno', $y);
} elseif (isset($post["sucessivo"]) && $post["sucessivo"] != "") {
    $successivo = mktime(0, 0, 0, $m + 1, 1, $y);
    $m = (int) strftime("%m", $successivo);
    $y = (int) strftime("%Y", $successivo);
    $session->set('mese', $m);
    $session->set('anno', $y);
} else {
    $m = date('n');
    $y = date('Y');
}

?>

<form name="form5" method="post" enctype="multipart/form-data" class="form-control-sm needs-validation">
    <div class="input-group">
        <button type="submit" name='precedente' value="p" class="btn btn-primary btn-sm cerca"><<</button>
       <?php
if ($m == "") {
    $m = date('n');
}
if ($m < 10) {
    $m = substr($m, 0, 1);
} else {
    $m = $m;
}?>
        <input type="text" id="form1" name="mese" value="<?php echo $nomi_mesi[$m] ?>" class="mese" readonly/>
        <input type="text" id="form1" name="anno" value="<?php echo $y ?>" class="anno" />
        <button type="submit" name='sucessivo' value="s" class="btn btn-primary btn-sm cerca">>></button>
    </div>
</form>
<?php

$nomi_giorni = array(
    "Mo",
    "Bes",
    "Hei",
    "Don",
    "Fr",
    "Sa",
    "Son",
);

$cols = 7;
$days = date("t", mktime(0, 0, 0, $m, 1, $y));
$lunedi = date("w", mktime(0, 0, 0, $m, 1, $y));
if ($lunedi == 0) {
    $lunedi = 7;
}

echo "<table class='tbmese'>\n";

foreach ($nomi_giorni as $v) {
    echo "<td><b>" . $v . "</b></td>\n";
}
echo "</tr>";

for ($j = 1; $j < $days + $lunedi; $j++) {
    if ($j % $cols + 1 == 0) {
        echo "<tr>\n";
    }

    if ($j < $lunedi) {
        echo "<td> </td>\n";
    } else {
        $day = $j - ($lunedi - 1);
        if ($day < 10) {
            $dayd = '0' . $day;
        } else {
            $dayd = $day;
        }
        if ($m < 10) {
            $mm = '0' . $m;
        } else {
            $mm = $m;
        }
        $datad = date($y . "-" . $mm . "-" . $dayd);
        $data = strtotime(date($y . "-" . $m . "-" . $day));
        $oggi = strtotime(date("Y-m-d"));

        $inizio = CaleventHelper::getInizio($datad);

        if ($set_inizio == 0) {
            if (isset($inizio[0]->data_inizio)) {
                $iniziod = $inizio[0]->data_inizio;
                //$fined = $inizio[0]->data_fine;
                $set_inizio = 1;
                $link = $inizio[0]->id_link;
                $sig = $inizio[0]->sigla;
                $colore = $inizio[0]->colore;
                $colorear[$coeve] = $colore;
                $titolo = $inizio[0]->title;
                $titoli[$coeve] = $titolo;
                $links[$coeve] = $link;
                $coeve ++;
                //$colori .=$colore;
                $fined = CaleventHelper::getFine($iniziod);
                //$fined = date('Y-m-d',strtotime($fined."+1 days"));
                //$fined = date('Y-m-d', strtotime('-1 day', strtotime($fined1)));
            }
        }

        if ($set_inizio == 1) {
            if ($datad == $fined) {
                $set_inizio = 0;
            }
            if ($show_sigla == 1) {
            //$link2 = "<a  href=" . $prelink . $link . ">" . $sig . "</a>";
            $link2 = "<a  data-toggle='tooltip' title='".$titolo."'  class='linksp'  href=" . $prelink . $link . "><span id='ngl'>" . $day . "</span>".  $sig . " </a>";
            echo "<td class='giornol' style='background:".$colore."'>". $link2 . "</td>";
          } else {
            $spa = ' ';

            $link2 = "<a  class='giornol'  href=" . $prelink . $link ."   data-toggle='tooltip' title='".$titolo."'  ><span id='ng'>" . $day ."</span> </a>";
            echo "<td class='giorno' style='background:".$colore."'>". $link2 . "</td>";
          }
                
            
        } else {
            $sig = '';
            $colore = '#fff';
            $link = '';
            echo "<td>" . $day . "</td>";
        }

    }

    if ($j % $cols == 0) {
        echo "</tr>";
    }
}
echo "<tr></tr>";
echo "</table>";

?>

<?php 
if ($show_link_sotto == 1) {
    $cont = count($titoli);
    for ($i = 0;$i < $cont;$i++) {
        echo '<a href="'.$prelink.$links[$i].'"><span style="color:'.$colorear[$i].'">'. $titoli[$i].'</span> </a><br>';
    }
}?>



    <div class="jext-cli-footnote"
        style="min-height: 80px;background: #ffffff;margin-top: 100px;display: flex;flex-direction: column;justify-content: center;align-items: center;border-radius: 10px;box-shadow: 1px 1px 2px 0px #2222225c;font-family: 'helvetica', sans-serif;font-size: 14px;color: #757575;">
        <p style="margin: 0;">Powered by <a href="https://github.com/ahamed/jext-cli"
                style="text-decoration: none;"><code>JEXT-CLI</code></a>, Developed by <a
                href="https://ahamed.github.io" style="text-decoration: none;">Sajeeb Ahamed</a></p><a
            href="https://ahamed.github.io" style="text-decoration: none;"><small>&copy; 2022, Sajeeb Ahamed</small></a>
    </div>
</div>
