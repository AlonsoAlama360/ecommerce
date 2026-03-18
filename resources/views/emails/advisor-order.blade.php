<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pedido Recibido</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        Pedido {{ $order->order_number }} recibido - Un asesor te contactar&aacute; pronto &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F1EC;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width: 560px; width: 100%;">

                    <!-- Logo -->
                    <tr>
                        <td style="padding: 0 0 32px; text-align: center;">
                            <img src="https://arixna.com/images/logo_arixna.png" alt="Arixna" style="height: 36px;">
                        </td>
                    </tr>

                    <!-- Main card -->
                    <tr>
                        <td style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

                                <!-- Header -->
                                <tr>
                                    <td style="padding: 40px 40px 24px;">
                                        <p style="margin: 0 0 6px; color: #C39563; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Pedido recibido</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">&iexcl;Gracias por tu pedido, {{ explode(' ', $order->customer_name)[0] }}!</h1>
                                        <p style="margin: 0; color: #888; font-size: 14px; line-height: 1.6;">Hemos recibido tu pedido <strong style="color: #333;">{{ $order->order_number }}</strong>. Pronto un asesor de venta se pondr&aacute; en contacto contigo por email o WhatsApp para coordinar el pago y env&iacute;o.</p>
                                    </td>
                                </tr>

                                <!-- Advisor badge -->
                                <tr>
                                    <td style="padding: 0 40px 24px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="background-color: #FDF6F0; border-radius: 12px; width: 100%;">
                                            <tr>
                                                <td style="padding: 16px 20px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="vertical-align: middle; padding-right: 14px;">
                                                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #D4A574, #C39563); border-radius: 10px; text-align: center; line-height: 40px; color: #fff; font-size: 18px;">&#9742;</div>
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                <p style="margin: 0 0 2px; color: #333; font-size: 13px; font-weight: 700;">Atenci&oacute;n personalizada</p>
                                                                <p style="margin: 0; color: #888; font-size: 12px; line-height: 1.4;">Un asesor te contactar&aacute; a la brevedad para ayudarte con tu compra.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Products -->
                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <p style="margin: 0 0 16px; color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Detalle del pedido</p>

                                        @foreach($order->items as $item)
                                        @php
                                            $imgUrl = null;
                                            if ($item->product && $item->product->primaryImage) {
                                                $imgUrl = $item->product->primaryImage->image_url;
                                                if ($imgUrl && !str_starts_with($imgUrl, 'http')) {
                                                    $imgUrl = rtrim(config('app.url'), '/') . '/' . ltrim($imgUrl, '/');
                                                }
                                            }
                                        @endphp
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 14px;">
                                            <tr>
                                                @if($imgUrl)
                                                <td style="vertical-align: top; padding-right: 14px; width: 52px;">
                                                    <img src="{{ $imgUrl }}" alt="{{ $item->product_name }}" width="48" height="48" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; display: block;">
                                                </td>
                                                @else
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <div style="width: 8px; height: 8px; background-color: #D4A574; border-radius: 50%; margin-top: 6px;"></div>
                                                </td>
                                                @endif
                                                <td style="vertical-align: top; width: 100%;">
                                                    <p style="margin: 0; color: #333; font-size: 14px; font-weight: 600;">{{ $item->product_name }}</p>
                                                    <p style="margin: 2px 0 0; color: #AAA; font-size: 12px;">Cant: {{ $item->quantity }} &times; S/ {{ number_format($item->unit_price, 2) }}</p>
                                                </td>
                                                <td style="vertical-align: top; text-align: right; white-space: nowrap; padding-left: 16px;">
                                                    <p style="margin: 0; color: #333; font-size: 14px; font-weight: 700;">S/ {{ number_format($item->line_total, 2) }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    </td>
                                </tr>

                                <!-- Totals -->
                                <tr>
                                    <td style="padding: 8px 40px 28px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px dashed #E5E0DA; padding-top: 14px;">
                                            @if($order->discount_amount > 0)
                                            <tr>
                                                <td style="padding: 3px 0; color: #888; font-size: 13px;">Descuento</td>
                                                <td style="padding: 3px 0; color: #4CAF50; font-size: 13px; text-align: right;">- S/ {{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 10px 0 0; color: #1A1A1A; font-size: 16px; font-weight: 800;">Total</td>
                                                <td style="padding: 10px 0 0; color: #1A1A1A; font-size: 18px; font-weight: 800; text-align: right;">S/ {{ number_format($order->total, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Order info grid -->
                                <tr>
                                    <td style="padding: 24px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="50%" style="vertical-align: top; padding-right: 12px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Estado</p>
                                                    <p style="margin: 0; color: #C39563; font-size: 13px; font-weight: 600;">Pendiente de asesor</p>
                                                </td>
                                                <td width="50%" style="vertical-align: top; padding-left: 12px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Fecha</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                                </td>
                                            </tr>
                                            @if($order->shipping_address)
                                            <tr>
                                                <td colspan="2" style="padding-top: 16px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Direcci&oacute;n de env&iacute;o</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $order->shipping_address }}</p>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($order->shipping_agency)
                                            <tr>
                                                <td colspan="2" style="padding-top: 16px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Agencia de env&iacute;o</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $order->shipping_agency }} - {{ $order->shipping_agency_address }}</p>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>

                                <!-- CTA -->
                                <tr>
                                    <td style="padding: 8px 40px 36px; text-align: center;">
                                        <a href="{{ rtrim(config('app.url'), '/') }}/mis-pedidos" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Ver mi pedido
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- Help link -->
                    @php $whatsapp = App\Models\SiteSetting::get('whatsapp_number'); @endphp
                    @if($whatsapp)
                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0; color: #999; font-size: 13px;">
                                &#191;Dudas sobre tu pedido?
                                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, consulta sobre mi pedido ' . $order->order_number) }}" target="_blank" style="color: #C39563; font-weight: 600; text-decoration: none;">Escr&iacute;benos</a>
                            </p>
                        </td>
                    </tr>
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0 0 4px; color: #BBB; font-size: 11px;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Este es un correo autom&aacute;tico, por favor no respondas.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
