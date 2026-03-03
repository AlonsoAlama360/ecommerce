@extends('layouts.app')

@section('title', 'Política de Cambios y Devoluciones - Arixna')
@section('meta_description', 'Conoce nuestra política de cambios y devoluciones. En Arixna garantizamos tu satisfacción con cada compra.')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-3">Política de Cambios y Devoluciones</h1>
            <p class="text-gray-500">Última actualización: {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 space-y-10 text-gray-700 leading-relaxed">

            <!-- Intro -->
            <section>
                <p>En <strong>Arixna</strong> queremos que estés completamente satisfecho con tu compra. Si por algún motivo necesitas realizar un cambio o devolución, aquí te explicamos cómo proceder, en cumplimiento del Código de Protección y Defensa del Consumidor (Ley N° 29571).</p>
            </section>

            <!-- 1. Plazo -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Plazo para cambios y devoluciones</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Tienes <strong>7 días calendario</strong> desde la recepción del producto para solicitar un cambio o devolución.</li>
                    <li>Para productos con defectos de fábrica o que no correspondan a lo ofrecido, el plazo se extiende a <strong>30 días calendario</strong>.</li>
                </ul>
            </section>

            <!-- 2. Condiciones -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Condiciones para aceptar un cambio o devolución</h2>
                <p>Para que un cambio o devolución sea aceptado, el producto debe cumplir con las siguientes condiciones:</p>
                <ul class="mt-3 space-y-2 list-disc list-inside">
                    <li>Estar <strong>sin uso</strong> y en las mismas condiciones en que fue recibido.</li>
                    <li>Conservar su <strong>empaque original</strong>, etiquetas, manuales y accesorios incluidos.</li>
                    <li>Presentar el <strong>comprobante de pago</strong> (boleta o factura).</li>
                    <li>No pertenecer a la categoría de productos excluidos (ver sección 5).</li>
                </ul>
            </section>

            <!-- 3. Motivos válidos -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Motivos válidos</h2>

                <div class="grid md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-green-50 rounded-xl p-5">
                        <h3 class="font-semibold text-green-800 mb-3"><i class="fas fa-check-circle mr-2"></i>Se acepta cambio o devolución</h3>
                        <ul class="space-y-1 text-sm text-green-700 list-disc list-inside">
                            <li>Producto con defecto de fábrica</li>
                            <li>Producto diferente al solicitado</li>
                            <li>Producto dañado durante el envío</li>
                            <li>Producto incompleto (falta accesorios)</li>
                            <li>Desistimiento dentro de los 7 días</li>
                        </ul>
                    </div>
                    <div class="bg-red-50 rounded-xl p-5">
                        <h3 class="font-semibold text-red-800 mb-3"><i class="fas fa-times-circle mr-2"></i>No se acepta cambio o devolución</h3>
                        <ul class="space-y-1 text-sm text-red-700 list-disc list-inside">
                            <li>Producto usado o manipulado</li>
                            <li>Sin empaque original o etiquetas</li>
                            <li>Daño por mal uso del cliente</li>
                            <li>Pasado el plazo establecido</li>
                            <li>Productos de higiene personal abiertos</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 4. Procedimiento -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Procedimiento para solicitar un cambio o devolución</h2>
                <ol class="space-y-4 list-decimal list-inside">
                    <li>
                        <strong>Comunícate con nosotros</strong> enviando un correo a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong> indicando:
                        <ul class="mt-2 ml-6 space-y-1 list-disc list-inside text-sm">
                            <li>Número de pedido</li>
                            <li>Nombre completo del comprador</li>
                            <li>Producto(s) que deseas cambiar o devolver</li>
                            <li>Motivo del cambio o devolución</li>
                            <li>Fotos del producto y empaque (en caso de defecto o daño)</li>
                        </ul>
                    </li>
                    <li><strong>Evaluación:</strong> Nuestro equipo evaluará tu solicitud en un plazo máximo de <strong>3 días hábiles</strong> y te notificará por correo electrónico.</li>
                    <li><strong>Envío del producto:</strong> Si la solicitud es aprobada, te indicaremos cómo realizar el envío del producto. Los costos de envío por devolución corren por cuenta de Arixna si el motivo es un defecto de fábrica o error nuestro.</li>
                    <li><strong>Resolución:</strong> Una vez recibido y verificado el producto, procederemos con el cambio o reembolso según corresponda.</li>
                </ol>
            </section>

            <!-- 5. Productos excluidos -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Productos excluidos de cambios y devoluciones</h2>
                <p>Por razones de higiene y seguridad, los siguientes productos <strong>no admiten cambios ni devoluciones</strong> salvo defecto de fábrica:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li>Perfumes y fragancias abiertos o usados</li>
                    <li>Productos de cuidado personal abiertos</li>
                    <li>Joyería personalizada o grabada</li>
                    <li>Productos en promoción o liquidación (salvo defectos de fábrica)</li>
                </ul>
            </section>

            <!-- 6. Reembolsos -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Reembolsos</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Los reembolsos se realizarán por el <strong>mismo medio de pago</strong> utilizado en la compra.</li>
                    <li>El plazo para efectuar el reembolso es de <strong>15 días hábiles</strong> contados desde la aprobación de la devolución.</li>
                    <li>Para pagos con tarjeta, el reembolso puede tardar hasta <strong>2 ciclos de facturación</strong> adicionales dependiendo de la entidad bancaria.</li>
                    <li>Los costos de envío original <strong>no son reembolsables</strong>, salvo en casos de error de Arixna o defecto de fábrica.</li>
                </ul>
            </section>

            <!-- 7. Cambios -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Cambios de producto</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Los cambios están sujetos a la <strong>disponibilidad de stock</strong> del producto solicitado.</li>
                    <li>Si el producto de cambio tiene un precio mayor, el cliente deberá abonar la diferencia.</li>
                    <li>Si el producto de cambio tiene un precio menor, se reembolsará la diferencia.</li>
                    <li>Solo se permite <strong>un cambio por producto</strong>.</li>
                </ul>
            </section>

            <!-- 8. Contacto -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Contacto</h2>
                <p>Para cualquier consulta sobre cambios y devoluciones:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li><strong>Correo:</strong> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</li>
                    <li><strong>Teléfono:</strong> {{ $settings['phone'] ?? 'Por definir' }}</li>
                    <li><strong>Horario:</strong> {{ $settings['business_hours'] ?? '' }}</li>
                </ul>
                <p class="mt-4">Si no estás conforme con la resolución, puedes presentar tu reclamo en nuestro <a href="{{ route('complaint.create') }}" class="text-rose-600 hover:text-rose-700 underline">Libro de Reclamaciones</a> o acudir a INDECOPI.</p>
            </section>

        </div>
    </div>
</div>
@endsection
