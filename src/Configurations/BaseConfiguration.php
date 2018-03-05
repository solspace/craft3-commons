<?php
/**
 * Created by PhpStorm.
 * User: gustavs
 * Date: 26/02/2018
 * Time: 12:08
 */

namespace Solspace\Commons\Configurations;

use Solspace\Commons\Exceptions\Configurations\ConfigurationException;

abstract class BaseConfiguration
{
    /**
     * BaseConfiguration constructor.
     * Passing an array config populates all of the configuration values for a given configuration
     *
     * @param array $config
     *
     * @throws ConfigurationException
     * @throws \ReflectionException
     */
    public function __construct(array $config = null)
    {
        if (null === $config) {
            return;
        }

        foreach ($config as $key => $value) {
            if (property_exists(\get_class($this), $key)) {
                $this->$key = $value;
            } else {
                $reflection = new \ReflectionClass($this);
                $properties = $reflection->getProperties(
                    \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED
                );

                $availableProperties = [];
                foreach ($properties as $property) {
                    $availableProperties[] = $property->getName();
                }

                throw new ConfigurationException(
                    sprintf(
                        'Configuration property "%s" does not exist. Available properties are: "%s"',
                        $key,
                        implode(', ', $availableProperties)
                    )
                );
            }
        }
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->getConfigHash();
    }

    /**
     * Returns the SHA1 hash of the serialized object
     *
     * @return string
     */
    public function getConfigHash(): string
    {
        return sha1(serialize($this));
    }

    /**
     * @param mixed $value
     * @param bool  $nullable
     *
     * @return int|null
     */
    protected function castToInt($value, bool $nullable = true)
    {
        if (null === $value && $nullable) {
            return null;
        }

        return (int) $value;
    }

    /**
     * @param mixed $value
     * @param bool  $nullable
     *
     * @return string|null
     */
    protected function castToString($value, bool $nullable = true)
    {
        if (null === $value && $nullable) {
            return null;
        }

        return (string) $value;
    }

    /**
     * @param mixed $value
     * @param bool  $nullable
     *
     * @return bool|null
     */
    protected function castToBool($value, bool $nullable = true)
    {
        if (null === $value && $nullable) {
            return null;
        }

        return (bool) $value;
    }

    /**
     * @param mixed $value
     * @param bool  $nullable
     *
     * @return array|null
     */
    protected function castToArray($value, bool $nullable = true)
    {
        if (null === $value) {
            return $nullable ? null : [];
        }

        if (!\is_array($value)) {
            return '' === $value ? [] : [$value];
        }

        return $value;
    }
}
