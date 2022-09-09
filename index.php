<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
ini_set('memory_limit', '8000M');
ini_set('max_execution_time', 3000000);
ini_set('post_max_size', '60M');

require_once "sql.php";
require 'vendor/autoload.php';
require_once "Book.class.php";

$bookObject = new Book();
$book = $bookObject->bookData();
$bookCompanies = $bookObject->bookCoompaniesData();


$mpdf = new \Mpdf\Mpdf(['format' => 'B5', 'mirrorMargins' => true]);


$mpdf->setFooter('||{PAGENO}');


$mpdf->WriteHTML('<style> .ps {
                         width: 100%;
                      text-align: center;
                      font-family: Arial, sans-serif;
                       }
                       .h1 {
                       padding-top: 100px;
                       font-size: 10mm;
                       }    
                       .h2 {
                       font-size: 8mm;
                        margin-top: 4mm;
                        margin-bottom: 0;
                       }
                       .h3 {
                       margin-top: 2mm;
                        font-size: 12mm;
                      
                       }
                       .h10 {
                       font-size: 3mm;
                       text-align: left;
                       position: fixed;
                       left: 0px;
                       bottom: 0px;
                       display: block;
                       }
                       </style>');


$mpdf->WriteHTML('<p class="ps h1">KSIĄŻKA TELEFONICZNA</p>');

$mpdf->WriteHTML('<p class="ps h3">2022 - 2026</p>');

$mpdf->WriteHTML('<pagebreak $sheet-size="B5" margin-left="15mm" margin-right="10mm" margin-top="10mm" resetpagenum="0" pagenumstyle="1" suppress="off" />');
$mpdf->WriteHTML('<div class="ps h10">Reklamacje: xxx<br /><br />W niniejszej książce telefonicznej zostały umieszczone tylko dane osób, które wyraziły na to zgodę.<br />Dane zawarte w tej publikacji nie mogą być reprodukowane w całości ani w części. Przetwarzanie, kopiowanie w jakiejkolwiek formie bez zgody wydawcy - zabronione. 
                    <br /><br />Biuro czynne w godz. 12-14 w dni powszednie. Tel: xxx.
                              <br /><br />07.2022         
                        </div>');
$mpdf->WriteHTML('<pagebreak $sheet-size="B5" margin-left="15mm" margin-right="10mm" margin-top="10mm" resetpagenum="0" pagenumstyle="1" suppress="off" />');

$mpdf->WriteHTML('<style>
                                        @page {
                                            margin: 10mm 10mm 10mm 15mm;
                                        }
                                          body {
                                            font-family: "Arial";
                                            font-size: 10pt;
                                          }
                                        td.naglowek {
                                            font-size: 9pt;
                                            text-transform: uppercase;
                                            float: left;
                                            color: #fff;
                                            background-color: #000;
                                            padding-left: 1.5pt;
                                            padding-top: 1.5pt;
                                            padding-bottom: 1.5pt;
                                            page-break-inside:avoid;
                                        }
                                        .wpis td {
                                            font-size: 7pt;                                         
                                            border-bottom: 1px solid #000;
                                            vertical-align: bottom;
                                        }     
                                        .lewa {                                    
                                            padding-right: 1pt;
                                        }
                                        .prawa {                               
                                            white-space: nowrap;
                                            text-align: right;
                                        }
                                        .nazwisko {
                                            text-transform: uppercase;
                                            font-weight: bold;
                                        }
                                        .imie {
                                            font-weight: bold;
                                        }
                                        .wpis td .ulica {
                                            font-size: 5pt;
                                        }
                                        .numer {
                                    
                                        }
                                        .mh {
                                            text-align: center;
                                            width: 100%;
                                            text-transform: uppercase;
                                            float: left;
                                            color: #fff;
                                            background-color: #000;
                                            padding-left: 1.5pt;
                                            padding-top: 1.5pt;
                                            padding-bottom: 1.5pt;
                                        }
                                </style>');


$mpdf->SetColumns(1, "j", 0);
$mpdf->WriteHTML('<div class="mh">FIRMY, SZPITALE, INSTYTUCJE</div>');
$mpdf->SetColumns(2, "j", 1);

$mpdf->WriteHTML('<table cellspacing="0" style="overflow: wrap;"><tbody >');
$cityBuffer = "";

foreach ($bookCompanies as $k => $wpis) {

    $wpis['miejscowosc'] = preg_replace('/^\s*[0-9]{2}-[0-9]{3}\s*/', '', $wpis['miejscowosc']);
    $wpis['miejscowosc'] = preg_replace('/^\s*[0-9]{2} [0-9]{3}\s*/', '', $wpis['miejscowosc']);
    $wpis['miejscowosc'] = preg_replace('/^\s*[0-9]{1} \s*/', '', $wpis['miejscowosc']);
    if (mb_strtolower($cityBuffer) != mb_strtolower($wpis['miejscowosc'])) {

        $mpdf->WriteHTML('<tr style="page-break-inside:avoid;"><td class="naglowek" colspan="2"><span class="miejscowosc">' . $wpis['miejscowosc'] . '</span></td></tr>');

    }
    $cityBuffer = $wpis['miejscowosc'];
    ob_start();
    include "template/book.php";
    $myvar = ob_get_clean();
    $mpdf->WriteHTML($myvar);

}


$mpdf->WriteHTML('</tbody></table>');


ob_clean();
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'utf-8';
$mpdf->SetColumns(1, "j", 0);

$mpdf->WriteHTML('<div class="mh">ABONENCI PRYWATNI</div>');

$mpdf->SetColumns(3, "j", 1);

$mpdf->WriteHTML('<table cellspacing="0" style="overflow: wrap;"><tbody >');


$cityBuffer = "";

foreach ($book as $k => $wpis) {

    if (mb_strtolower($cityBuffer) != mb_strtolower($wpis['miejscowosc'])) {


        $mpdf->WriteHTML('<tr style="page-break-inside:avoid;"><td class="naglowek" colspan="2"><span class="miejscowosc">' . $wpis['miejscowosc'] . '</span></td></tr>');


    }
    $cityBuffer = $wpis['miejscowosc'];
    ob_start();
    include "template/book.php";
    $myvar = ob_get_clean();
    $mpdf->WriteHTML($myvar);

}

$mpdf->WriteHTML('</tbody></table>');

ob_clean();

$daten = new DateTime();

$mpdf->Output('ksiazka_' . $daten->getTimestamp() . '.pdf', 'F');


function mb_startsWith($str, $prefix, $case_sensitivity = false)
{
    if ($case_sensitivity) {
        return mb_substr($str, 0, mb_strlen($prefix)) === $prefix;
    } else {

        foreach ($prefix as $k => $numer) {
            if (mb_strtolower(mb_substr($str, 0, mb_strlen($numer))) === mb_strtolower($numer)) {
                return true;
            }
        }

    }
}