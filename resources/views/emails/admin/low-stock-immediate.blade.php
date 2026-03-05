<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Stock</title>
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
                                        <div style="width: 64px; height: 64px; background-color: {{ $product->stock === 0 ? '#FEF2F2' : '#FFFBEB' }}; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#9888;&#65039;</div>
                                        <p style="margin: 0 0 6px; color: {{ $product->stock === 0 ? '#DC2626' : '#D97706' }}; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">
                                            {{ $product->stock === 0 ? 'Sin stock' : 'Stock bajo' }}
                                        </p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 20px; font-weight: 700;">{{ $product->name }}</h1>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 40px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: {{ $product->stock === 0 ? '#FEF2F2' : '#FFFBEB' }}; border-radius: 12px;">
                                            <tr>
                                                <td style="padding: 20px 24px; text-align: center;">
                                                    <p style="margin: 0 0 4px; color: #888; font-size: 12px;">Stock actual</p>
                                                    <p style="margin: 0; color: {{ $product->stock === 0 ? '#DC2626' : '#D97706' }}; font-size: 36px; font-weight: 800;">{{ $product->stock }}</p>
                                                    <p style="margin: 4px 0 0; color: #AAA; font-size: 11px;">Umbral configurado: {{ $threshold }} unidades</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="50%" style="padding: 8px 0;">
                                                    <p style="margin: 0 0 2px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase;">SKU</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $product->sku }}</p>
                                                </td>
                                                <td width="50%" style="padding: 8px 0;">
                                                    <p style="margin: 0 0 2px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase;">Categor&iacute;a</p>
                                                    <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $product->category?->name ?? 'Sin categor&iacute;a' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 40px 36px; text-align: center;">
                                        <a href="{{ url('/admin/products/' . $product->id . '/edit') }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Editar producto
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Alerta autom&aacute;tica de inventario &middot; {{ config('app.name') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
