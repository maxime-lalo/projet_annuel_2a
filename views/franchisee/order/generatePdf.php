<?php

require_once __DIR__ . "/../../../utils/fpdf/fpdf.php";
require_once __DIR__ . "/../../../repositories/FranchiseeOrderRepository.php";
$fORepo = new FranchiseeOrderRepository();
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
if (isset($_GET['id'])){
    $order = $fORepo->getOneById($_GET['id']);

    $pdf = new PDF();
    $pdf->setHeaderTitle("Commande N°" . $order->getId());
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->setHeader();
    
    $pdf->Br();
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(47,10,$pdf->convertText("Date"),1,0,'C');
    $pdf->Cell(48,10,$pdf->convertText("Nombre d'articles fournis"),1,0,'C');
    $pdf->Cell(47,10,$pdf->convertText("Nombre d'articles manquants"),1,0,'C');
    $pdf->Cell(48,10,$pdf->convertText("Pourcentage"),1,1,'C');

    $pdf->SetFont('Times','',10);
    $pdf->Cell(47,10,$pdf->convertText($order->getDate()->format("d/m/Y H:i")),1,0,'C');
    $pdf->Cell(48,10,$pdf->convertText(count($order->getFoods())),1,0,'C');
    $pdf->Cell(47,10,$pdf->convertText(count($order->getMissing())),1,0,'C');
    $pdf->Cell(48,10,$pdf->convertText($order->getPercentage()),1,1,'C');

    $pdf->Br();

    $pdf->SetFont('Times','B',12);
    $pdf->Cell(0,10,$pdf->convertText("Articles fournis"),1,1,'C');
    $pdf->SetFont('Times','B',10);

    $pdf->Cell(63,10,$pdf->convertText("Article"),1,0,'C');
    $pdf->Cell(63,10,$pdf->convertText("Type"),1,0,'C');
    $pdf->Cell(64,10,$pdf->convertText("Quantité fournie"),1,1,'C');

    $pdf->SetFont('Times','',10);
    foreach($order->getFoods() as $food){
        $pdf->Cell(63,10,$pdf->convertText($food->getName()),1,0,'C');
        $pdf->Cell(63,10,$pdf->convertText($food->getType()),1,0,'C');
        $pdf->Cell(64,10,$pdf->convertText($food->getQuantity() . " * " . $food->getWeight() . $food->getUnity()),1,1,'C');
    }

    $pdf->Br();
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(0,10,$pdf->convertText("Articles non fournis"),1,1,'C');
    $pdf->SetFont('Times','B',10);

    $pdf->Cell(63,10,$pdf->convertText("Article"),1,0,'C');
    $pdf->Cell(63,10,$pdf->convertText("Type"),1,0,'C');
    $pdf->Cell(64,10,$pdf->convertText("Quantité manquante"),1,1,'C');

    $pdf->SetFont('Times','',10);
    foreach($order->getMissing() as $food){
        $pdf->Cell(63,10,$pdf->convertText($food->getName()),1,0,'C');
        $pdf->Cell(63,10,$pdf->convertText($food->getType()),1,0,'C');
        $pdf->Cell(64,10,$pdf->convertText(($food->getQuantity() * $food->getWeight()) . $food->getUnity()),1,1,'C');
    }

    $pdf->Br();
    $pdf->Output();
}else{
    echo translate("Aucun id renseigné");
}
