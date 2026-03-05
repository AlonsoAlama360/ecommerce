<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Alerta de Stock Bajo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        {{ $products->count() }} producto(s) con stock igual o menor a {{ $threshold }} unidades &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
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
                                    <td style="padding: 40px 40px 24px; text-align: center;">
                                        <div style="width: 64px; height: 64px; background-color: #FEF2F2; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#9888;&#65039;</div>
                                        <p style="margin: 0 0 6px; color: #DC2626; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Alerta de stock</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">{{ $products->count() }} producto(s) con stock bajo</h1>
                                        <p style="margin: 0; color: #888; font-size: 14px; line-height: 1.5;">Los siguientes productos tienen stock igual o menor a <strong style="color: #333;">{{ $threshold }} unidades</strong>.</p>
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Product list -->
                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <!-- Table header -->
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
                                            <tr>
                                                <td style="color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 8px;">Producto</td>
                                                <td style="color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 8px; text-align: center; width: 60px;">SKU</td>
                                                <td style="color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 8px; text-align: right; width: 60px;">Stock</td>
                                            </tr>
                                        </table>

                                        @foreach($products as $product)
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 10px; border-radius: 8px; {{ $product->stock === 0 ? 'background-color: #FEF2F2;' : 'background-color: #FFFBEB;' }}">
                                            <tr>
                                                <td style="padding: 12px 14px; vertical-align: middle;">
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $product->name }}</p>
                                                    <p style="margin: 2px 0 0; color: #999; font-size: 11px;">{{ $product->category?->name ?? 'Sin categor&iacute;a' }}</p>
                                                </td>
                                                <td style="padding: 12px 8px; vertical-align: middle; text-align: center;">
                                                    <p style="margin: 0; color: #888; font-size: 12px;">{{ $product->sku }}</p>
                                                </td>
                                                <td style="padding: 12px 14px; vertical-align: middle; text-align: right;">
                                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 700; {{ $product->stock === 0 ? 'background-color: #FEE2E2; color: #DC2626;' : 'background-color: #FEF3C7; color: #D97706;' }}">
                                                        {{ $product->stock }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    </td>
                                </tr>

                                <!-- Summary -->
                                <tr>
                                    <td style="padding: 16px 40px 8px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px dashed #E5E0DA; padding-top: 14px;">
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <span style="display: inline-block; width: 10px; height: 10px; background-color: #FEE2E2; border-radius: 3px; margin-right: 6px; vertical-align: middle;"></span>
                                                    <span style="color: #DC2626; font-size: 13px; font-weight: 600;">{{ $products->where('stock', 0)->count() }} sin stock</span>
                                                </td>
                                                <td style="padding: 4px 0; text-align: right;">
                                                    <span style="display: inline-block; width: 10px; height: 10px; background-color: #FEF3C7; border-radius: 3px; margin-right: 6px; vertical-align: middle;"></span>
                                                    <span style="color: #D97706; font-size: 13px; font-weight: 600;">{{ $products->where('stock', '>', 0)->count() }} stock bajo</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- CTA -->
                                <tr>
                                    <td style="padding: 24px 40px 36px; text-align: center;">
                                        <a href="{{ url('/admin/products') }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Gestionar productos
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0 0 4px; color: #BBB; font-size: 11px;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Alerta autom&aacute;tica del sistema de inventario.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
