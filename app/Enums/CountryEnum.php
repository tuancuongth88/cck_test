<?php
/**
 * Created by PhpStorm.
 * User: cuongnt
 * Date: 11/03/2020
 * Time: 10:54
 */

namespace Enum;

/**
 * @OA\Schema(
 *     title="Country",
 *     description="Danh sách các quốc gia:
 *     1 : ベトナム,
 *     2:ミャンマー,
 *     3:フィリピン,
 *     4:バングラデシュ,
 *     5:ネパール,
 *     6:カンボジア,
 *     7:タイ,
 *     (-1): その他",
 *     type="int",
 *     enum={1,2,3,4,5,6,7,-1},
 * )
 */
class CountryEnum extends Enum
{
    const COUNTRY = [
        '1' => 'ベトナム',
        '2' => 'ミャンマー',
        '3' => 'フィリピン',
        '4' => 'バングラデシュ',
        '5' => 'ネパール',
        '6' => 'カンボジア',
        '7' => 'タイ',
        '-1' => 'その他'
    ];
}
