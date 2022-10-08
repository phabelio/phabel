<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\String;

use PhabelVendor\Symfony\Component\String\Exception\ExceptionInterface;
use PhabelVendor\Symfony\Component\String\Exception\InvalidArgumentException;
use PhabelVendor\Symfony\Component\String\Exception\RuntimeException;
/**
 * Represents a string of abstract Unicode characters.
 *
 * Unicode defines 3 types of "characters" (bytes, code points and grapheme clusters).
 * This class is the abstract type to use as a type-hint when the logic you want to
 * implement is Unicode-aware but doesn't care about code points vs grapheme clusters.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @throws ExceptionInterface
 */
abstract class AbstractUnicodeString extends AbstractString
{
    public const NFC = 16;
    public const NFD = 4;
    public const NFKC = 32;
    public const NFKD = 8;
    // all ASCII letters sorted by typical frequency of occurrence
    private const ASCII = ' eiasntrolud][cmp\'
g|hv.fb,:=-q10C2*yx)(L9AS/P"EjMIk3>5T<D4}B{8FwR67UGN;JzV#HOW_&!K?XQ%Y\\	Z+~^$@`' . "\x00" . '';
    // the subset of folded case mappings that is not in lower case mappings
    private const FOLD_FROM = array(0 => 'İ', 1 => 'µ', 2 => 'ſ', 3 => 'ͅ', 4 => 'ς', 5 => 'ϐ', 6 => 'ϑ', 7 => 'ϕ', 8 => 'ϖ', 9 => 'ϰ', 10 => 'ϱ', 11 => 'ϵ', 12 => 'ẛ', 13 => 'ι', 14 => 'ß', 15 => 'İ', 16 => 'ŉ', 17 => 'ǰ', 18 => 'ΐ', 19 => 'ΰ', 20 => 'և', 21 => 'ẖ', 22 => 'ẗ', 23 => 'ẘ', 24 => 'ẙ', 25 => 'ẚ', 26 => 'ẞ', 27 => 'ὐ', 28 => 'ὒ', 29 => 'ὔ', 30 => 'ὖ', 31 => 'ᾀ', 32 => 'ᾁ', 33 => 'ᾂ', 34 => 'ᾃ', 35 => 'ᾄ', 36 => 'ᾅ', 37 => 'ᾆ', 38 => 'ᾇ', 39 => 'ᾈ', 40 => 'ᾉ', 41 => 'ᾊ', 42 => 'ᾋ', 43 => 'ᾌ', 44 => 'ᾍ', 45 => 'ᾎ', 46 => 'ᾏ', 47 => 'ᾐ', 48 => 'ᾑ', 49 => 'ᾒ', 50 => 'ᾓ', 51 => 'ᾔ', 52 => 'ᾕ', 53 => 'ᾖ', 54 => 'ᾗ', 55 => 'ᾘ', 56 => 'ᾙ', 57 => 'ᾚ', 58 => 'ᾛ', 59 => 'ᾜ', 60 => 'ᾝ', 61 => 'ᾞ', 62 => 'ᾟ', 63 => 'ᾠ', 64 => 'ᾡ', 65 => 'ᾢ', 66 => 'ᾣ', 67 => 'ᾤ', 68 => 'ᾥ', 69 => 'ᾦ', 70 => 'ᾧ', 71 => 'ᾨ', 72 => 'ᾩ', 73 => 'ᾪ', 74 => 'ᾫ', 75 => 'ᾬ', 76 => 'ᾭ', 77 => 'ᾮ', 78 => 'ᾯ', 79 => 'ᾲ', 80 => 'ᾳ', 81 => 'ᾴ', 82 => 'ᾶ', 83 => 'ᾷ', 84 => 'ᾼ', 85 => 'ῂ', 86 => 'ῃ', 87 => 'ῄ', 88 => 'ῆ', 89 => 'ῇ', 90 => 'ῌ', 91 => 'ῒ', 92 => 'ΐ', 93 => 'ῖ', 94 => 'ῗ', 95 => 'ῢ', 96 => 'ΰ', 97 => 'ῤ', 98 => 'ῦ', 99 => 'ῧ', 100 => 'ῲ', 101 => 'ῳ', 102 => 'ῴ', 103 => 'ῶ', 104 => 'ῷ', 105 => 'ῼ', 106 => 'ﬀ', 107 => 'ﬁ', 108 => 'ﬂ', 109 => 'ﬃ', 110 => 'ﬄ', 111 => 'ﬅ', 112 => 'ﬆ', 113 => 'ﬓ', 114 => 'ﬔ', 115 => 'ﬕ', 116 => 'ﬖ', 117 => 'ﬗ');
    private const FOLD_TO = array(0 => 'i̇', 1 => 'μ', 2 => 's', 3 => 'ι', 4 => 'σ', 5 => 'β', 6 => 'θ', 7 => 'φ', 8 => 'π', 9 => 'κ', 10 => 'ρ', 11 => 'ε', 12 => 'ṡ', 13 => 'ι', 14 => 'ss', 15 => 'i̇', 16 => 'ʼn', 17 => 'ǰ', 18 => 'ΐ', 19 => 'ΰ', 20 => 'եւ', 21 => 'ẖ', 22 => 'ẗ', 23 => 'ẘ', 24 => 'ẙ', 25 => 'aʾ', 26 => 'ss', 27 => 'ὐ', 28 => 'ὒ', 29 => 'ὔ', 30 => 'ὖ', 31 => 'ἀι', 32 => 'ἁι', 33 => 'ἂι', 34 => 'ἃι', 35 => 'ἄι', 36 => 'ἅι', 37 => 'ἆι', 38 => 'ἇι', 39 => 'ἀι', 40 => 'ἁι', 41 => 'ἂι', 42 => 'ἃι', 43 => 'ἄι', 44 => 'ἅι', 45 => 'ἆι', 46 => 'ἇι', 47 => 'ἠι', 48 => 'ἡι', 49 => 'ἢι', 50 => 'ἣι', 51 => 'ἤι', 52 => 'ἥι', 53 => 'ἦι', 54 => 'ἧι', 55 => 'ἠι', 56 => 'ἡι', 57 => 'ἢι', 58 => 'ἣι', 59 => 'ἤι', 60 => 'ἥι', 61 => 'ἦι', 62 => 'ἧι', 63 => 'ὠι', 64 => 'ὡι', 65 => 'ὢι', 66 => 'ὣι', 67 => 'ὤι', 68 => 'ὥι', 69 => 'ὦι', 70 => 'ὧι', 71 => 'ὠι', 72 => 'ὡι', 73 => 'ὢι', 74 => 'ὣι', 75 => 'ὤι', 76 => 'ὥι', 77 => 'ὦι', 78 => 'ὧι', 79 => 'ὰι', 80 => 'αι', 81 => 'άι', 82 => 'ᾶ', 83 => 'ᾶι', 84 => 'αι', 85 => 'ὴι', 86 => 'ηι', 87 => 'ήι', 88 => 'ῆ', 89 => 'ῆι', 90 => 'ηι', 91 => 'ῒ', 92 => 'ΐ', 93 => 'ῖ', 94 => 'ῗ', 95 => 'ῢ', 96 => 'ΰ', 97 => 'ῤ', 98 => 'ῦ', 99 => 'ῧ', 100 => 'ὼι', 101 => 'ωι', 102 => 'ώι', 103 => 'ῶ', 104 => 'ῶι', 105 => 'ωι', 106 => 'ff', 107 => 'fi', 108 => 'fl', 109 => 'ffi', 110 => 'ffl', 111 => 'st', 112 => 'st', 113 => 'մն', 114 => 'մե', 115 => 'մի', 116 => 'վն', 117 => 'մխ');
    // the subset of upper case mappings that map one code point to many code points
    private const UPPER_FROM = array(0 => 'ß', 1 => 'ﬀ', 2 => 'ﬁ', 3 => 'ﬂ', 4 => 'ﬃ', 5 => 'ﬄ', 6 => 'ﬅ', 7 => 'ﬆ', 8 => 'և', 9 => 'ﬓ', 10 => 'ﬔ', 11 => 'ﬕ', 12 => 'ﬖ', 13 => 'ﬗ', 14 => 'ŉ', 15 => 'ΐ', 16 => 'ΰ', 17 => 'ǰ', 18 => 'ẖ', 19 => 'ẗ', 20 => 'ẘ', 21 => 'ẙ', 22 => 'ẚ', 23 => 'ὐ', 24 => 'ὒ', 25 => 'ὔ', 26 => 'ὖ', 27 => 'ᾶ', 28 => 'ῆ', 29 => 'ῒ', 30 => 'ΐ', 31 => 'ῖ', 32 => 'ῗ', 33 => 'ῢ', 34 => 'ΰ', 35 => 'ῤ', 36 => 'ῦ', 37 => 'ῧ', 38 => 'ῶ');
    private const UPPER_TO = array(0 => 'SS', 1 => 'FF', 2 => 'FI', 3 => 'FL', 4 => 'FFI', 5 => 'FFL', 6 => 'ST', 7 => 'ST', 8 => 'ԵՒ', 9 => 'ՄՆ', 10 => 'ՄԵ', 11 => 'ՄԻ', 12 => 'ՎՆ', 13 => 'ՄԽ', 14 => 'ʼN', 15 => 'Ϊ́', 16 => 'Ϋ́', 17 => 'J̌', 18 => 'H̱', 19 => 'T̈', 20 => 'W̊', 21 => 'Y̊', 22 => 'Aʾ', 23 => 'Υ̓', 24 => 'Υ̓̀', 25 => 'Υ̓́', 26 => 'Υ̓͂', 27 => 'Α͂', 28 => 'Η͂', 29 => 'Ϊ̀', 30 => 'Ϊ́', 31 => 'Ι͂', 32 => 'Ϊ͂', 33 => 'Ϋ̀', 34 => 'Ϋ́', 35 => 'Ρ̓', 36 => 'Υ͂', 37 => 'Ϋ͂', 38 => 'Ω͂');
    // the subset of https://github.com/unicode-org/cldr/blob/master/common/transforms/Latin-ASCII.xml that is not in NFKD
    private const TRANSLIT_FROM = array(0 => 'Æ', 1 => 'Ð', 2 => 'Ø', 3 => 'Þ', 4 => 'ß', 5 => 'æ', 6 => 'ð', 7 => 'ø', 8 => 'þ', 9 => 'Đ', 10 => 'đ', 11 => 'Ħ', 12 => 'ħ', 13 => 'ı', 14 => 'ĸ', 15 => 'Ŀ', 16 => 'ŀ', 17 => 'Ł', 18 => 'ł', 19 => 'ŉ', 20 => 'Ŋ', 21 => 'ŋ', 22 => 'Œ', 23 => 'œ', 24 => 'Ŧ', 25 => 'ŧ', 26 => 'ƀ', 27 => 'Ɓ', 28 => 'Ƃ', 29 => 'ƃ', 30 => 'Ƈ', 31 => 'ƈ', 32 => 'Ɖ', 33 => 'Ɗ', 34 => 'Ƌ', 35 => 'ƌ', 36 => 'Ɛ', 37 => 'Ƒ', 38 => 'ƒ', 39 => 'Ɠ', 40 => 'ƕ', 41 => 'Ɩ', 42 => 'Ɨ', 43 => 'Ƙ', 44 => 'ƙ', 45 => 'ƚ', 46 => 'Ɲ', 47 => 'ƞ', 48 => 'Ƣ', 49 => 'ƣ', 50 => 'Ƥ', 51 => 'ƥ', 52 => 'ƫ', 53 => 'Ƭ', 54 => 'ƭ', 55 => 'Ʈ', 56 => 'Ʋ', 57 => 'Ƴ', 58 => 'ƴ', 59 => 'Ƶ', 60 => 'ƶ', 61 => 'Ǆ', 62 => 'ǅ', 63 => 'ǆ', 64 => 'Ǥ', 65 => 'ǥ', 66 => 'ȡ', 67 => 'Ȥ', 68 => 'ȥ', 69 => 'ȴ', 70 => 'ȵ', 71 => 'ȶ', 72 => 'ȷ', 73 => 'ȸ', 74 => 'ȹ', 75 => 'Ⱥ', 76 => 'Ȼ', 77 => 'ȼ', 78 => 'Ƚ', 79 => 'Ⱦ', 80 => 'ȿ', 81 => 'ɀ', 82 => 'Ƀ', 83 => 'Ʉ', 84 => 'Ɇ', 85 => 'ɇ', 86 => 'Ɉ', 87 => 'ɉ', 88 => 'Ɍ', 89 => 'ɍ', 90 => 'Ɏ', 91 => 'ɏ', 92 => 'ɓ', 93 => 'ɕ', 94 => 'ɖ', 95 => 'ɗ', 96 => 'ɛ', 97 => 'ɟ', 98 => 'ɠ', 99 => 'ɡ', 100 => 'ɢ', 101 => 'ɦ', 102 => 'ɧ', 103 => 'ɨ', 104 => 'ɪ', 105 => 'ɫ', 106 => 'ɬ', 107 => 'ɭ', 108 => 'ɱ', 109 => 'ɲ', 110 => 'ɳ', 111 => 'ɴ', 112 => 'ɶ', 113 => 'ɼ', 114 => 'ɽ', 115 => 'ɾ', 116 => 'ʀ', 117 => 'ʂ', 118 => 'ʈ', 119 => 'ʉ', 120 => 'ʋ', 121 => 'ʏ', 122 => 'ʐ', 123 => 'ʑ', 124 => 'ʙ', 125 => 'ʛ', 126 => 'ʜ', 127 => 'ʝ', 128 => 'ʟ', 129 => 'ʠ', 130 => 'ʣ', 131 => 'ʥ', 132 => 'ʦ', 133 => 'ʪ', 134 => 'ʫ', 135 => 'ᴀ', 136 => 'ᴁ', 137 => 'ᴃ', 138 => 'ᴄ', 139 => 'ᴅ', 140 => 'ᴆ', 141 => 'ᴇ', 142 => 'ᴊ', 143 => 'ᴋ', 144 => 'ᴌ', 145 => 'ᴍ', 146 => 'ᴏ', 147 => 'ᴘ', 148 => 'ᴛ', 149 => 'ᴜ', 150 => 'ᴠ', 151 => 'ᴡ', 152 => 'ᴢ', 153 => 'ᵫ', 154 => 'ᵬ', 155 => 'ᵭ', 156 => 'ᵮ', 157 => 'ᵯ', 158 => 'ᵰ', 159 => 'ᵱ', 160 => 'ᵲ', 161 => 'ᵳ', 162 => 'ᵴ', 163 => 'ᵵ', 164 => 'ᵶ', 165 => 'ᵺ', 166 => 'ᵻ', 167 => 'ᵽ', 168 => 'ᵾ', 169 => 'ᶀ', 170 => 'ᶁ', 171 => 'ᶂ', 172 => 'ᶃ', 173 => 'ᶄ', 174 => 'ᶅ', 175 => 'ᶆ', 176 => 'ᶇ', 177 => 'ᶈ', 178 => 'ᶉ', 179 => 'ᶊ', 180 => 'ᶌ', 181 => 'ᶍ', 182 => 'ᶎ', 183 => 'ᶏ', 184 => 'ᶑ', 185 => 'ᶒ', 186 => 'ᶓ', 187 => 'ᶖ', 188 => 'ᶙ', 189 => 'ẚ', 190 => 'ẜ', 191 => 'ẝ', 192 => 'ẞ', 193 => 'Ỻ', 194 => 'ỻ', 195 => 'Ỽ', 196 => 'ỽ', 197 => 'Ỿ', 198 => 'ỿ', 199 => '©', 200 => '®', 201 => '₠', 202 => '₢', 203 => '₣', 204 => '₤', 205 => '₧', 206 => '₺', 207 => '₹', 208 => 'ℌ', 209 => '℞', 210 => '㎧', 211 => '㎮', 212 => '㏆', 213 => '㏗', 214 => '㏞', 215 => '㏟', 216 => '¼', 217 => '½', 218 => '¾', 219 => '⅓', 220 => '⅔', 221 => '⅕', 222 => '⅖', 223 => '⅗', 224 => '⅘', 225 => '⅙', 226 => '⅚', 227 => '⅛', 228 => '⅜', 229 => '⅝', 230 => '⅞', 231 => '⅟', 232 => '〇', 233 => '‘', 234 => '’', 235 => '‚', 236 => '‛', 237 => '“', 238 => '”', 239 => '„', 240 => '‟', 241 => '′', 242 => '″', 243 => '〝', 244 => '〞', 245 => '«', 246 => '»', 247 => '‹', 248 => '›', 249 => '‐', 250 => '‑', 251 => '‒', 252 => '–', 253 => '—', 254 => '―', 255 => '︱', 256 => '︲', 257 => '﹘', 258 => '‖', 259 => '⁄', 260 => '⁅', 261 => '⁆', 262 => '⁎', 263 => '、', 264 => '。', 265 => '〈', 266 => '〉', 267 => '《', 268 => '》', 269 => '〔', 270 => '〕', 271 => '〘', 272 => '〙', 273 => '〚', 274 => '〛', 275 => '︑', 276 => '︒', 277 => '︹', 278 => '︺', 279 => '︽', 280 => '︾', 281 => '︿', 282 => '﹀', 283 => '﹑', 284 => '﹝', 285 => '﹞', 286 => '｟', 287 => '｠', 288 => '｡', 289 => '､', 290 => '×', 291 => '÷', 292 => '−', 293 => '∕', 294 => '∖', 295 => '∣', 296 => '∥', 297 => '≪', 298 => '≫', 299 => '⦅', 300 => '⦆');
    private const TRANSLIT_TO = array(0 => 'AE', 1 => 'D', 2 => 'O', 3 => 'TH', 4 => 'ss', 5 => 'ae', 6 => 'd', 7 => 'o', 8 => 'th', 9 => 'D', 10 => 'd', 11 => 'H', 12 => 'h', 13 => 'i', 14 => 'q', 15 => 'L', 16 => 'l', 17 => 'L', 18 => 'l', 19 => '\'n', 20 => 'N', 21 => 'n', 22 => 'OE', 23 => 'oe', 24 => 'T', 25 => 't', 26 => 'b', 27 => 'B', 28 => 'B', 29 => 'b', 30 => 'C', 31 => 'c', 32 => 'D', 33 => 'D', 34 => 'D', 35 => 'd', 36 => 'E', 37 => 'F', 38 => 'f', 39 => 'G', 40 => 'hv', 41 => 'I', 42 => 'I', 43 => 'K', 44 => 'k', 45 => 'l', 46 => 'N', 47 => 'n', 48 => 'OI', 49 => 'oi', 50 => 'P', 51 => 'p', 52 => 't', 53 => 'T', 54 => 't', 55 => 'T', 56 => 'V', 57 => 'Y', 58 => 'y', 59 => 'Z', 60 => 'z', 61 => 'DZ', 62 => 'Dz', 63 => 'dz', 64 => 'G', 65 => 'g', 66 => 'd', 67 => 'Z', 68 => 'z', 69 => 'l', 70 => 'n', 71 => 't', 72 => 'j', 73 => 'db', 74 => 'qp', 75 => 'A', 76 => 'C', 77 => 'c', 78 => 'L', 79 => 'T', 80 => 's', 81 => 'z', 82 => 'B', 83 => 'U', 84 => 'E', 85 => 'e', 86 => 'J', 87 => 'j', 88 => 'R', 89 => 'r', 90 => 'Y', 91 => 'y', 92 => 'b', 93 => 'c', 94 => 'd', 95 => 'd', 96 => 'e', 97 => 'j', 98 => 'g', 99 => 'g', 100 => 'G', 101 => 'h', 102 => 'h', 103 => 'i', 104 => 'I', 105 => 'l', 106 => 'l', 107 => 'l', 108 => 'm', 109 => 'n', 110 => 'n', 111 => 'N', 112 => 'OE', 113 => 'r', 114 => 'r', 115 => 'r', 116 => 'R', 117 => 's', 118 => 't', 119 => 'u', 120 => 'v', 121 => 'Y', 122 => 'z', 123 => 'z', 124 => 'B', 125 => 'G', 126 => 'H', 127 => 'j', 128 => 'L', 129 => 'q', 130 => 'dz', 131 => 'dz', 132 => 'ts', 133 => 'ls', 134 => 'lz', 135 => 'A', 136 => 'AE', 137 => 'B', 138 => 'C', 139 => 'D', 140 => 'D', 141 => 'E', 142 => 'J', 143 => 'K', 144 => 'L', 145 => 'M', 146 => 'O', 147 => 'P', 148 => 'T', 149 => 'U', 150 => 'V', 151 => 'W', 152 => 'Z', 153 => 'ue', 154 => 'b', 155 => 'd', 156 => 'f', 157 => 'm', 158 => 'n', 159 => 'p', 160 => 'r', 161 => 'r', 162 => 's', 163 => 't', 164 => 'z', 165 => 'th', 166 => 'I', 167 => 'p', 168 => 'U', 169 => 'b', 170 => 'd', 171 => 'f', 172 => 'g', 173 => 'k', 174 => 'l', 175 => 'm', 176 => 'n', 177 => 'p', 178 => 'r', 179 => 's', 180 => 'v', 181 => 'x', 182 => 'z', 183 => 'a', 184 => 'd', 185 => 'e', 186 => 'e', 187 => 'i', 188 => 'u', 189 => 'a', 190 => 's', 191 => 's', 192 => 'SS', 193 => 'LL', 194 => 'll', 195 => 'V', 196 => 'v', 197 => 'Y', 198 => 'y', 199 => '(C)', 200 => '(R)', 201 => 'CE', 202 => 'Cr', 203 => 'Fr.', 204 => 'L.', 205 => 'Pts', 206 => 'TL', 207 => 'Rs', 208 => 'x', 209 => 'Rx', 210 => 'm/s', 211 => 'rad/s', 212 => 'C/kg', 213 => 'pH', 214 => 'V/m', 215 => 'A/m', 216 => ' 1/4', 217 => ' 1/2', 218 => ' 3/4', 219 => ' 1/3', 220 => ' 2/3', 221 => ' 1/5', 222 => ' 2/5', 223 => ' 3/5', 224 => ' 4/5', 225 => ' 1/6', 226 => ' 5/6', 227 => ' 1/8', 228 => ' 3/8', 229 => ' 5/8', 230 => ' 7/8', 231 => ' 1/', 232 => '0', 233 => '\'', 234 => '\'', 235 => ',', 236 => '\'', 237 => '"', 238 => '"', 239 => ',,', 240 => '"', 241 => '\'', 242 => '"', 243 => '"', 244 => '"', 245 => '<<', 246 => '>>', 247 => '<', 248 => '>', 249 => '-', 250 => '-', 251 => '-', 252 => '-', 253 => '-', 254 => '-', 255 => '-', 256 => '-', 257 => '-', 258 => '||', 259 => '/', 260 => '[', 261 => ']', 262 => '*', 263 => ',', 264 => '.', 265 => '<', 266 => '>', 267 => '<<', 268 => '>>', 269 => '[', 270 => ']', 271 => '[', 272 => ']', 273 => '[', 274 => ']', 275 => ',', 276 => '.', 277 => '[', 278 => ']', 279 => '<<', 280 => '>>', 281 => '<', 282 => '>', 283 => ',', 284 => '[', 285 => ']', 286 => '((', 287 => '))', 288 => '.', 289 => ',', 290 => '*', 291 => '/', 292 => '-', 293 => '/', 294 => '\\', 295 => '|', 296 => '||', 297 => '<<', 298 => '>>', 299 => '((', 300 => '))');
    private static $transliterators = [];
    private static $tableZero;
    private static $tableWide;
    /**
     * @return static
     */
    public static function fromCodePoints(int ...$codes)
    {
        $string = '';
        foreach ($codes as $code) {
            if (0x80 > ($code %= 0x200000)) {
                $string .= \chr($code);
            } elseif (0x800 > $code) {
                $string .= \chr(0xc0 | $code >> 6) . \chr(0x80 | $code & 0x3f);
            } elseif (0x10000 > $code) {
                $string .= \chr(0xe0 | $code >> 12) . \chr(0x80 | $code >> 6 & 0x3f) . \chr(0x80 | $code & 0x3f);
            } else {
                $string .= \chr(0xf0 | $code >> 18) . \chr(0x80 | $code >> 12 & 0x3f) . \chr(0x80 | $code >> 6 & 0x3f) . \chr(0x80 | $code & 0x3f);
            }
        }
        $phabelReturn = new static($string);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Generic UTF-8 to ASCII transliteration.
     *
     * Install the intl extension for best results.
     *
     * @param (string[] | \Transliterator[] | \Closure[]) $rules See "*-Latin" rules from Transliterator::listIDs()
     */
    public function ascii(array $rules = array()) : self
    {
        $str = clone $this;
        $s = $str->string;
        $str->string = '';
        \array_unshift($rules, 'nfd');
        $rules[] = 'latin-ascii';
        if (\function_exists('transliterator_transliterate')) {
            $rules[] = 'any-latin/bgn';
        }
        $rules[] = 'nfkd';
        $rules[] = '[:nonspacing mark:] remove';
        while (\strlen($s) - 1 > ($i = \Phabel\Target\Php80\Polyfill::strspn($s, self::ASCII))) {
            if (0 < --$i) {
                $str->string .= \Phabel\Target\Php80\Polyfill::substr($s, 0, $i);
                $s = \Phabel\Target\Php80\Polyfill::substr($s, $i);
            }
            if (!($rule = \array_shift($rules))) {
                $rules = [];
                // An empty rule interrupts the next ones
            }
            if ($rule instanceof \Transliterator) {
                $s = $rule->transliterate($s);
            } elseif ($rule instanceof \Closure) {
                $s = $rule($s);
            } elseif ($rule) {
                if ('nfd' === ($rule = \strtolower($rule))) {
                    \normalizer_is_normalized($s, self::NFD) ?: ($s = \normalizer_normalize($s, self::NFD));
                } elseif ('nfkd' === $rule) {
                    \normalizer_is_normalized($s, self::NFKD) ?: ($s = \normalizer_normalize($s, self::NFKD));
                } elseif ('[:nonspacing mark:] remove' === $rule) {
                    $s = \preg_replace('/\\p{Mn}++/u', '', $s);
                } elseif ('latin-ascii' === $rule) {
                    $s = \str_replace(self::TRANSLIT_FROM, self::TRANSLIT_TO, $s);
                } elseif ('de-ascii' === $rule) {
                    $s = \preg_replace("/([AUO])̈(?=\\p{Ll})/u", '$1e', $s);
                    $s = \str_replace(["ä", "ö", "ü", "Ä", "Ö", "Ü"], ['ae', 'oe', 'ue', 'AE', 'OE', 'UE'], $s);
                } elseif (\function_exists('transliterator_transliterate')) {
                    if (null === ($transliterator = self::$transliterators[$rule] ?? (self::$transliterators[$rule] = \Transliterator::create($rule)))) {
                        if ('any-latin/bgn' === $rule) {
                            $rule = 'any-latin';
                            $transliterator = self::$transliterators[$rule] ?? (self::$transliterators[$rule] = \Transliterator::create($rule));
                        }
                        if (null === $transliterator) {
                            throw new InvalidArgumentException(\sprintf('Unknown transliteration rule "%s".', $rule));
                        }
                        self::$transliterators['any-latin/bgn'] = $transliterator;
                    }
                    $s = $transliterator->transliterate($s);
                }
            } elseif (!\function_exists('iconv')) {
                $s = \preg_replace('/[^\\x00-\\x7F]/u', '?', $s);
            } else {
                $s = @\preg_replace_callback('/[^\\x00-\\x7F]/u', static function ($c) {
                    $c = (string) \iconv('UTF-8', 'ASCII//TRANSLIT', $c[0]);
                    if ('' === $c && '' === \iconv('UTF-8', 'ASCII//TRANSLIT', '²')) {
                        throw new \LogicException(\sprintf('"%s" requires a translit-able iconv implementation, try installing "gnu-libiconv" if you\'re using Alpine Linux.', static::class));
                    }
                    return 1 < \strlen($c) ? \ltrim($c, '\'`"^~') : ('' !== $c ? $c : '?');
                }, $s);
            }
        }
        $str->string .= $s;
        return $str;
    }
    /**
     * @return static
     */
    public function camel()
    {
        $str = clone $this;
        $str->string = \str_replace(' ', '', \preg_replace_callback('/\\b.(?![A-Z]{2,})/u', static function ($m) use(&$i) {
            return 1 === ++$i ? 'İ' === $m[0] ? 'i̇' : \mb_strtolower($m[0], 'UTF-8') : \mb_convert_case($m[0], \MB_CASE_TITLE, 'UTF-8');
        }, \preg_replace('/[^\\pL0-9]++/u', ' ', $this->string)));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return int[]
     */
    public function codePointsAt(int $offset) : array
    {
        $str = $this->slice($offset, 1);
        if ('' === $str->string) {
            return [];
        }
        $codePoints = [];
        foreach (\preg_split('//u', $str->string, -1, \PREG_SPLIT_NO_EMPTY) as $c) {
            $codePoints[] = \mb_ord($c, 'UTF-8');
        }
        return $codePoints;
    }
    /**
     * @return static
     */
    public function folded(bool $compat = \true)
    {
        $str = clone $this;
        if (!$compat || !\defined('Normalizer::NFKC_CF')) {
            $str->string = \normalizer_normalize($str->string, $compat ? \Normalizer::NFKC : \Normalizer::NFC);
            $str->string = \mb_strtolower(\str_replace(self::FOLD_FROM, self::FOLD_TO, $this->string), 'UTF-8');
        } else {
            $str->string = \normalizer_normalize($str->string, \Normalizer::NFKC_CF);
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function join(array $strings, string $lastGlue = NULL)
    {
        $str = clone $this;
        $tail = null !== $lastGlue && 1 < \count($strings) ? $lastGlue . \array_pop($strings) : '';
        $str->string = \implode($this->string, $strings) . $tail;
        if (!\preg_match('//u', $str->string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function lower()
    {
        $str = clone $this;
        $str->string = \mb_strtolower(\str_replace('İ', 'i̇', $str->string), 'UTF-8');
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function match(string $regexp, int $flags = 0, int $offset = 0) : array
    {
        $match = (\PREG_PATTERN_ORDER | \PREG_SET_ORDER) & $flags ? 'preg_match_all' : 'preg_match';
        if ($this->ignoreCase) {
            $regexp .= 'i';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (\false === $match($regexp . 'u', $this->string, $matches, $flags | 0, $offset)) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && \str_ends_with($k, '_ERROR')) {
                        throw new RuntimeException('Matching failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Matching failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        array_walk_recursive($match, function (&$v){return $v === "" ? null : $v;}); return $matches;
    }
    /**
     * @return static
     */
    public function normalize(int $form = 16)
    {
        if (!\in_array($form, [self::NFC, self::NFD, self::NFKC, self::NFKD])) {
            throw new InvalidArgumentException('Unsupported normalization form.');
        }
        $str = clone $this;
        \normalizer_is_normalized($str->string, $form) ?: ($str->string = \normalizer_normalize($str->string, $form));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function padBoth(int $length, string $padStr = ' ')
    {
        if ('' === $padStr || !\preg_match('//u', $padStr)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $pad = clone $this;
        $pad->string = $padStr;
        $phabelReturn = $this->pad($length, $pad, \STR_PAD_BOTH);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function padEnd(int $length, string $padStr = ' ')
    {
        if ('' === $padStr || !\preg_match('//u', $padStr)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $pad = clone $this;
        $pad->string = $padStr;
        $phabelReturn = $this->pad($length, $pad, \STR_PAD_RIGHT);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function padStart(int $length, string $padStr = ' ')
    {
        if ('' === $padStr || !\preg_match('//u', $padStr)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $pad = clone $this;
        $pad->string = $padStr;
        $phabelReturn = $this->pad($length, $pad, \STR_PAD_LEFT);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | callable) $to
     * @return static
     */
    public function replaceMatches(string $fromRegexp, $to)
    {
        if (!(\is_string($to) || \is_callable($to))) {
            if (!(\is_string($to) || \is_object($to) && \method_exists($to, '__toString') || (\is_bool($to) || \is_numeric($to)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($to) must be of type callable|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $to = (string) $to;
            }
        }
        if ($this->ignoreCase) {
            $fromRegexp .= 'i';
        }
        if (\is_array($to) || $to instanceof \Closure) {
            $replace = 'preg_replace_callback';
            $to = static function (array $m) use($to) : string {
                $to = $to($m);
                if ('' !== $to && (!\is_string($to) || !\preg_match('//u', $to))) {
                    throw new InvalidArgumentException('Replace callback must return a valid UTF-8 string.');
                }
                return $to;
            };
        } elseif ('' !== $to && !\preg_match('//u', $to)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        } else {
            $replace = 'preg_replace';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (null === ($string = $replace($fromRegexp . 'u', $to, $this->string))) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && \str_ends_with($k, '_ERROR')) {
                        throw new RuntimeException('Matching failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Matching failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        $str = clone $this;
        $str->string = $string;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function reverse()
    {
        $str = clone $this;
        $str->string = \implode('', \array_reverse(\preg_split('/(\\X)/u', $str->string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY)));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function snake()
    {
        $str = $this->camel();
        $str->string = \mb_strtolower(\preg_replace(['/(\\p{Lu}+)(\\p{Lu}\\p{Ll})/u', '/([\\p{Ll}0-9])(\\p{Lu})/u'], 'PhabelVendor\\1_\\2', $str->string), 'UTF-8');
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function title(bool $allWords = \false)
    {
        $str = clone $this;
        $limit = $allWords ? -1 : 1;
        $str->string = \preg_replace_callback('/\\b./u', static function (array $m) : string {
            return \mb_convert_case($m[0], \MB_CASE_TITLE, 'UTF-8');
        }, $str->string, $limit);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trim(string $chars = ' 	
' . "\x00" . ' ﻿')
    {
        if (" \t\n\r\x00\v\f ﻿" !== $chars && !\preg_match('//u', $chars)) {
            throw new InvalidArgumentException('Invalid UTF-8 chars.');
        }
        $chars = \preg_quote($chars);
        $str = clone $this;
        $str->string = \preg_replace("{^[{$chars}]++|[{$chars}]++\$}uD", '', $str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimEnd(string $chars = ' 	
' . "\x00" . ' ﻿')
    {
        if (" \t\n\r\x00\v\f ﻿" !== $chars && !\preg_match('//u', $chars)) {
            throw new InvalidArgumentException('Invalid UTF-8 chars.');
        }
        $chars = \preg_quote($chars);
        $str = clone $this;
        $str->string = \preg_replace("{[{$chars}]++\$}uD", '', $str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimPrefix($prefix)
    {
        if (!$this->ignoreCase) {
            $phabelReturn = parent::trimPrefix($prefix);
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        if ($prefix instanceof \Traversable) {
            $prefix = \iterator_to_array($prefix, \false);
        } elseif ($prefix instanceof parent) {
            $prefix = $prefix->string;
        }
        $prefix = \implode('|', \array_map('preg_quote', (array) $prefix));
        $str->string = \preg_replace("{^(?:{$prefix})}iuD", '', $this->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimStart(string $chars = ' 	
' . "\x00" . ' ﻿')
    {
        if (" \t\n\r\x00\v\f ﻿" !== $chars && !\preg_match('//u', $chars)) {
            throw new InvalidArgumentException('Invalid UTF-8 chars.');
        }
        $chars = \preg_quote($chars);
        $str = clone $this;
        $str->string = \preg_replace("{^[{$chars}]++}uD", '', $str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimSuffix($suffix)
    {
        if (!$this->ignoreCase) {
            $phabelReturn = parent::trimSuffix($suffix);
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        if ($suffix instanceof \Traversable) {
            $suffix = \iterator_to_array($suffix, \false);
        } elseif ($suffix instanceof parent) {
            $suffix = $suffix->string;
        }
        $suffix = \implode('|', \array_map('preg_quote', (array) $suffix));
        $str->string = \preg_replace("{(?:{$suffix})\$}iuD", '', $this->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function upper()
    {
        $str = clone $this;
        $str->string = \mb_strtoupper($str->string, 'UTF-8');
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function width(bool $ignoreAnsiDecoration = \true) : int
    {
        $width = 0;
        $s = \str_replace(["\x00", "\x05", "\x07"], '', $this->string);
        if (\str_contains($s, "\r")) {
            $s = \str_replace(["\r\n", "\r"], "\n", $s);
        }
        if (!$ignoreAnsiDecoration) {
            $s = \preg_replace('/[\\p{Cc}\\x7F]++/u', '', $s);
        }
        foreach (\explode("\n", $s) as $s) {
            if ($ignoreAnsiDecoration) {
                $s = \preg_replace('/(?:\\x1B(?:
                    \\[ [\\x30-\\x3F]*+ [\\x20-\\x2F]*+ [\\x40-\\x7E]
                    | [P\\]X^_] .*? \\x1B\\\\
                    | [\\x41-\\x7E]
                )|[\\p{Cc}\\x7F]++)/xu', '', $s);
            }
            $lineWidth = $this->wcswidth($s);
            if ($lineWidth > $width) {
                $width = $lineWidth;
            }
        }
        return $width;
    }
    /**
     * @return static
     */
    private function pad(int $len, self $pad, int $type)
    {
        $sLen = $this->length();
        if ($len <= $sLen) {
            $phabelReturn = clone $this;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $padLen = $pad->length();
        $freeLen = $len - $sLen;
        $len = $freeLen % $padLen;
        switch ($type) {
            case \STR_PAD_RIGHT:
                $phabelReturn = $this->append(\str_repeat($pad->string, \intdiv($freeLen, $padLen)) . ($len ? $pad->slice(0, $len) : ''));
                if (!$phabelReturn instanceof static) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            case \STR_PAD_LEFT:
                $phabelReturn = $this->prepend(\str_repeat($pad->string, \intdiv($freeLen, $padLen)) . ($len ? $pad->slice(0, $len) : ''));
                if (!$phabelReturn instanceof static) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            case \STR_PAD_BOTH:
                $freeLen /= 2;
                $rightLen = \ceil($freeLen);
                $len = $rightLen % $padLen;
                $str = $this->append(\str_repeat($pad->string, \intdiv($rightLen, $padLen)) . ($len ? $pad->slice(0, $len) : ''));
                $leftLen = \floor($freeLen);
                $len = $leftLen % $padLen;
                $phabelReturn = $str->prepend(\str_repeat($pad->string, \intdiv($leftLen, $padLen)) . ($len ? $pad->slice(0, $len) : ''));
                if (!$phabelReturn instanceof static) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            default:
                throw new InvalidArgumentException('Invalid padding type.');
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Based on https://github.com/jquast/wcwidth, a Python implementation of https://www.cl.cam.ac.uk/~mgk25/ucs/wcwidth.c.
     */
    private function wcswidth(string $string) : int
    {
        $width = 0;
        foreach (\preg_split('//u', $string, -1, \PREG_SPLIT_NO_EMPTY) as $c) {
            $codePoint = \mb_ord($c, 'UTF-8');
            if (0 === $codePoint || 0x34f === $codePoint || 0x200b <= $codePoint && 0x200f >= $codePoint || 0x2028 === $codePoint || 0x2029 === $codePoint || 0x202a <= $codePoint && 0x202e >= $codePoint || 0x2060 <= $codePoint && 0x2063 >= $codePoint) {
                continue;
            }
            // Non printable characters
            if (32 > $codePoint || 0x7f <= $codePoint && 0xa0 > $codePoint) {
                return -1;
            }
            if (null === self::$tableZero) {
                self::$tableZero = (require __DIR__ . '/Resources/data/wcswidth_table_zero.php');
            }
            if ($codePoint >= self::$tableZero[0][0] && $codePoint <= self::$tableZero[$ubound = \count(self::$tableZero) - 1][1]) {
                $lbound = 0;
                while ($ubound >= $lbound) {
                    $mid = \floor(($lbound + $ubound) / 2);
                    if ($codePoint > self::$tableZero[$mid][1]) {
                        $lbound = $mid + 1;
                    } elseif ($codePoint < self::$tableZero[$mid][0]) {
                        $ubound = $mid - 1;
                    } else {
                        continue 2;
                    }
                }
            }
            if (null === self::$tableWide) {
                self::$tableWide = (require __DIR__ . '/Resources/data/wcswidth_table_wide.php');
            }
            if ($codePoint >= self::$tableWide[0][0] && $codePoint <= self::$tableWide[$ubound = \count(self::$tableWide) - 1][1]) {
                $lbound = 0;
                while ($ubound >= $lbound) {
                    $mid = \floor(($lbound + $ubound) / 2);
                    if ($codePoint > self::$tableWide[$mid][1]) {
                        $lbound = $mid + 1;
                    } elseif ($codePoint < self::$tableWide[$mid][0]) {
                        $ubound = $mid - 1;
                    } else {
                        $width += 2;
                        continue 2;
                    }
                }
            }
            ++$width;
        }
        return $width;
    }
}
