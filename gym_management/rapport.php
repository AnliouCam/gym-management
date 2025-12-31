<?php
require('fpdf/fpdf.php');
require_once "config.php"; // Connexion à la base de données

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'Rapport des Revenus', 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$dateEdition = date("d/m/Y H:i");
$pdf->Cell(190, 10, "Date d'edition: $dateEdition", 0, 1, 'R');
$pdf->Ln(5);

// Revenu total des non-abonnes (journalier et mensuel)
$revenuJournalier = $pdo->query("SELECT COALESCE(SUM(montant_paye), 0) FROM non_abonnes WHERE DATE(date_heure) = CURDATE()")
                          ->fetchColumn();
$revenuMensuel = $pdo->query("SELECT COALESCE(SUM(montant_paye), 0) FROM non_abonnes WHERE DATE_FORMAT(date_heure, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')")
                        ->fetchColumn();

$pdf->Cell(190, 10, "Revenus des non-abonnes", 0, 1, 'L');
$pdf->Cell(190, 10, "- Journalier: " . number_format($revenuJournalier, 0, ',', ' ') . " FCFA", 0, 1, 'L');
$pdf->Cell(190, 10, "- Mensuel: " . number_format($revenuMensuel, 0, ',', ' ') . " FCFA", 0, 1, 'L');
$pdf->Ln(5);

// Revenu total des abonnes (mensuel)
$revenuAbonnes = $pdo->query("SELECT COALESCE(SUM(prix), 0) FROM abonnements WHERE DATE_FORMAT(date_debut, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')")
                      ->fetchColumn();

$pdf->Cell(190, 10, "Revenus des abonnes", 0, 1, 'L');
$pdf->Cell(190, 10, "- Mensuel: " . number_format($revenuAbonnes, 0, ',', ' ') . " FCFA", 0, 1, 'L');
$pdf->Ln(5);

// Liste des transactions des non-abonnes
$pdf->Cell(190, 10, "Transactions des non-abonnes", 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 10, 'Date', 1);
$pdf->Cell(60, 10, 'Heure', 1);
$pdf->Cell(70, 10, 'Montant (FCFA)', 1);
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);

$stmt = $pdo->query("SELECT DATE(date_heure) as date, TIME(date_heure) as heure, montant_paye FROM non_abonnes ORDER BY date_heure DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(60, 10, $row['date'], 1);
    $pdf->Cell(60, 10, $row['heure'], 1);
    $pdf->Cell(70, 10, number_format($row['montant_paye'], 0, ',', ' '), 1);
    $pdf->Ln();
}

// Forcer le téléchargement du fichier PDF
$pdf->Output('D', 'rapport_revenus.pdf');
?>
