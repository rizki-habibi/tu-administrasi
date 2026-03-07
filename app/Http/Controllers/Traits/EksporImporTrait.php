<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait EksporImporTrait
{
    /**
     * Generate CSV response from data.
     *
     * @param string $filename  e.g. 'data_laporan_20260308.csv'
     * @param array  $headers   e.g. ['No', 'Judul', 'Kategori']
     * @param Collection|array $rows  array of arrays
     */
    protected function eksporCsv(string $filename, array $headers, $rows)
    {
        $callback = function () use ($headers, $rows) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($f, $headers);
            foreach ($rows as $row) {
                fputcsv($f, $row);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Parse uploaded CSV and return rows as array of associative arrays.
     */
    protected function parseCsvImport(Request $request, string $fieldName = 'file'): array
    {
        $request->validate([
            $fieldName => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file($fieldName);
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return [];
        }

        // Normalize headers
        $header = array_map(fn($h) => strtolower(trim(str_replace(' ', '_', $h))), $header);

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;
            $rows[] = array_combine($header, array_pad($row, count($header), ''));
        }
        fclose($handle);

        return $rows;
    }
}
