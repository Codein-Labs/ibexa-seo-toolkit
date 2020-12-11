<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Model;

/**
 * interface ArrayableInterface.
 */
interface ArrayableInterface
{
    /**
   	 * Returns a representation of the object as a native PHP array.
   	 *
   	 * @return array Associative array of object data.
   	 */
   	public function toArray();
}
