<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'Infrastructure/WooCommerceClient.php';

require_once 'Infrastructure/Parser.php';
require_once 'Infrastructure/CSVGenerator.php';

$consumerKey = '';
$consumerSecret = '';
$storeURL = 'http://localhost/ecomfeed/';

$wooCommerceClient = new WooCommerceClient($consumerKey, $consumerSecret, $storeURL);
$products = $wooCommerceClient->getProducts();

$headersAllegro = getHeadersAllegro();

$arrFields = [
    'id',
    'meta_data', ## extract ean code
    'categories', ## category | subcategory
    'sku', ## sku
    'stock_quantity',
    'price',
    'name', ## tytul oferty
    'images', ## images
    'description', ## opis oferta
    'shipping_class_id',
    'attributes'
];

$parser = new Parser($arrFields);
$parsedProduct = $parser->parseAllegro($products, $headersAllegro, $wooCommerceClient);
$csvName = date('YmdHis')."_productAllegro.csv";
$csvGenerator = new CSVGenerator();
$csvGenerator->generateCSV($parsedProduct, $headersAllegro, $csvName);

function getHeadersAllegro()
{
    return [
        "id_produkt_ean" => "ID produktu (EAN/UPC/ISBN/ISSN/ID produktu Allegro)",
        "kategoria_glowna" => "Kategoria główna",
        "podkategoria" => "Podkategoria",
        "sygnatura_sku_sprzedajacego" => "Sygnatura/SKU Sprzedającego",
        "liczba_sztuk" => "Liczba sztuk",
        "cena" => "Cena",
        "tytul_oferty" => "Tytuł oferty",
        "Zdjęcia" => "Zdjęcia",
        "opis_oferty" => "Opis oferty",
        "cennik_dostawy" => "Cennik dostawy",
        "czas_wysylki" => "Czas wysyłki",
        "kraj" => "Kraj",
        "wojewodztwo" => "Województwo",
        "kod_pocztowy" => "Kod pocztowy",
        "miejscowosc" => "Miejscowość",
        "opcje_faktury" => "Opcje faktury",
        "przedmiot_oferty" => "Przedmiot oferty",
        "stawka_vat_pl" => "Stawka VAT (PL)",
        "podstawa_wylaczenia_z_vat" => "Podstawa wyłączenia z VAT",
        "warunki_zwrotow" => "Warunki zwrotów",
        "warunki_reklamacji" => "Warunki reklamacji",
        "informacje_o_gwarancjach_opcjonalne" => "Informacje o gwarancjach (opcjonalne)",
        "stan" => "Stan",
        "waga_z_opakowaniem_kg" => "Waga (z opakowaniem) [kg]",
        "wersja_systemu_operacyjnego" => "Wersja systemu operacyjnego",
        "blokada_simlock" => "Blokada simlock",
        "kod_producenta" => "Kod producenta",
        "typ" => "Typ",
        "kolor" => "kolor",
        "przekątna_ekranu" => "Przekątna ekranu",
        "wbudowana_pamięć" => "Wbudowana pamięć",
        "pamięć_ram" => "Pamięć RAM",
        "system_operacyjny" => "System operacyjny",
        "pojemność_akumulatora_mah" => "Pojemność akumulatora [mAh]",
        "komunikacja" => "Komunikacja",
        "opcje_sim" => "Opcje SIM",
        "marka" => "Marka",
        "material_zewnetrzny" => "Materiał zewnętrzny",
        "model" => "Model",
        "cechy_dodatkowe" => "Cechy dodatkowe",
        "zapięcie" => "Zapięcie",
        "rozmiar" => "Rozmiar",
        "dlugosc_wkladki_cm" => "Długość wkładki [cm]",
        "oryginalne_opakowanie_producenta" => "Oryginalne opakowanie producenta",
        "wzor_dominujacy" => "Wzór dominujący",
        "material_wkladki" => "Materiał wkładki",
        "rodzaj_kolor_dominujacy" => "Rodzaj Kolor dominujący",
        "nosek" => "Nosek",
        "wysokosc" => "Wysokość",
        "obcas_platformy_cm" => "obcasa/platformy [cm]",
        "wysokosc_ocieplenie" => "Wysokość, Ocieplenie",
        "liczba_bolcow" => "Liczba bolców",
        "rodzaj_obcasa" => "Rodzaj obcasa",
        "fason" => "Fason",
        "okazja" => "Okazja",
        "wysokosc_cholewki" => "Wysokość cholewki",
        "rodzaj_wlosia" => "Rodzaj włosia",
        "materiał" => "Materiał",
    ];
}

?>
