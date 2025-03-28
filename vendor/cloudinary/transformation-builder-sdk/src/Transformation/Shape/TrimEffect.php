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
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Detect and remove image edges whose color is similar to corner pixels.
 *
 * @api
 *
 * @see ReshapeTrait::trim
 */
class TrimEffect extends LimitedEffectQualifier
{
    /**
     * @var array $valueOrder The order of the values.
     */
    protected array $valueOrder = [0, 'color_similarity', 'color_override'];

    /**
     * Trim constructor.
     *
     */
    public function __construct($colorSimilarity = null)
    {
        parent::__construct(ReshapeQualifier::TRIM, EffectRange::PERCENT);

        $this->colorSimilarity($colorSimilarity);
    }

    /**
     * Sets the tolerance level for color similarity.
     *
     * @param int $colorSimilarity The tolerance level for color similarity.  (Range: 0 to 100, Server default: 10)
     *
     */
    public function colorSimilarity(int $colorSimilarity): static
    {
        $this->value->setSimpleValue('color_similarity', $colorSimilarity);

        return $this;
    }

    /**
     *
     * Overrides the corner pixels color with the specified color.
     *
     * @param string $colorOverride The color to trim as a named color or an RGB/A hex code.
     *
     */
    public function colorOverride(string $colorOverride): static
    {
        $this->value->setSimpleValue('color_override', ClassUtils::verifyInstance($colorOverride, ColorValue::class));

        return $this;
    }
}
