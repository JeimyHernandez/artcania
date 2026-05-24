<?php
class ReportController extends Controller {

    public function bitacoraPDF() {
        $this->requireRole('admin');
        $logs = (new Bitacora())->listar(1000, 0);
        $cols = ['ID', 'Usuario', 'Email', 'Acción', 'Detalle', 'IP', 'Fecha'];
        $rows = [];
        foreach ($logs as $r) {
            $rows[] = [
                $r['id'],
                $r['nombre'] ?? '-',
                $r['email']  ?? '-',
                $r['accion'],
                mb_substr($r['detalle'] ?? '', 0, 50),
                $r['ip'],
                $r['creado_en'],
            ];
        }
        $html = $this->buildHTMLReport('Bitácora de Auditoría – Artcania', $cols, $rows);
        $this->downloadPDF($html, 'bitacora_' . date('Ymd') . '.html');
    }

    public function bitacoraExcel() {
        $this->requireRole('admin');
        $logs = (new Bitacora())->listar(1000, 0);
        $cols = ['ID', 'Usuario', 'Email', 'Accion', 'Detalle', 'IP', 'Fecha'];
        $rows = [];
        foreach ($logs as $r) {
            $rows[] = [$r['id'], $r['nombre'] ?? '-', $r['email'] ?? '-', $r['accion'], $r['detalle'] ?? '', $r['ip'], $r['creado_en']];
        }
        $this->downloadExcel($this->buildExcelXML($rows, $cols), 'bitacora_' . date('Ymd') . '.xls');
    }

    public function comprasPDF() {
        $this->requireRole('admin');
        $ventas = Database::getInstance()->query(
            'SELECT v.*, u.nombre, u.email, o.titulo
             FROM ventas v
             JOIN usuarios u ON u.id = v.usuario_id
             JOIN obras o ON o.id = v.obra_id
             ORDER BY v.creado_en DESC LIMIT 1000'
        )->fetchAll();
        $cols = ['ID', 'Cliente', 'Obra', 'Monto', 'Estado', 'Fecha'];
        $rows = [];
        foreach ($ventas as $r) {
            $rows[] = [$r['id'], $r['nombre'], $r['titulo'], money($r['monto_total']), $r['estado'], $r['creado_en']];
        }
        $html = $this->buildHTMLReport('Reporte de Compras – Artcania', $cols, $rows);
        $this->downloadPDF($html, 'compras_' . date('Ymd') . '.html');
    }

    public function comprasExcel() {
        $this->requireRole('admin');
        $ventas = Database::getInstance()->query(
            'SELECT v.*, u.nombre, u.email, o.titulo FROM ventas v
             JOIN usuarios u ON u.id = v.usuario_id
             JOIN obras o ON o.id = v.obra_id
             ORDER BY v.creado_en DESC LIMIT 1000'
        )->fetchAll();
        $cols = ['ID', 'Cliente', 'Email', 'Obra', 'Monto', 'Comision', 'Estado', 'Fecha'];
        $rows = [];
        foreach ($ventas as $r) {
            $rows[] = [$r['id'], $r['nombre'], $r['email'], $r['titulo'], $r['monto_total'], $r['comision_plataforma'], $r['estado'], $r['creado_en']];
        }
        $this->downloadExcel($this->buildExcelXML($rows, $cols), 'compras_' . date('Ymd') . '.xls');
    }

    public function statsPDF() {
        $this->requireRole('admin');
        $stats = (new Estadistica())->resumen();
        $views = (new Estadistica())->visualizacionesPorDia(30);
        $top   = (new Estadistica())->obrasMasVistas(10);

        $cols = ['Métrica', 'Valor'];
        $rows = [
            ['Usuarios registrados', number_format($stats['usuarios'])],
            ['Artistas',             number_format($stats['artistas'])],
            ['Obras aprobadas',      number_format($stats['obras'])],
            ['Comentarios',          number_format($stats['comentarios'])],
            ['Subastas activas',     number_format($stats['subastas'])],
            ['Ventas totales',       money($stats['ventas'])],
        ];
        $html = $this->buildHTMLReport('Estadísticas Generales – Artcania', $cols, $rows);
        $this->downloadPDF($html, 'estadisticas_' . date('Ymd') . '.html');
    }

    public function statsExcel() {
        $this->requireRole('admin');
        $views = (new Estadistica())->visualizacionesPorDia(30);
        $cols  = ['Fecha', 'Visualizaciones'];
        $rows  = [];
        foreach ($views as $v) {
            $rows[] = [$v['fecha'], $v['total']];
        }
        $this->downloadExcel($this->buildExcelXML($rows, $cols), 'estadisticas_' . date('Ymd') . '.xls');
    }

    /* ── Generadores ──────────────────────────────────────── */
    private function buildHTMLReport(string $title, array $headers, array $rows) {
        $th = '';
        foreach ($headers as $h) {
            $th .= '<th>' . e($h) . '</th>';
        }
        $tbody = '';
        foreach ($rows as $row) {
            $tbody .= '<tr>';
            foreach ($row as $cell) {
                $tbody .= '<td>' . e((string)$cell) . '</td>';
            }
            $tbody .= '</tr>';
        }
        return "<!DOCTYPE html>
<html lang='es'><head><meta charset='UTF-8'>
<style>
  body{font-family:Arial,sans-serif;font-size:11px;color:#222;margin:20px}
  h1{color:#6c3483;font-size:16px;border-bottom:2px solid #6c3483;padding-bottom:6px}
  .meta{color:#888;font-size:10px;margin-bottom:10px}
  table{width:100%;border-collapse:collapse;margin-top:10px}
  th{background:#6c3483;color:#fff;padding:6px 8px;text-align:left;font-size:10px}
  td{padding:5px 8px;border-bottom:1px solid #ddd;font-size:10px}
  tr:nth-child(even){background:#f4f0fb}
  @media print{.no-print{display:none}}
</style></head><body>
<h1>{$title}</h1>
<p class='meta'>Generado: " . date('d/m/Y H:i') . " &nbsp;|&nbsp; Artcania &copy; " . date('Y') . "</p>
<button class='no-print' onclick='window.print()' style='padding:6px 14px;background:#6c3483;color:#fff;border:none;border-radius:4px;cursor:pointer;margin-bottom:10px'>Imprimir</button>
<table><thead><tr>{$th}</tr></thead><tbody>{$tbody}</tbody></table>
</body></html>";
    }

    private function buildExcelXML(array $rows, array $headers) {
        $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" ";
        $xml .= "xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\">\n";
        $xml .= "<Worksheet ss:Name=\"Reporte\"><Table>\n";

        // Header row
        $xml .= '<Row>';
        foreach ($headers as $h) {
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($h, ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($rows as $row) {
            $xml .= '<Row>';
            foreach ($row as $cell) {
                $type  = is_numeric($cell) ? 'Number' : 'String';
                $value = htmlspecialchars((string)$cell, ENT_QUOTES, 'UTF-8');
                $xml .= "<Cell><Data ss:Type=\"{$type}\">{$value}</Data></Cell>";
            }
            $xml .= '</Row>' . "\n";
        }

        $xml .= "</Table></Worksheet></Workbook>";
        return $xml;
    }
}