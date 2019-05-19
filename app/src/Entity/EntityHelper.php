<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Entity;


trait EntityHelper
{
    /**
     * Initialize object with properties from array.
     *
     * @param array $values Array with properties.
     * @param bool  $hard
     */
    public function hydrate(array $values = [], bool $hard = false)
    {
        $sacredProperties = ['id', 'uuid'];
        foreach ($values as $property => $value) {
            if (!$hard && in_array($property, $sacredProperties)) {
                continue;
            }
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}