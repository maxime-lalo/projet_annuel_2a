<?php

require_once __DIR__ . "/../../../utils/fpdf/fpdf.php";
require_once __DIR__ . "/../../../repositories/CaRepository.php";
$CaRepo = new CaRepository();

class PDF extends FPDF
{
    private $title;
    function setHeaderTitle($title){
        $this->title = $this->convertText($title);
    }
    // En-tête
    function setHeader()
    {
        // Logo
        $this->Image(__DIR__ . '/../../../public/assets/img/about.png',5,11,30);
        // Police Arial gras 15
        $this->SetFont('Arial','B',15);
        // Décalage à droite
        $this->Cell(30);
        // Titre
        $this->Cell(130,10,$this->title,1,0,'C');
        // Saut de ligne
        $this->Ln(20);
    }

    function Br(){
        $this->Cell(0,10,"",0,1);
    }
    // Pied de page
    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial','I',8);
        // Numéro de page
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    function convertText($str){
        return iconv('UTF-8', 'windows-1252', $str);
    }
}
if (isset($_GET['dateBegin']) && isset($_GET['dateEnd'])){
    $cas = $CaRepo->getByDate($_GET['dateBegin'] , $_GET['dateEnd']);

    $dateDeb = preg_replace('/T/' , ' ' ,$_GET['dateBegin']);
    $dateEnd = preg_replace('/T/' , ' ' ,$_GET['dateEnd']);
    $pdf = new PDF();
    $pdf->setHeaderTitle("CA du " .$dateDeb. " au " . $dateEnd);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->setHeader();

    
    $pdf->Br();
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(47,10,$pdf->convertText("User"),1,0,'C');
    $pdf->Cell(47,10,$pdf->convertText("Date"),1,0,'C');
    $pdf->Cell(47,10,$pdf->convertText("Prix de base"),1,0,'C');
    $pdf->Cell(47,10,$pdf->convertText("Chiffre d'affaire"),1,1,'C');

    $total = 0;
    foreach($cas as $ca) {
        $total += $ca->getPriceCa();

        $pdf->SetFont('Times', '', 10);

        $pdf->Cell(47, 10, $pdf->convertText($ca->getIdUser()), 1, 0, 'C');
        $pdf->Cell(47, 10, $pdf->convertText($ca->getDate()->format("d/m/Y H:i")), 1, 0, 'C');
        $pdf->Cell(47, 10, $pdf->convertText($ca->getPrice()), 1, 0, 'C');
        $pdf->Cell(47, 10, " + " . $pdf->convertText($ca->getPriceCa()), 1, 1, 'C');
        
    }
    $pdf->setFillColor(0,230,0);
    $pdf->Cell(47, 10, "TOTAL", 1, 0, 'C', true);
    $pdf->Cell(47, 10, "TOTAL", 1, 0, 'C', true);
    $pdf->Cell(47, 10, "TOTAL", 1, 0, 'C', true);
    $pdf->Cell(47, 10, " + " . $total, 1, 1, 'C',true);

    $pdf->Output();
}else{
    echo translate("Aucune date renseigné");
}
