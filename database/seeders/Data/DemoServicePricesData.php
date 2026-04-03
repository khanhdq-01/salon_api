<?php

namespace Database\Seeders\Data;

use Database\Seeders\Support\DemoServiceCatalog;

/**
 * Bảng giá cố định cho từng salon (30 salon × 14 dịch vụ).
 */
final class DemoServicePricesData
{
    /** @var list<array<string, int>> */
    private const PRICES = [
        ['cut_male' => 90200, 'cut_female' => 151700, 'wash' => 65600, 'massage' => 143500, 'shave' => 41000, 'ear_clean' => 61500, 'dye' => 820000, 'highlight' => 820000, 'perm' => 984000, 'straighten' => 820000, 'styling' => 114800, 'recovery' => 328000, 'oil_treatment' => 225500, 'keratin' => 1148000],
        ['cut_male' => 92400, 'cut_female' => 155400, 'wash' => 67200, 'massage' => 147000, 'shave' => 42000, 'ear_clean' => 63000, 'dye' => 840000, 'highlight' => 840000, 'perm' => 1008000, 'straighten' => 840000, 'styling' => 117600, 'recovery' => 336000, 'oil_treatment' => 231000, 'keratin' => 1176000],
        ['cut_male' => 94600, 'cut_female' => 159100, 'wash' => 68800, 'massage' => 150500, 'shave' => 43000, 'ear_clean' => 64500, 'dye' => 860000, 'highlight' => 860000, 'perm' => 1032000, 'straighten' => 860000, 'styling' => 120400, 'recovery' => 344000, 'oil_treatment' => 236500, 'keratin' => 1204000],
        ['cut_male' => 96800, 'cut_female' => 162800, 'wash' => 70400, 'massage' => 154000, 'shave' => 44000, 'ear_clean' => 66000, 'dye' => 880000, 'highlight' => 880000, 'perm' => 1056000, 'straighten' => 880000, 'styling' => 123200, 'recovery' => 352000, 'oil_treatment' => 242000, 'keratin' => 1232000],
        ['cut_male' => 99000, 'cut_female' => 166500, 'wash' => 72000, 'massage' => 157500, 'shave' => 45000, 'ear_clean' => 67500, 'dye' => 900000, 'highlight' => 900000, 'perm' => 1080000, 'straighten' => 900000, 'styling' => 126000, 'recovery' => 360000, 'oil_treatment' => 247500, 'keratin' => 1260000],
        ['cut_male' => 101200, 'cut_female' => 170200, 'wash' => 73600, 'massage' => 161000, 'shave' => 46000, 'ear_clean' => 69000, 'dye' => 920000, 'highlight' => 920000, 'perm' => 1104000, 'straighten' => 920000, 'styling' => 128800, 'recovery' => 368000, 'oil_treatment' => 253000, 'keratin' => 1288000],
        ['cut_male' => 103400, 'cut_female' => 173900, 'wash' => 75200, 'massage' => 164500, 'shave' => 47000, 'ear_clean' => 70500, 'dye' => 940000, 'highlight' => 940000, 'perm' => 1128000, 'straighten' => 940000, 'styling' => 131600, 'recovery' => 376000, 'oil_treatment' => 258500, 'keratin' => 1316000],
        ['cut_male' => 105600, 'cut_female' => 177600, 'wash' => 76800, 'massage' => 168000, 'shave' => 48000, 'ear_clean' => 72000, 'dye' => 960000, 'highlight' => 960000, 'perm' => 1152000, 'straighten' => 960000, 'styling' => 134400, 'recovery' => 384000, 'oil_treatment' => 264000, 'keratin' => 1344000],
        ['cut_male' => 107800, 'cut_female' => 181300, 'wash' => 78400, 'massage' => 171500, 'shave' => 49000, 'ear_clean' => 73500, 'dye' => 980000, 'highlight' => 980000, 'perm' => 1176000, 'straighten' => 980000, 'styling' => 137200, 'recovery' => 392000, 'oil_treatment' => 269500, 'keratin' => 1372000],
        ['cut_male' => 110000, 'cut_female' => 185000, 'wash' => 80000, 'massage' => 175000, 'shave' => 50000, 'ear_clean' => 75000, 'dye' => 1000000, 'highlight' => 1000000, 'perm' => 1200000, 'straighten' => 1000000, 'styling' => 140000, 'recovery' => 400000, 'oil_treatment' => 275000, 'keratin' => 1400000],
        ['cut_male' => 112200, 'cut_female' => 188700, 'wash' => 81600, 'massage' => 178500, 'shave' => 51000, 'ear_clean' => 76500, 'dye' => 1020000, 'highlight' => 1020000, 'perm' => 1224000, 'straighten' => 1020000, 'styling' => 142800, 'recovery' => 408000, 'oil_treatment' => 280500, 'keratin' => 1428000],
        ['cut_male' => 114400, 'cut_female' => 192400, 'wash' => 83200, 'massage' => 182000, 'shave' => 52000, 'ear_clean' => 78000, 'dye' => 1040000, 'highlight' => 1040000, 'perm' => 1248000, 'straighten' => 1040000, 'styling' => 145600, 'recovery' => 416000, 'oil_treatment' => 286000, 'keratin' => 1456000],
        ['cut_male' => 116600, 'cut_female' => 196100, 'wash' => 84800, 'massage' => 185500, 'shave' => 53000, 'ear_clean' => 79500, 'dye' => 1060000, 'highlight' => 1060000, 'perm' => 1272000, 'straighten' => 1060000, 'styling' => 148400, 'recovery' => 424000, 'oil_treatment' => 291500, 'keratin' => 1484000],
        ['cut_male' => 118800, 'cut_female' => 199800, 'wash' => 86400, 'massage' => 189000, 'shave' => 54000, 'ear_clean' => 81000, 'dye' => 1080000, 'highlight' => 1080000, 'perm' => 1296000, 'straighten' => 1080000, 'styling' => 151200, 'recovery' => 432000, 'oil_treatment' => 297000, 'keratin' => 1512000],
        ['cut_male' => 121000, 'cut_female' => 203500, 'wash' => 88000, 'massage' => 192500, 'shave' => 55000, 'ear_clean' => 82500, 'dye' => 1100000, 'highlight' => 1100000, 'perm' => 1320000, 'straighten' => 1100000, 'styling' => 154000, 'recovery' => 440000, 'oil_treatment' => 302500, 'keratin' => 1540000],
        ['cut_male' => 123200, 'cut_female' => 207200, 'wash' => 89600, 'massage' => 196000, 'shave' => 56000, 'ear_clean' => 84000, 'dye' => 1120000, 'highlight' => 1120000, 'perm' => 1344000, 'straighten' => 1120000, 'styling' => 156800, 'recovery' => 448000, 'oil_treatment' => 308000, 'keratin' => 1568000],
        ['cut_male' => 125400, 'cut_female' => 210900, 'wash' => 91200, 'massage' => 199500, 'shave' => 57000, 'ear_clean' => 85500, 'dye' => 1140000, 'highlight' => 1140000, 'perm' => 1368000, 'straighten' => 1140000, 'styling' => 159600, 'recovery' => 456000, 'oil_treatment' => 313500, 'keratin' => 1596000],
        ['cut_male' => 127600, 'cut_female' => 214600, 'wash' => 92800, 'massage' => 203000, 'shave' => 58000, 'ear_clean' => 87000, 'dye' => 1160000, 'highlight' => 1160000, 'perm' => 1392000, 'straighten' => 1160000, 'styling' => 162400, 'recovery' => 464000, 'oil_treatment' => 319000, 'keratin' => 1624000],
        ['cut_male' => 129800, 'cut_female' => 218300, 'wash' => 94400, 'massage' => 206500, 'shave' => 59000, 'ear_clean' => 88500, 'dye' => 1180000, 'highlight' => 1180000, 'perm' => 1416000, 'straighten' => 1180000, 'styling' => 165200, 'recovery' => 472000, 'oil_treatment' => 324500, 'keratin' => 1652000],
        ['cut_male' => 132000, 'cut_female' => 222000, 'wash' => 96000, 'massage' => 210000, 'shave' => 60000, 'ear_clean' => 90000, 'dye' => 1200000, 'highlight' => 1200000, 'perm' => 1440000, 'straighten' => 1200000, 'styling' => 168000, 'recovery' => 480000, 'oil_treatment' => 330000, 'keratin' => 1680000],
        ['cut_male' => 93500, 'cut_female' => 157250, 'wash' => 68000, 'massage' => 148750, 'shave' => 42500, 'ear_clean' => 63750, 'dye' => 850000, 'highlight' => 850000, 'perm' => 1020000, 'straighten' => 850000, 'styling' => 119000, 'recovery' => 340000, 'oil_treatment' => 233750, 'keratin' => 1190000],
        ['cut_male' => 100100, 'cut_female' => 168350, 'wash' => 72800, 'massage' => 159250, 'shave' => 45500, 'ear_clean' => 68250, 'dye' => 910000, 'highlight' => 910000, 'perm' => 1092000, 'straighten' => 910000, 'styling' => 127400, 'recovery' => 364000, 'oil_treatment' => 250250, 'keratin' => 1274000],
        ['cut_male' => 106700, 'cut_female' => 179450, 'wash' => 77600, 'massage' => 169750, 'shave' => 48500, 'ear_clean' => 72750, 'dye' => 970000, 'highlight' => 970000, 'perm' => 1164000, 'straighten' => 970000, 'styling' => 135800, 'recovery' => 388000, 'oil_treatment' => 266750, 'keratin' => 1358000],
        ['cut_male' => 113300, 'cut_female' => 190550, 'wash' => 82400, 'massage' => 180250, 'shave' => 51500, 'ear_clean' => 77250, 'dye' => 1030000, 'highlight' => 1030000, 'perm' => 1236000, 'straighten' => 1030000, 'styling' => 144200, 'recovery' => 412000, 'oil_treatment' => 283250, 'keratin' => 1442000],
        ['cut_male' => 119900, 'cut_female' => 201650, 'wash' => 87200, 'massage' => 190750, 'shave' => 54500, 'ear_clean' => 81750, 'dye' => 1090000, 'highlight' => 1090000, 'perm' => 1308000, 'straighten' => 1090000, 'styling' => 152600, 'recovery' => 436000, 'oil_treatment' => 299750, 'keratin' => 1526000],
        ['cut_male' => 126500, 'cut_female' => 212750, 'wash' => 92000, 'massage' => 201250, 'shave' => 57500, 'ear_clean' => 86250, 'dye' => 1150000, 'highlight' => 1150000, 'perm' => 1380000, 'straighten' => 1150000, 'styling' => 161000, 'recovery' => 460000, 'oil_treatment' => 316250, 'keratin' => 1610000],
        ['cut_male' => 97900, 'cut_female' => 164650, 'wash' => 71200, 'massage' => 155750, 'shave' => 44500, 'ear_clean' => 66750, 'dye' => 890000, 'highlight' => 890000, 'perm' => 1068000, 'straighten' => 890000, 'styling' => 124600, 'recovery' => 356000, 'oil_treatment' => 244750, 'keratin' => 1246000],
        ['cut_male' => 104500, 'cut_female' => 175750, 'wash' => 76000, 'massage' => 166250, 'shave' => 47500, 'ear_clean' => 71250, 'dye' => 950000, 'highlight' => 950000, 'perm' => 1140000, 'straighten' => 950000, 'styling' => 133000, 'recovery' => 380000, 'oil_treatment' => 261250, 'keratin' => 1330000],
        ['cut_male' => 111100, 'cut_female' => 186850, 'wash' => 80800, 'massage' => 176750, 'shave' => 50500, 'ear_clean' => 75750, 'dye' => 1010000, 'highlight' => 1010000, 'perm' => 1212000, 'straighten' => 1010000, 'styling' => 141400, 'recovery' => 404000, 'oil_treatment' => 277750, 'keratin' => 1414000],
        ['cut_male' => 117700, 'cut_female' => 197950, 'wash' => 85600, 'massage' => 187250, 'shave' => 53500, 'ear_clean' => 80250, 'dye' => 1070000, 'highlight' => 1070000, 'perm' => 1284000, 'straighten' => 1070000, 'styling' => 149800, 'recovery' => 428000, 'oil_treatment' => 294250, 'keratin' => 1498000],
    ];

    public static function price(int $salonIndex, string $serviceKey): int
    {
        return self::PRICES[$salonIndex][$serviceKey];
    }

    public static function duration(string $serviceKey): int
    {
        foreach (DemoServiceCatalog::SERVICES as $service) {
            if ($service['key'] === $serviceKey) {
                return $service['duration_minutes'];
            }
        }

        throw new \InvalidArgumentException("Unknown service key: {$serviceKey}");
    }

    public static function name(string $serviceKey): string
    {
        foreach (DemoServiceCatalog::SERVICES as $service) {
            if ($service['key'] === $serviceKey) {
                return $service['name'];
            }
        }

        throw new \InvalidArgumentException("Unknown service key: {$serviceKey}");
    }
}
