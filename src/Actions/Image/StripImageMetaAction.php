<?php

/*
 * This file is part of Chevereto.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevereto\Actions\Image;

use Chevere\Components\Action\Action;
use Chevere\Components\Parameter\Parameter;
use Chevere\Components\Parameter\Parameters;
use Chevere\Components\Response\ResponseSuccess;
use Chevere\Components\Type\Type;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Response\ResponseInterface;
use Imagick;
use Intervention\Image\Image;

class StripImageMetaAction extends Action
{
    public function getParameters(): ParametersInterface
    {
        return (new Parameters)
            ->withAddedRequired(
                new Parameter('image', new Type(Image::class))
            );
    }

    public function run(ArgumentsInterface $arguments): ResponseInterface
    {
        /**
         * @var Image $image
         * @var Imagick $image
         */
        $image = $arguments->get('image');
        $imagick = $image->getCore();
        $profiles = $imagick->getImageProfiles('icc', true);
        $imagick->stripImage();
        if (!empty($profiles)) {
            $imagick->profileImage('icc', $profiles['icc']); //@codeCoverageIgnore
        }
        $image->save();

        return new ResponseSuccess([]);
    }
}