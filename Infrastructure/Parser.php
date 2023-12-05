<?php

class Parser
{

    private $fields;

    public function __construct($fields) {
        $this->fields = $fields;
    }

    public function parseAllegro(
        array $products,
        array $arrHeaders,
        WooCommerceClient $wooCommerceClient
    ): array
    {
        $arrParser = [];
        $defaultAttributes = [
            'kraj' => "Polska",
            'wojewodztwo' => "Wielkopolskie",
            'kod_pocztowy' => "60-001",
            'miejscowosc' => "Poznań",
            'opcje_faktury' => "faktura VAT",
            'przedmiot_oferty' => "towar",
            'stawka_vat_pl' => "23%",
            'stan' => "Nowy"
        ];

        foreach ($products as $product) {
            $parsedProduct = [];
            foreach ($this->fields as $field) {

                $currentField = $product->$field ?? '';

                if ($currentField) {

                    if ($field === 'meta_data') {
                        $parsedProduct['id_produkt_ean'] = $this->extractEanFromMetaData($currentField);
                    }

                    if ($field === 'categories') {
                        $arrCategory = $this->extractCategorySubCategory($currentField);
                        $parsedProduct['kategoria_glowna'] = $arrCategory['kategoria_glowna'];
                        $parsedProduct['podkategoria'] = $arrCategory['podkategoria'];
                    }

                    if ($field === 'sku') {
                        $parsedProduct['sygnatura_sku_sprzedajacego'] = $currentField;
                    }

                    if ($field === 'stock_quantity') {
                        $parsedProduct['liczba_sztuk'] = $currentField;
                    }

                    if ($field === 'price') {
                        $parsedProduct['cena'] = $currentField;
                    }

                    if ($field === 'name') {
                        $parsedProduct['tytul_oferty'] = $this->escapeSpecialChars($currentField);
                    }

                    if ($field === 'images') {
                       $parsedProduct['Zdjęcia'] = $this->extractImages($currentField);
                    }

                    if ($field === 'description') {
                        $parsedProduct['opis_oferty'] = $this->escapeSpecialChars($currentField);
                    }

                    if ($field === 'shipping_class_id') {
                        $parsedProduct['czas_wysylki'] = $this->extractShippingTitle($currentField, $wooCommerceClient);
                    }

                    if ($field === "weight") {
                        $parsedProduct['waga_z_opakowaniem_kg'] = $currentField;
                    }

                    if ($field === 'attributes') {
                        $arrAttributes = $this->parseProductAttributes($currentField);
                        if (is_iterable($arrAttributes)) {
                            $parsedProduct = array_merge($parsedProduct, $arrAttributes);
                        }

                    }
                }

            }
            if (is_iterable($parsedProduct)) {
                $parsedProduct = array_merge($parsedProduct, $defaultAttributes);
                $arrParser[] = $parsedProduct;
            }
        }
        return $arrParser;
    }

    public function getFields() {
        return $this->fields;
    }

    public function extractEanFromMetaData($currentField) {
        $keys = array_column($currentField, 'key');
        $values = array_column($currentField, 'value');
        $index = array_search('ean_code', $keys);
        return $index !== false ? $values[$index] : null;
    }

    public function extractCategorySubCategory($currentField) {

        $category = null;
        $subCategory = null;

        if (is_iterable($currentField)) {
            $category = $currentField[0]->name;

            $arrSubCategoryNames = array_map(function($obj) {
                return $obj->name;
            }, $currentField);
            $subCategory = implode(' > ', $arrSubCategoryNames);
        }

        return [
            'kategoria_glowna' => $category,
            'podkategoria' => $subCategory,
        ];
    }

    public function extractImages($arrImages){
        $arrSrc = array_map(function($image) {
            return $image->src;
        }, $arrImages);

        return implode('|', $arrSrc);
    }

    public function escapeSpecialChars($text) {

        //$text = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = '"' . str_replace('"', '""', $text) . '"';
        return $text;
    }

    public function extractShippingTitle(
        $idShipping,
        WooCommerceClient $wooCommerceClient
    ){
        $shippingClasses = $wooCommerceClient->getShippingClass($idShipping);
        $shippingTimeTitle = null;
        if (is_iterable($shippingClasses)) {
            $shippingTimeTitle = $shippingClasses[0]->name;
        }
        return $shippingTimeTitle;
    }

    public function parseProductAttributes($arrAttributes){

        $arrAttributeParser = [];
        foreach ($arrAttributes as $attribute) {
            $attributeName = $attribute->name;
            $attributeCode = $this->transformToSlug($attributeName);
            $attributeValue = $attribute->options[0];

            switch ($attributeCode) {
                case 'waga_produktu_z_opakowaniem':
                    $arrAttributeParser['waga_z_opakowaniem_kg'] = $attributeValue;
                    break;
                case 'pojemność_akumulatora':
                    $arrAttributeParser['pojemność_akumulatora_mah'] = $attributeValue;
                    break;
                default:
                    $arrAttributeParser[$attributeCode] = $attributeValue;
                    break;
            }
        }
        return $arrAttributeParser;
    }

    public function transformToSlug($slug) {
        $slug = strtolower(
            preg_replace('/\s+/', '_', $slug)
        );
        $slug = preg_replace('/[^a-z0-9-_ąćęłńóśźż]/u', '', $slug);
        $slug = preg_replace('/_+/', '_', $slug);
        return $slug;
    }
}
