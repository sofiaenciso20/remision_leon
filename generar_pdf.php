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

// Función global para unificar la codificación
function encode_text($text) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text ?? '');
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
        $line_height = 4;
        $font_size = 8;

        // Borde exterior
        $this->Rect($x, $y, $w, $h);

        // --- COLUMNA IZQUIERDA: DATOS ---
        $data_col_w = $w * 0.65;
        $this->Rect($x, $y, $data_col_w, $h);

        $this->SetFont('Arial', 'B', $font_size);
        $this->SetXY($x + 2, $y + 2);

        // Fila 1
        $this->Cell(20, $line_height, encode_text('REMISIÓN N°:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(30, $line_height, encode_text($this->datos['numero_remision']), 0, 1);

        // Fila 2
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('FECHA:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(30, $line_height, date('d/m/Y', strtotime($this->datos['fecha_emision'])), 0, 1);

        // Fila 3
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('TIPO:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(30, $line_height, encode_text($this->datos['tipo_remision']), 0, 1);

        // Fila 4
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('CLIENTE:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(0, $line_height, encode_text($this->datos['nombre_cliente']), 0, 1);

        // Fila 5: Dirección y NIT
        $current_y = $this->GetY();
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('DIRECCIÓN:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(60, $line_height, encode_text($this->datos['direccion']), 0, 0);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(10, $line_height, encode_text('NIT:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(0, $line_height, encode_text($this->datos['nit']), 0, 1);

        // Fila 6: Teléfono
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('TELÉFONO:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(0, $line_height, encode_text($this->datos['telefono']), 0, 1);

        // Fila 7: Recibe
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('RECIBE:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(0, $line_height, encode_text($this->datos['nombre_persona']), 0, 1);

        // Fila 8: Responsable
        $this->SetX($x + 2);
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(20, $line_height, encode_text('RESPONSABLE:'), 0, 0);
        $this->SetFont('Arial', '', $font_size);
        $this->Cell(0, $line_height, encode_text($this->datos['nombre_responsable']), 0, 1);


        // --- COLUMNA DERECHA: LOGO ---
        $logo_col_x = $x + $data_col_w;
        $logo_col_w = $w - $data_col_w;
        $this->Image('img/logo.png', $logo_col_x + 2, $y + 2, $logo_col_w - 4, $h - 4);

        // Mover cursor para el contenido
        $this->Ln(5);
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
        $this->Cell($tercio-5,4, encode_text('Administrador'),0,0,'C');

        // 2️⃣ Firma Cliente
        $this->SetXY(10+$tercio,$y+20);
        $this->Cell($tercio-5,4,'_________________________',0,1,'C');
        $this->SetX(10+$tercio);
        $this->Cell($tercio-5,4, encode_text('Cliente - NIT o CC.'),0,0,'C');

        // 3️⃣ Firma Responsable
        $this->SetXY(10+($tercio*2),$y+20);
        $this->Cell($tercio-5,4,'_________________________',0,1,'C');
        $this->SetX(10+($tercio*2));
        $this->Cell($tercio-5,4, encode_text('Responsable'),0,1,'C');

        // Nombre del responsable debajo de la firma
        if (isset($this->datos['nombre_responsable']) && !empty($this->datos['nombre_responsable'])) {
            $this->SetX(10+($tercio*2));
            $this->SetFont('Arial','',7);
            $this->Cell($tercio-5,4,
                encode_text($this->datos['nombre_responsable']),
            0,0,'C');
        }
    }
}

// CREAR PDF
$pdf = new RemisionPDF($datos);
$pdf->SetMargins(10, 45, 10); // Margen superior ajustado para el nuevo diseño
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
    $pdf->Cell($col1,7, encode_text('CANT.'),1,0,'C');
    $pdf->Cell($col2,7, encode_text('DESCRIPCIÓN'),1,0,'C');
    $pdf->Cell($col3,7, encode_text('V. UNITARIO'),1,0,'C');
    $pdf->Cell($col4,7, encode_text('TOTAL'),1,1,'C');

    $pdf->SetFont('Arial','',8);
    $total_general = 0;

    foreach ($items as $i) {
        $valor = $i['valor_unitario'] ?? 0;
        $total = $i['cantidad'] * $valor;
        $total_general += $total;

        $pdf->Cell($col1,6, encode_text($i['cantidad']),1,0,'C');
        $pdf->Cell($col2,6, encode_text(substr($i['descripcion'],0,70)),1,0,'L');
        $pdf->Cell($col3,6,"$ ".number_format($valor,0,',','.'),1,0,'R');
        $pdf->Cell($col4,6,"$ ".number_format($total,0,',','.'),1,1,'R');
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($col1+$col2+$col3,7, encode_text('TOTAL GENERAL'),1,0,'R');
    $pdf->Cell($col4,7,"$ ".number_format($total_general,0,',','.'),1,1,'R');

} else {
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(0,10, encode_text('No hay items en esta remisión'),0,1,'C');
}

// Observaciones
$pdf->Ln(3);
if (!empty($datos['observaciones'])) {
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(0,5, encode_text('OBSERVACIONES:'),0,1);
    $pdf->SetFont('Arial','',7);
    $pdf->MultiCell(0,4, encode_text($datos['observaciones']),1,'L');
}

$pdf->Output('I', 'Remision_'.$datos['numero_remision'].'.pdf');
?>
