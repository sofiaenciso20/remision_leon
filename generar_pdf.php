<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Remision.php';
require_once __DIR__ . '/models/ItemRemisionado.php';
require_once __DIR__ . '/lib/fpdf/fpdf.php';

// Validar parámetro ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    die('<h1>Error</h1><p>ID de remisión no especificado</p>');
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $remision = new Remision($db);
    $itemRemisionado = new ItemRemisionado($db);

    // DATOS COMPLETOS: Cliente + Persona Contacto + Responsable + Tipo
    $datos = $remision->obtenerPorIdConResponsable($_GET['id']);
    $items = $itemRemisionado->obtenerPorRemision($_GET['id']);

    if (!$datos) {
        http_response_code(404);
        die('<h1>Error</h1><p>Remisión no encontrada</p>');
    }

} catch (Exception $e) {
    error_log("Error al generar PDF: " . $e->getMessage());
    http_response_code(500);
    die('<h1>Error</h1><p>Error al generar el PDF</p>');
}

class RemisionPDF extends FPDF {
    public $datos;

    function __construct($datos) {
        parent::__construct('L', 'mm', 'A5');
        $this->datos = $datos;
    }

    function Header() {
        $x = 10;
        $y = 10;
        $w = $this->w - 20;
        $h = 32;

        $this->Rect($x, $y, $w, $h);
        $divisor_x = $x + $w * 0.6;
        $this->Line($divisor_x, $y, $divisor_x, $y + $h);

        // Número remisión
        $this->SetXY($x + 3, $y + 3);
        $this->SetFont('Arial','B',10);
        $this->Cell(28,4,'REMISIÓN N°: ',0,0);
        $this->SetFont('Arial','B',12);
        $this->Cell(15,4,iconv('UTF-8', 'ISO-8859-1', $this->datos['numero_remision']),0,1);

        // Fecha
        $this->SetFont('Arial','B',9);
        $this->SetX($x+3);
        $this->Cell(14,4,'FECHA: ',0,0);
        $this->SetFont('Arial','',9);
        $this->Cell(20,4,date('d/m/Y',strtotime($this->datos['fecha_emision'])),0,1);

        // Tipo de Remisión
        $this->SetFont('Arial','B',9);
        $this->SetX($x+3);
        $this->Cell(12,4,'TIPO: ',0,0);
        $this->SetFont('Arial','',9);
        $this->Cell(20,4,iconv('UTF-8', 'ISO-8859-1', $this->datos['tipo_remision']),0,1);

        // Cliente
        $this->SetFont('Arial','B',9);
        $this->SetX($x+3);
        $this->Cell(17,4,'CLIENTE: ',0,0);
        $this->SetFont('Arial','',9);
        $this->Cell(0,4,iconv('UTF-8', 'ISO-8859-1', $this->datos['nombre_cliente']),0,1);

        // Dirección y Teléfono
        $this->SetFont('Arial','B',9);
        $this->SetX($x+3);
        $this->Cell(20,4,'DIRECCIÓN: ',0,0);
        $this->SetFont('Arial','',9);
        $this->MultiCell($divisor_x - $x - 23,4,iconv('UTF-8', 'ISO-8859-1', $this->datos['direccion']),0);

        $this->SetX($x+3);
        $this->SetFont('Arial','B',9);
        $this->Cell(20,4,'TELÉFONO: ',0,0);
        $this->SetFont('Arial','',9);
        $this->Cell(30,4,$this->datos['telefono'],0,0);

        $this->SetFont('Arial','B',9);
        $this->Cell(10,4,'NIT:',0,0);
        $this->SetFont('Arial','',9);
        $this->Cell(0,4,$this->datos['nit'],0,1);

        // Persona Contacto (RECIBE)
        if (!empty($this->datos['nombre_persona'])) {
            $recibeInfo = $this->datos['nombre_persona'];
            if (!empty($this->datos['telefono_persona'])) {
                $recibeInfo .= ' - Tel: '.$this->datos['telefono_persona'];
            }

            $this->SetX($x+3);
            $this->SetFont('Arial','B',9);
            $this->Cell(17,4,'RECIBE:',0,0);
            $this->SetFont('Arial','',9);
            $this->Cell(0,4,iconv('UTF-8', 'ISO-8859-1', $recibeInfo),0,1);
        }

        // RESPONSABLE (ARRIBA DEL PDF)
        if (isset($this->datos['nombre_responsable']) && !empty($this->datos['nombre_responsable'])) {
            $this->SetX($x+3);
            $this->SetFont('Arial','B',9);
            $this->Cell(28,4,'RESPONSABLE: ',0,0);
            $this->SetFont('Arial','',9);
            $this->Cell(0,4,iconv('UTF-8', 'ISO-8859-1', $this->datos['nombre_responsable']),0,1);
        }

        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-38);
        $ancho = $this->w - 20;

        $y = $this->GetY();
        $this->Rect(10, $y, $ancho, 32);

        $tercio = $ancho / 3;
        $this->SetFont('Arial','',8);

        // 1️⃣ Firma Administrador
        $this->SetXY(10,$y+20);
        $this->Cell($tercio-5,4,'_________________________',0,1,'C');
        $this->SetX(10);
        $this->SetFont('Arial','',7);
        $this->Cell($tercio-5,4,'Administrador',0,0,'C');

        // 2️⃣ Firma Cliente
        $this->SetXY(10+$tercio,$y+20);
        $this->Cell($tercio-5,4,'_________________________',0,1,'C');
        $this->SetX(10+$tercio);
        $this->Cell($tercio-5,4,'Cliente - NIT o CC.',0,0,'C');

        // 3️⃣ Firma Responsable
        $this->SetXY(10+($tercio*2),$y+20);
        $this->Cell($tercio-5,4,'_________________________',0,1,'C');
        $this->SetX(10+($tercio*2));
        $this->Cell($tercio-5,4,'Responsable',0,1,'C');

        // Nombre del responsable debajo de la firma
        if (isset($this->datos['nombre_responsable']) && !empty($this->datos['nombre_responsable'])) {
            $this->SetX(10+($tercio*2));
            $this->SetFont('Arial','',7);
            $this->Cell($tercio-5,4,
                iconv('UTF-8', 'ISO-8859-1', $this->datos['nombre_responsable']),
            0,0,'C');
        }
    }
}

// CREAR PDF
$pdf = new RemisionPDF($datos);
$pdf->SetMargins(10, 45, 10);
$pdf->SetAutoPageBreak(true, 45);
$pdf->AddPage();

// TABLA DE ITEMS
$ancho = $pdf->GetPageWidth() - 20;
if (count($items) > 0) {
    $col1 = 15;
    $col3 = 25;
    $col4 = 25;
    $col2 = $ancho - ($col1 + $col3 + $col4);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell($col1,7,'CANT.',1,0,'C');
    $pdf->Cell($col2,7,'DESCRIPCIÓN',1,0,'C');
    $pdf->Cell($col3,7,'V. UNITARIO',1,0,'C');
    $pdf->Cell($col4,7,'TOTAL',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $total_general = 0;

    foreach ($items as $i) {
        $valor = $i['valor_unitario'] ?? 0;
        $total = $i['cantidad'] * $valor;
        $total_general += $total;

        $pdf->Cell($col1,6,$i['cantidad'],1,0,'C');
        $pdf->Cell($col2,6,iconv('UTF-8', 'ISO-8859-1', substr($i['descripcion'],0,70)),1,0,'L');
        $pdf->Cell($col3,6,"$ ".number_format($valor,0,',','.'),1,0,'R');
        $pdf->Cell($col4,6,"$ ".number_format($total,0,',','.'),1,1,'R');
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($col1+$col2+$col3,7,'TOTAL GENERAL',1,0,'R');
    $pdf->Cell($col4,7,"$ ".number_format($total_general,0,',','.'),1,1,'R');

} else {
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(0,10,'No hay items en esta remisión',0,1,'C');
}

// Observaciones
$pdf->Ln(3);
if (!empty($datos['observaciones'])) {
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(0,5,'OBSERVACIONES:',0,1);
    $pdf->SetFont('Arial','',7);
    $pdf->MultiCell(0,4,iconv('UTF-8', 'ISO-8859-1', $datos['observaciones']),1,'L');
}

$pdf->Output('I', 'Remision_'.$datos['numero_remision'].'.pdf');
?>
