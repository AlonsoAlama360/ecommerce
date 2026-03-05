<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F1EC;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width: 560px; width: 100%;">

                    <tr>
                        <td style="padding: 0 0 32px; text-align: center;">
                            <img src="https://arixna.com/images/logo_arixna.png" alt="Arixna" style="height: 36px;">
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td style="padding: 40px 40px 24px; text-align: center;">
                                        <div style="width: 64px; height: 64px; background-color: #ECFDF5; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#128176;</div>
                                        <p style="margin: 0 0 6px; color: #059669; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Nueva venta</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">{{ $order->order_number }}</h1>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="50%" style="vertical-align: top; padding-right: 12px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Cliente</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $order->customer_name }}</p>
                                                    <p style="margin: 2px 0 0; color: #888; font-size: 12px;">{{ $order->customer_email }}</p>
                                                </td>
                                                <td width="50%" style="vertical-align: top; padding-left: 12px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Total</p>
                                                    <p style="margin: 0; color: #059669; font-size: 22px; font-weight: 800;">S/ {{ number_format($order->total, 2) }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <p style="margin: 0 0 12px; color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Productos ({{ $order->items->count() }})</p>
                                        @foreach($order->items as $item)
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 10px;">
                                                    <div style="width: 8px; height: 8px; background-color: #D4A574; border-radius: 50%; margin-top: 5px;"></div>
                                                </td>
                                                <td style="vertical-align: top; width: 100%;">
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $item->product_name }}</p>
                                                    <p style="margin: 2px 0 0; color: #AAA; font-size: 11px;">{{ $item->quantity }} x S/ {{ number_format($item->unit_price, 2) }}</p>
                                                </td>
                                                <td style="vertical-align: top; text-align: right; white-space: nowrap;">
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 700;">S/ {{ number_format($item->line_total, 2) }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 16px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 4px 0; color: #888; font-size: 12px;">Pago</td>
                                                <td style="padding: 4px 0; color: #333; font-size: 12px; text-align: right; font-weight: 600;">{{ $order->payment_method_label }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #888; font-size: 12px;">Fuente</td>
                                                <td style="padding: 4px 0; color: #333; font-size: 12px; text-align: right; font-weight: 600;">{{ $order->source === 'web' ? 'Tienda web' : 'Admin' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 8px 40px 36px; text-align: center;">
                                        <a href="{{ url('/admin/orders/' . $order->id) }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Ver pedido
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Notificaci&oacute;n autom&aacute;tica del sistema &middot; {{ config('app.name') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
