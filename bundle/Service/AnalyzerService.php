<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Service;

/**
 * Class AnalyzerService.
 */
final class AnalyzerService
{

    public const STATUS_VALUES = ['low', 'medium', 'high'];

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