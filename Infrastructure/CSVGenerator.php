<?php
class CSVGenerator {
    public function generateCSV(
        $parsedProducts,
        $arrHeader,
        $filename
    ) {

        $keysHeader = array_keys($arrHeader);
        $valuesHeader = array_values($arrHeader);

        $fp = fopen($filename, 'w');

        // Add header to CSV file
        fputcsv($fp, $valuesHeader);

        $processedProducts = array_map(function ($product) use ($keysHeader) {

            $productEmptyValues = array_fill_keys($keysHeader, '');
            $productFiltered = array_intersect_key($product, array_flip($keysHeader));
            return array_merge($productEmptyValues, $productFiltered);
        }, $parsedProducts);

        foreach ($processedProducts as $product) {
            fputcsv($fp, $product);
        }
        fclose($fp);
    }
}
?>
