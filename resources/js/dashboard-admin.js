/**
 * dashboard-admin.js — Artcania
 * Dashboard ejecutivo con Chart.js
 * Requiere:
 *   - jQuery (ya en layout)
 */

(function () {
    'use strict';

    /* ── Paletas de colores Artcania ─────────────────────── */
    var GOLD     = '#e8c65a';
    var PURPLE   = '#7c3aed';
    var TEAL     = '#2dd4bf';
    var ROSE     = '#f87171';
    var VIOLET   = '#a78bfa';
    var EMERALD  = '#34d399';
    var AMBER    = '#f59e0b';
    var BLUE     = '#60a5fa';
    var BORDER   = 'rgba(255,255,255,.06)';
    var GRID     = 'rgba(255,255,255,.07)';

    var PALETTE_PIE  = [PURPLE, GOLD, TEAL, ROSE, VIOLET, EMERALD, AMBER, BLUE];
    var PALETTE_BAR  = [TEAL, VIOLET, GOLD, ROSE, EMERALD, AMBER];

    /* ── Defaults globales de Chart.js ──────────────────── */
    Chart.defaults.color          = '#b8a9d0';
    Chart.defaults.font.family    = 'Inter, sans-serif';
    Chart.defaults.font.size      = 12;
    Chart.defaults.plugins.legend.labels.boxWidth = 12;

    /* ── Referencias a instancias de charts ─────────────── */
    var charts = {};

    /* ── Helpers ─────────────────────────────────────────── */
    function formatCurrency(v) {
        return '$' + parseFloat(v || 0).toLocaleString('es-SV', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateKPI(id, value) {
        var el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    /* ── Actualizar KPIs ─────────────────────────────────── */
    function renderKPIs(kpis) {
        updateKPI('kpi-usuarios-total',   kpis.usuarios_total   || 0);
        updateKPI('kpi-usuarios-activos', kpis.usuarios_activos || 0);
        updateKPI('kpi-usuarios-nuevos',  kpis.usuarios_nuevos  || 0);
        updateKPI('kpi-obras',            kpis.obras            || 0);
        updateKPI('kpi-obras-pendientes', kpis.obras_pendientes || 0);
        updateKPI('kpi-artistas',         kpis.artistas         || 0);
        updateKPI('kpi-subastas',         kpis.subastas         || 0);
        updateKPI('kpi-ventas-mes',       formatCurrency(kpis.ventas_mes));
    }

    /* ── Gráfico 1: Pie — Usuarios por rol ──────────────── */
    function renderRoles(data) {
        var ctx = document.getElementById('chartRoles');
        if (!ctx || !data || !data.length) return;

        var labels = data.map(function (r) {
            return r.rol ? r.rol.charAt(0).toUpperCase() + r.rol.slice(1) : 'Sin rol';
        });
        var values = data.map(function (r) { return parseInt(r.total) || 0; });

        if (charts.roles) charts.roles.destroy();
        charts.roles = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data:            values,
                    backgroundColor: PALETTE_PIE.slice(0, labels.length),
                    borderColor:     '#0a0612',
                    borderWidth:     3,
                    hoverOffset:     8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                var pct   = total ? Math.round(ctx.parsed * 100 / total) : 0;
                                return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    /* ── Gráfico 2: Bar — Obras por categoría ───────────── */
    function renderObras(data) {
        var ctx = document.getElementById('chartObras');
        if (!ctx || !data || !data.length) return;

        var labels = data.map(function (r) { return r.categoria || 'Sin categoría'; });
        var values = data.map(function (r) { return parseInt(r.total) || 0; });

        if (charts.obras) charts.obras.destroy();
        charts.obras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label:           'Obras aprobadas',
                    data:            values,
                    backgroundColor: PALETTE_BAR.map(function (c) { return c + 'cc'; }),
                    borderColor:     PALETTE_BAR,
                    borderWidth:     1.5,
                    borderRadius:    6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { grid: { color: GRID }, ticks: { maxRotation: 40 } },
                    y: { grid: { color: GRID }, beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    /* ── Gráfico 3: Line — Subastas por mes ─────────────── */
    function renderSubastas(data) {
        var ctx = document.getElementById('chartSubastas');
        if (!ctx || !data || !data.length) return;

        var labels = data.map(function (r) { return r.mes_label || r.mes; });
        var values = data.map(function (r) { return parseInt(r.total) || 0; });

        if (charts.subastas) charts.subastas.destroy();
        charts.subastas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label:           'Subastas',
                    data:            values,
                    borderColor:     GOLD,
                    backgroundColor: 'rgba(232,198,90,.12)',
                    pointBackgroundColor: GOLD,
                    pointRadius:     4,
                    tension:         0.4,
                    fill:            true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { grid: { color: GRID } },
                    y: { grid: { color: GRID }, beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    /* ── Gráfico 4: Area — Ventas mensuales ─────────────── */
    function renderVentas(data) {
        var ctx = document.getElementById('chartVentas');
        if (!ctx || !data || !data.length) return;

        var labels    = data.map(function (r) { return r.mes_label || r.mes; });
        var ventas    = data.map(function (r) { return parseInt(r.total)   || 0; });
        var ingresos  = data.map(function (r) { return parseFloat(r.ingresos) || 0; });

        if (charts.ventas) charts.ventas.destroy();
        charts.ventas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label:           'Ingresos ($)',
                        data:            ingresos,
                        borderColor:     TEAL,
                        backgroundColor: 'rgba(45,212,191,.12)',
                        pointBackgroundColor: TEAL,
                        pointRadius:     4,
                        tension:         0.4,
                        fill:            true,
                        yAxisID:         'yIngresos'
                    },
                    {
                        label:           'Ventas (#)',
                        data:            ventas,
                        borderColor:     VIOLET,
                        backgroundColor: 'rgba(167,139,250,.08)',
                        pointBackgroundColor: VIOLET,
                        pointRadius:     4,
                        tension:         0.4,
                        fill:            false,
                        yAxisID:         'yVentas'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x:         { grid: { color: GRID } },
                    yIngresos: {
                        position: 'left',
                        grid:     { color: GRID },
                        beginAtZero: true,
                        ticks: {
                            callback: function (v) { return '$' + v.toLocaleString(); }
                        }
                    },
                    yVentas: {
                        position:  'right',
                        grid:      { drawOnChartArea: false },
                        beginAtZero: true,
                        ticks:     { precision: 0 }
                    }
                }
            }
        });
    }

    /* ── Carga y refresco ────────────────────────────────── */
    function cargarDashboard() {
        if (typeof BASE_URL === 'undefined') return;

        fetch(BASE_URL + '/ajax/dashboard_stats.php', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (data) {
            if (data.error) return;
            if (data.kpis)     renderKPIs(data.kpis);
            if (data.roles)    renderRoles(data.roles);
            if (data.obras)    renderObras(data.obras);
            if (data.subastas) renderSubastas(data.subastas);
            if (data.ventas)   renderVentas(data.ventas);
        })
        .catch(function (err) {
            console.warn('Artcania Dashboard:', err.message);
        });
    }

    /* ── Arrancar ────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        cargarDashboard();
        // Actualización automática cada 5 minutos
        setInterval(cargarDashboard, 5 * 60 * 1000);
    });

})();
