<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ClassUtils;

/**
 * Trait FormatQualifierTrait
 *
 * @api
 */
trait FormatQualifierTrait
{
    /**
     * Sets the file format.
     *
     * @param string $format The file format.
     *
     */
    public static function format(string $format): FormatQualifier
    {
        return ClassUtils::verifyInstance($format, FormatQualifier::class);
    }
}
