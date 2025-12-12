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
        // --- COLUMNA IZQUIERDA ---
        $this->Image('img/logo.png', 10, 8, 40);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(45, 6, '', 0, 0); // Espacio para el logo
        $this->Cell(80, 6, encode_text('LEÓN GRÁFICAS'), 0, 1, 'L');

        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 4, '', 0, 0);
        $this->Cell(80, 4, encode_text('Soluciones Gráficas y Logística'), 0, 1, 'L');

        $this->Cell(45, 4, '', 0, 0);
        $this->Cell(80, 4, encode_text('NIT: 1.110.510.622-5'), 0, 1, 'L');

        $this->Cell(45, 4, '', 0, 0);
        $this->Cell(80, 4, encode_text('Tel: 310 207 4333 - 318 408 8848'), 0, 1, 'L');

        // --- COLUMNA DERECHA ---
        $right_col_x = 140;
        $this->SetY(10);
        $this->SetX($right_col_x);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(60, 7, encode_text('REMISIÓN'), 1, 1, 'C');

        $this->SetX($right_col_x);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(60, 9, encode_text('N° ' . $this->datos['numero_remision']), 1, 1, 'C');

        $this->SetY(28); // Alinearlo verticalmente
        $this->SetX($right_col_x);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, encode_text('FECHA:'), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 5, date('d/m/Y', strtotime($this->datos['fecha_emision'])), 0, 1, 'L');

        // --- BLOQUE DE DATOS DEL CLIENTE ---
        $this->Ln(8); // Espacio antes del bloque
        $y_cliente = $this->GetY();
        $this->SetFillColor(240, 240, 240);
        $this->Rect(10, $y_cliente, $this->w - 20, 24, 'DF');

        // Fila 1: Cliente y NIT
        $this->SetY($y_cliente + 2);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, encode_text('CLIENTE:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, encode_text($this->datos['nombre_cliente']), 0, 0);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(10, 5, encode_text('NIT:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, encode_text($this->datos['nit']), 0, 1);

        // Fila 2: Dirección y Teléfono
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, encode_text('DIRECCIÓN:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, encode_text($this->datos['direccion']), 0, 0);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, encode_text('TELÉFONO:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, encode_text($this->datos['telefono']), 0, 1);

        // Fila 3: Recibe (Persona Contacto)
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, encode_text('RECIBE:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, encode_text($this->datos['nombre_persona']), 0, 1);

        // Fila 4: Responsable
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, encode_text('RESPONSABLE:'), 0, 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(110, 5, encode_text($this->datos['nombre_responsable']), 0, 1);

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
$pdf->SetMargins(10, 75, 10); // Aumentado el margen para evitar superposición
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
