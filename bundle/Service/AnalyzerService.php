<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

/**
 * Class AnalyzerService.
 */
final class AnalyzerService
{

    private const STATUS_VALUES = ['low', 'medium', 'high'];

    public const ACCENT_VALUES = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );

    /** 
     * Convert an UTF-8 encoded string to a single-byte string suitable for
     * functions such as levenshtein.
     *
     * The function simply uses (and updates) a tailored dynamic encoding
     * (in/out map parameter) where non-ascii characters are remapped to
     * the range [128-255] in order of appearance.
     *
     * Thus it supports up to 128 different multibyte code points max over
     * the whole set of strings sharing this encoding.
     */
    public static function utf8_to_extended_ascii($str, &$map)
    {
        // find all multibyte characters (cf. utf-8 encoding specs)
        $matches = array();
        if (!preg_match_all('/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches))
            return $str; // plain ascii string
    
        // update the encoding map with the characters not already met
        foreach ($matches[0] as $mbc)
            if (!isset($map[$mbc]))
                $map[$mbc] = chr(128 + count($map));
    
        // finally remap non-ascii characters
        return strtr($str, $map);
    }

    /** 
     * https://www.php.net/manual/en/function.levenshtein.php#113702
     */
    public static function levenshtein_utf8($s1, $s2)
    {
        $charMap = array();
        $s1 = self::utf8_to_extended_ascii($s1, $charMap);
        $s2 = self::utf8_to_extended_ascii($s2, $charMap);
    
        return levenshtein($s1, $s2);
    }


    /**
     * Helper method to provide readable analysis.
     *
     * @param string $category
     * @param string $status
     * @param array $data
     * @return array
     */
    public function compile(string $category, ?string $status, ?array $data): array {
        if (
            !is_string($status)
            || !in_array($status, self::STATUS_VALUES)
            || !is_array($data)
        ) 
        {
            return array(
                $category => array()
            );
        }
        return array( 
            $category => array(
                'status' => $status,
                'data' => $data
            )
        );
    }
}