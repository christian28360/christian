<?php

namespace CHRIST\Common\Kernel\Extensions\Doctrine\SqlSrv\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

/**
 * Type that maps an SQL DATETIME/TIMESTAMP to a PHP DateTime object.
 *
 * @since 2.0
 */
class DateTimeSqlSrvType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::DATETIME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'datetimeSqlSrv';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format('Ymd H:i:s') : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }
        
        $mask = strpos($value, '.') === false ? 'Y-m-d H:i:s' : 'Y-m-d H:i:s.u';
        $val = \DateTime::createFromFormat($mask, $value);
        if ( ! $val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $val;
    }
}
