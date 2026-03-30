<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * The base URL that QR codes will resolve to.
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('app.url'), '/');
    }

    /**
     * Generate a QR code for a seed batch.
     * The QR code encodes a URL: {APP_URL}/assets/batch/{batch_code}
     *
     * @return string The storage path of the generated QR code
     */
    public function generateForSeedBatch(string $batchCode): string
    {
        $url = $this->baseUrl.'/assets/batch/'.$batchCode;
        $filename = 'qrcodes/batches/'.Str::slug($batchCode).'.svg';

        $svg = $this->generateSvgQrCode($url, $batchCode);
        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }

    /**
     * Generate a QR code for a plant.
     * The QR code encodes a URL: {APP_URL}/assets/plant/{uuid}
     *
     * @return string The storage path of the generated QR code
     */
    public function generateForPlant(string $uuid): string
    {
        $url = $this->baseUrl.'/assets/plant/'.$uuid;
        $filename = 'qrcodes/plants/'.$uuid.'.svg';

        $svg = $this->generateSvgQrCode($url, $uuid);
        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }

    /**
     * Get the public URL for a QR code stored at the given path.
     */
    public function getPublicUrl(string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Generate a simple SVG QR code placeholder.
     * In production, replace this with a proper QR library like `chillerlan/php-qrcode`.
     *
     * This generates a valid SVG with the encoded data as a data attribute
     * and a visual placeholder that clearly shows it needs a real QR library.
     * The API endpoint will use the `chillerlan/php-qrcode` package when available.
     */
    protected function generateSvgQrCode(string $data, string $label): string
    {
        // This is a placeholder SVG generator.
        // In production, install: composer require chillerlan/php-qrcode
        // and replace this with actual QR matrix generation.

        $escapedData = htmlspecialchars($data, ENT_XML1);
        $escapedLabel = htmlspecialchars($label, ENT_XML1);

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="230" viewBox="0 0 200 230" data-qr-content="{$escapedData}">
  <rect width="200" height="230" fill="white" stroke="#333" stroke-width="2"/>
  <rect x="10" y="10" width="180" height="180" fill="#f0f0f0" stroke="#999" stroke-width="1"/>

  <!-- QR Finder Patterns (top-left) -->
  <rect x="20" y="20" width="40" height="40" fill="#000"/>
  <rect x="25" y="25" width="30" height="30" fill="#fff"/>
  <rect x="30" y="30" width="20" height="20" fill="#000"/>

  <!-- QR Finder Patterns (top-right) -->
  <rect x="140" y="20" width="40" height="40" fill="#000"/>
  <rect x="145" y="25" width="30" height="30" fill="#fff"/>
  <rect x="150" y="30" width="20" height="20" fill="#000"/>

  <!-- QR Finder Patterns (bottom-left) -->
  <rect x="20" y="140" width="40" height="40" fill="#000"/>
  <rect x="25" y="145" width="30" height="30" fill="#fff"/>
  <rect x="30" y="150" width="20" height="20" fill="#000"/>

  <!-- Decorative data modules -->
  <rect x="70" y="20" width="8" height="8" fill="#000"/>
  <rect x="86" y="20" width="8" height="8" fill="#000"/>
  <rect x="102" y="20" width="8" height="8" fill="#000"/>
  <rect x="70" y="36" width="8" height="8" fill="#000"/>
  <rect x="86" y="36" width="8" height="8" fill="#000"/>
  <rect x="70" y="70" width="8" height="8" fill="#000"/>
  <rect x="86" y="70" width="8" height="8" fill="#000"/>
  <rect x="102" y="70" width="8" height="8" fill="#000"/>
  <rect x="118" y="70" width="8" height="8" fill="#000"/>
  <rect x="102" y="86" width="8" height="8" fill="#000"/>
  <rect x="70" y="102" width="8" height="8" fill="#000"/>
  <rect x="86" y="102" width="8" height="8" fill="#000"/>
  <rect x="118" y="86" width="8" height="8" fill="#000"/>
  <rect x="134" y="86" width="8" height="8" fill="#000"/>
  <rect x="118" y="102" width="8" height="8" fill="#000"/>
  <rect x="70" y="118" width="8" height="8" fill="#000"/>
  <rect x="86" y="118" width="8" height="8" fill="#000"/>
  <rect x="102" y="118" width="8" height="8" fill="#000"/>
  <rect x="134" y="118" width="8" height="8" fill="#000"/>
  <rect x="102" y="140" width="8" height="8" fill="#000"/>
  <rect x="118" y="140" width="8" height="8" fill="#000"/>
  <rect x="134" y="140" width="8" height="8" fill="#000"/>
  <rect x="150" y="140" width="8" height="8" fill="#000"/>
  <rect x="134" y="156" width="8" height="8" fill="#000"/>
  <rect x="150" y="156" width="8" height="8" fill="#000"/>

  <!-- Label -->
  <text x="100" y="215" text-anchor="middle" font-family="monospace" font-size="10" fill="#333">{$escapedLabel}</text>
</svg>
SVG;
    }
}
