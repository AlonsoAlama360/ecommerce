@extends('layouts.app')

@section('title', 'Términos y Condiciones - Arixna')
@section('meta_description', 'Términos y condiciones de uso de la tienda online Arixna. Conoce tus derechos y obligaciones al realizar compras en nuestra plataforma.')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-3">Términos y Condiciones</h1>
            <p class="text-gray-500">Última actualización: {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 space-y-10 text-gray-700 leading-relaxed">

            <!-- 1. Información del proveedor -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Identificación del proveedor</h2>
                <p>La tienda online <strong>{{ $settings['business_name'] ?? 'Arixna' }}</strong> es operada por:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li><strong>Razón Social:</strong> {{ $settings['legal_name'] ?? 'Arixna E.I.R.L.' }}</li>
                    <li><strong>RUC:</strong> {{ $settings['ruc'] ?? 'Por definir' }}</li>
                    <li><strong>Domicilio fiscal:</strong> {{ $settings['address'] ?? 'Por definir' }}</li>
                    <li><strong>Correo electrónico:</strong> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</li>
                    <li><strong>Teléfono:</strong> {{ $settings['phone'] ?? 'Por definir' }}</li>
                </ul>
            </section>

            <!-- 2. Objeto -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Objeto</h2>
                <p>Los presentes Términos y Condiciones regulan el acceso y uso del sitio web <strong>arixna.com</strong>, así como la contratación de productos ofrecidos a través de la plataforma. Al acceder, navegar o realizar una compra, el usuario acepta íntegramente estos términos.</p>
            </section>

            <!-- 3. Productos y precios -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Productos y precios</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Todos los productos ofrecidos incluyen una descripción detallada con sus características principales, imágenes referenciales y precio.</li>
                    <li>Los precios están expresados en <strong>Soles (PEN)</strong> e incluyen el Impuesto General a las Ventas (IGV).</li>
                    <li>Arixna se reserva el derecho de modificar los precios en cualquier momento, sin que ello afecte los pedidos ya confirmados.</li>
                    <li>Las imágenes de los productos son referenciales. Pueden existir variaciones menores de color debido a la configuración del monitor.</li>
                    <li>La disponibilidad de los productos está sujeta a stock.</li>
                </ul>
            </section>

            <!-- 4. Proceso de compra -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Proceso de compra</h2>
                <ol class="space-y-2 list-decimal list-inside">
                    <li>El usuario selecciona los productos deseados y los agrega al carrito de compras.</li>
                    <li>Revisa el resumen de su pedido, incluyendo cantidades, precios y costos de envío.</li>
                    <li>Completa sus datos personales y dirección de entrega.</li>
                    <li>Selecciona el método de pago y realiza el pago correspondiente.</li>
                    <li>Recibe una confirmación del pedido vía correo electrónico con el número de orden.</li>
                </ol>
                <p class="mt-3">El contrato de compraventa se perfecciona con la confirmación del pedido por parte de Arixna.</p>
            </section>

            <!-- 5. Medios de pago -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Medios de pago</h2>
                <p>Arixna acepta los siguientes medios de pago:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li>Tarjetas de crédito y débito (Visa, Mastercard, American Express, Diners Club)</li>
                    <li>Transferencia bancaria</li>
                    <li>Yape / Plin</li>
                </ul>
                <p class="mt-3">Los pagos con tarjeta son procesados de forma segura a través de nuestra pasarela de pago. Arixna no almacena datos de tarjetas de crédito o débito.</p>
            </section>

            <!-- 6. Envíos y entregas -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">6. Envíos y entregas</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Realizamos envíos a todo el Perú.</li>
                    <li>El plazo de entrega estimado es de <strong>3 a 7 días hábiles</strong> en Lima Metropolitana y de <strong>5 a 12 días hábiles</strong> en provincias, contados desde la confirmación del pago.</li>
                    <li>Los costos de envío se calculan según el destino y se muestran antes de confirmar la compra.</li>
                    <li>El cliente recibirá un número de seguimiento para rastrear su pedido.</li>
                    <li>Arixna no se responsabiliza por retrasos causados por el transportista, desastres naturales o causas de fuerza mayor.</li>
                </ul>
            </section>

            <!-- 7. Derecho de desistimiento -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">7. Derecho de desistimiento</h2>
                <p>De acuerdo con el Código de Protección y Defensa del Consumidor (Ley N° 29571), el consumidor tiene derecho a devolver el producto dentro de los <strong>primeros 7 días calendario</strong> desde la recepción del mismo, siempre que:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li>El producto se encuentre sin uso, en su empaque original y con todas sus etiquetas.</li>
                    <li>Se presente el comprobante de pago.</li>
                </ul>
                <p class="mt-3">Para más información, consulta nuestra <a href="{{ route('legal.returns') }}" class="text-rose-600 hover:text-rose-700 underline">Política de Cambios y Devoluciones</a>.</p>
            </section>

            <!-- 8. Garantía -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">8. Garantía</h2>
                <p>Todos los productos comercializados por Arixna cuentan con garantía legal conforme a la Ley N° 29571. Si el producto presenta defectos de fábrica, el consumidor podrá solicitar la reparación, reposición o devolución del monto pagado.</p>
            </section>

            <!-- 9. Protección de datos personales -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">9. Protección de datos personales</h2>
                <p>En cumplimiento de la Ley N° 29733, Ley de Protección de Datos Personales, y su Reglamento, Arixna informa que:</p>
                <ul class="mt-3 space-y-2 list-disc list-inside">
                    <li>Los datos personales proporcionados serán tratados para la gestión de pedidos, comunicaciones sobre el estado de la compra y, en caso de consentimiento, para el envío de promociones y novedades.</li>
                    <li>Los datos no serán compartidos con terceros, salvo los necesarios para el procesamiento de pagos y envíos.</li>
                    <li>El usuario puede ejercer sus derechos ARCO (Acceso, Rectificación, Cancelación y Oposición) enviando un correo a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong>.</li>
                </ul>
            </section>

            <!-- 10. Propiedad intelectual -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">10. Propiedad intelectual</h2>
                <p>Todo el contenido del sitio web (textos, imágenes, logotipos, diseños, código fuente) es propiedad de Arixna o de sus respectivos titulares. Queda prohibida su reproducción, distribución o uso sin autorización expresa.</p>
            </section>

            <!-- 11. Limitación de responsabilidad -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">11. Limitación de responsabilidad</h2>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Arixna no se responsabiliza por interrupciones temporales del servicio por mantenimiento o causas técnicas ajenas.</li>
                    <li>No se asume responsabilidad por el uso inadecuado de los productos adquiridos.</li>
                    <li>Arixna se reserva el derecho de cancelar pedidos en caso de detectar actividades fraudulentas.</li>
                </ul>
            </section>

            <!-- 12. Modificaciones -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">12. Modificaciones</h2>
                <p>Arixna se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones serán publicadas en esta misma página y entrarán en vigencia desde su publicación. Se recomienda revisarlos periódicamente.</p>
            </section>

            <!-- 13. Legislación aplicable -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">13. Legislación aplicable y resolución de conflictos</h2>
                <p>Los presentes Términos y Condiciones se rigen por las leyes de la República del Perú. Cualquier controversia será resuelta conforme al Código de Protección y Defensa del Consumidor (Ley N° 29571) ante las autoridades competentes (INDECOPI).</p>
                <p class="mt-3">Asimismo, ponemos a tu disposición nuestro <a href="{{ route('complaint.create') }}" class="text-rose-600 hover:text-rose-700 underline">Libro de Reclamaciones</a> virtual.</p>
            </section>

            <!-- 14. Contacto -->
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">14. Contacto</h2>
                <p>Para cualquier consulta relacionada con estos Términos y Condiciones, puedes comunicarte con nosotros a través de:</p>
                <ul class="mt-3 space-y-1 list-disc list-inside">
                    <li><strong>Correo electrónico:</strong> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</li>
                    <li><strong>Teléfono:</strong> {{ $settings['phone'] ?? 'Por definir' }}</li>
                    <li><strong>Horario de atención:</strong> {{ $settings['business_hours'] ?? '' }}</li>
                </ul>
            </section>

        </div>
    </div>
</div>
@endsection
