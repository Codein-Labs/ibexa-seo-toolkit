<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\FieldType;

use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Class Value.
 */
class Value extends FieldValue
{
    /**
     * Array of Meta.
     *
     * @var array
     */
    public $metas = [];

    public function __construct($metas = null)
    {
        parent::__construct();
        if (\is_array($metas)) {
            $this->metas = [];
            foreach ($metas as $meta) {
                $this->metas[$meta['meta_name']] = $meta['meta_content'];
            }
        }
    }

    public function __toString()
    {
        $str = '';
        if (\count($this->metas)) {
            foreach ($this->metas as $key => $meta) {
                $str .= "{$key} = {$meta}\n";
            }
        }

        return $str;
    }
}
