<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';
require_once __DIR__ . '/models/Remision.php';

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancias de los modelos
$cliente = new Cliente($db);
$remision = new Remision($db);

// Obtener estadísticas
$totalClientes = $cliente->contarTotal();
$totalRemisiones = $remision->contarTotal();

try {
    $remisionesHoy = method_exists($remision, 'contarPorFecha') 
        ? $remision->contarPorFecha(date('Y-m-d')) 
        : 0;
} catch (Error $e) {
    $remisionesHoy = 0;
}

$remisionesRecientes = $remision->obtenerRecientes(5);

include __DIR__ . '/views/layout/header.php';
?>

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat boxes) -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Remisiones</span>
                        <span class="info-box-number"><?php echo $totalRemisiones; ?></span>
                        <div class="info-box-footer">
                            <a href="listar_remisiones.php" class="text-info">Ver todas <i class="fas fa-arrow-circle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Clientes</span>
                        <span class="info-box-number"><?php echo $totalClientes; ?></span>
                        <div class="info-box-footer">
                            <a href="clientes.php" class="text-success">Ver todos <i class="fas fa-arrow-circle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1">
                        <i class="fas fa-calendar-day"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Remisiones Hoy</span>
                        <span class="info-box-number"><?php echo $remisionesHoy; ?></span>
                        <div class="info-box-footer">
                            <a href="listar_remisiones.php?fecha=<?php echo date('Y-m-d'); ?>" class="text-warning">Ver detalles <i class="fas fa-arrow-circle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Fecha Actual</span>
                        <span class="info-box-number"><?php echo date('d/m/Y'); ?></span>
                        <div class="info-box-footer">
                            <a href="index.php" class="text-danger">Nueva Remisión <i class="fas fa-arrow-circle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-rocket mr-2"></i>Accesos Rápidos
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <a href="index.php" class="btn btn-app bg-primary">
                                    <i class="fas fa-plus-circle fa-2x"></i>
                                    Nueva Remisión
                                </a>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <a href="clientes.php" class="btn btn-app bg-success">
                                    <i class="fas fa-user-plus fa-2x"></i>
                                    Nuevo Cliente
                                </a>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <a href="listar_remisiones.php" class="btn btn-app bg-info">
                                    <i class="fas fa-search fa-2x"></i>
                                    Buscar Remisiones
                                </a>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <button type="button" class="btn btn-app bg-warning" onclick="generarReporte()">
                                    <i class="fas fa-chart-bar fa-2x"></i>
                                    Reportes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-2"></i>Remisiones Recientes
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($remisionesRecientes) > 0): ?>
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <?php foreach ($remisionesRecientes as $remision): ?>
                                    <li class="item">
                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">
                                                Remisión #<?php echo $remision['numero_remision']; ?>
                                                <span class="badge badge-info float-right"><?php echo date('d/m/Y', strtotime($remision['fecha_emision'])); ?></span>
                                            </a>
                                            <span class="product-description">
                                                <?php echo $remision['nombre_cliente']; ?>
                                            </span>
                                            <div class="btn-group mt-2">
                                                <a href="ajax/ver_remision.php?id=<?php echo $remision['id_remision']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="generar_pdf.php?id=<?php echo $remision['id_remision']; ?>" 
                                                   class="btn btn-sm btn-outline-danger" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center p-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay remisiones recientes</p>
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Crear Primera Remisión
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center">
                        <a href="listar_remisiones.php" class="uppercase">Ver todas las remisiones</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>Información del Sistema
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Empresa</span>
                                        <span class="info-box-number text-center text-info mb-0">León Gráficas S.A.S</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Sistema</span>
                                        <span class="info-box-number text-center text-success mb-0">Gestión de Remisiones</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Última Actualización</span>
                                        <span class="info-box-number text-center text-warning mb-0"><?php echo date('d/m/Y'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function generarReporte() {
    Swal.fire({
        title: 'Reportes',
        text: 'Funcionalidad de reportes en desarrollo',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

setInterval(function() {
    location.reload();
}, 300000);
</script>

<?php include 'views/layout/footer.php'; ?>