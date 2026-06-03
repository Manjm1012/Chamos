<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FleetOps | Mantenimiento inteligente para flotas</title>
    <meta name="description" content="Software de mantenimiento para flotas de transporte: historial tecnico, alertas preventivas y control de costos.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'sans-serif'],
                        display: ['Chivo', 'sans-serif'],
                    },
                    colors: {
                        ink: '#152126',
                        sand: '#f7f2e8',
                        amberx: '#e76f1e',
                        tealx: '#1f8a84',
                        cloud: '#f2f7f8',
                    },
                    keyframes: {
                        rise: {
                            '0%': { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        rise: 'rise 0.8s ease-out forwards',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-sand text-ink antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-24 h-96 w-96 rounded-full bg-amberx/30 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 h-96 w-96 rounded-full bg-tealx/20 blur-3xl"></div>
    </div>

    <header class="mx-auto mt-5 w-[min(1150px,94vw)] rounded-2xl border border-ink/10 bg-white/70 px-6 py-4 backdrop-blur">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-ink font-display text-sm font-bold text-white">FO</div>
                <p class="font-display text-lg font-bold">FleetOps</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/api/v1/auth/login" class="rounded-xl border border-ink/15 bg-white px-4 py-2 text-sm font-semibold transition hover:-translate-y-0.5">API v1</a>
                <a href="/app" class="rounded-xl bg-ink px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5">Entrar al panel</a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-[min(1150px,94vw)] pb-20 pt-8">
        <section class="grid gap-5 rounded-3xl border border-ink/10 bg-white/80 p-8 shadow-xl shadow-ink/5 lg:grid-cols-[1.2fr_1fr]">
            <div class="animate-rise" style="animation-delay:80ms;">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-tealx">Gestion de mantenimiento para transporte</p>
                <h1 class="mt-3 font-display text-4xl font-black leading-[0.95] md:text-6xl">Controla tu flota, reduce costos y evita paradas inesperadas.</h1>
                <p class="mt-4 max-w-2xl text-base text-ink/75 md:text-lg">Una plataforma para empresas de transporte que necesitan trazabilidad tecnica real: mantenimientos preventivos, correctivos, historial por unidad y reportes para decidir con datos.</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="/app" class="rounded-xl bg-amberx px-5 py-3 text-sm font-bold text-white shadow-lg shadow-amberx/25 transition hover:-translate-y-0.5">Agendar demo</a>
                    <a href="/app/equipment" class="rounded-xl border border-ink/20 bg-white px-5 py-3 text-sm font-bold transition hover:-translate-y-0.5">Explorar plataforma</a>
                </div>
            </div>
            <aside class="animate-rise rounded-2xl bg-ink p-5 text-white" style="animation-delay:180ms;">
                <h2 class="font-display text-xl font-bold">Operacion visible en tiempo real</h2>
                <ul class="mt-4 space-y-3 text-sm text-white/85">
                    <li class="rounded-xl border border-white/15 bg-white/5 p-3">Alertas por fecha y kilometraje antes de la falla.</li>
                    <li class="rounded-xl border border-white/15 bg-white/5 p-3">Historial tecnico por equipo con linea de tiempo.</li>
                    <li class="rounded-xl border border-white/15 bg-white/5 p-3">Costos por unidad, por mes y por tipo de mantenimiento.</li>
                    <li class="rounded-xl border border-white/15 bg-white/5 p-3">Panel administrativo para supervisores y mecanicos.</li>
                </ul>
            </aside>
        </section>

        <section class="mt-8 rounded-3xl border border-ink/10 bg-white/85 p-8 animate-rise" style="animation-delay:220ms;">
            <h2 class="font-display text-3xl font-black">El problema</h2>
            <p class="mt-3 max-w-3xl text-ink/75">Muchas flotas siguen operando con registros dispersos en hojas de calculo, mensajes sueltos y memoria del equipo. Eso provoca mantenimientos tardios, sobrecostos y poca visibilidad para gerencia.</p>
            <div class="mt-5 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4 text-sm font-semibold">Sin historial centralizado por vehiculo</div>
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4 text-sm font-semibold">Alertas preventivas inexistentes o tardias</div>
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4 text-sm font-semibold">Costos ocultos hasta cierre de mes</div>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-ink/10 bg-linear-to-br from-white to-cloud p-8 animate-rise" style="animation-delay:280ms;">
            <h2 class="font-display text-3xl font-black">Nuestra solucion</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <article class="rounded-2xl border border-ink/10 bg-white p-5">
                    <h3 class="font-display text-xl font-bold">1. Registro de equipos</h3>
                    <p class="mt-2 text-sm text-ink/75">Control total de unidades, estado operativo, kilometraje y tipo de activo.</p>
                </article>
                <article class="rounded-2xl border border-ink/10 bg-white p-5">
                    <h3 class="font-display text-xl font-bold">2. Historial tecnico y timeline</h3>
                    <p class="mt-2 text-sm text-ink/75">Cada mantenimiento queda documentado y ordenado cronologicamente para auditoria y decisiones.</p>
                </article>
                <article class="rounded-2xl border border-ink/10 bg-white p-5">
                    <h3 class="font-display text-xl font-bold">3. Alertas inteligentes</h3>
                    <p class="mt-2 text-sm text-ink/75">Recordatorios por proximidad de servicio para anticiparte a fallas y paradas inesperadas.</p>
                </article>
                <article class="rounded-2xl border border-ink/10 bg-white p-5">
                    <h3 class="font-display text-xl font-bold">4. Reportes de costos</h3>
                    <p class="mt-2 text-sm text-ink/75">Visualiza costos por unidad, tendencia mensual y comparativo preventivo vs correctivo.</p>
                </article>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-ink/10 bg-white p-8 animate-rise" style="animation-delay:340ms;">
            <h2 class="font-display text-3xl font-black">Demo visual</h2>
            <p class="mt-3 text-ink/75">Interfaz creada con Filament para operaciones rapidas: filtros por fecha, acciones por unidad y panel de indicadores.</p>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-tealx">Dashboard</p>
                    <p class="mt-2 text-sm">KPIs de equipos activos, mantenimientos proximos y costos.</p>
                </div>
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-tealx">Timeline</p>
                    <p class="mt-2 text-sm">Historial por unidad con eventos agrupados por mes.</p>
                </div>
                <div class="rounded-2xl border border-ink/10 bg-cloud p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-tealx">Reportes</p>
                    <p class="mt-2 text-sm">Costos por equipo, por mes y distribucion por tipo de mantenimiento.</p>
                </div>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-ink/10 bg-white/85 p-8 animate-rise" style="animation-delay:400ms;">
            <h2 class="font-display text-3xl font-black">Beneficios para tu operacion</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-ink/10 bg-white p-4 text-sm font-semibold">Menos paradas no programadas</div>
                <div class="rounded-2xl border border-ink/10 bg-white p-4 text-sm font-semibold">Decisiones basadas en datos reales</div>
                <div class="rounded-2xl border border-ink/10 bg-white p-4 text-sm font-semibold">Trazabilidad para auditorias</div>
                <div class="rounded-2xl border border-ink/10 bg-white p-4 text-sm font-semibold">Escalable a modelo SaaS multi-empresa</div>
            </div>
        </section>

        <section class="mt-8 rounded-3xl bg-ink p-8 text-white animate-rise" style="animation-delay:460ms;">
            <h2 class="font-display text-3xl font-black">Solicita una demo</h2>
            <p class="mt-3 max-w-2xl text-white/80">Transforma tu mantenimiento en un sistema predictivo y ordenado. Ve la plataforma en accion con tus propios casos de uso.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="/app" class="rounded-xl bg-amberx px-5 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5">Solicitar demo</a>
                <a href="/app/maintenances" class="rounded-xl border border-white/25 px-5 py-3 text-sm font-bold transition hover:-translate-y-0.5">Ver mantenimientos</a>
            </div>
        </section>
    </main>
</body>
</html>
