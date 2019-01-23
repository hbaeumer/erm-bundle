<?php declare(strict_types=1);

/**
 * This file is part of hbaeumer/erm-bundle
 *
 * Copyright (c) Heiner Baeumer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Hbaeumer\ErmBundle\Controller;

use Hbaeumer\ErmBundle\Grapher\PlantUmlGrapher;
use Hbaeumer\ErmBundle\Parser\PlantUmlEntityParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ERMDiagramController extends AbstractController
{

    /**
     * @var PlantUmlGrapher
     */
    private $umlGrapher;

    /**
     * @var PlantUmlEntityParser
     */
    private $plantUmlEntityParser;

    public function __construct(PlantUmlEntityParser $plantUmlEntityParser, PlantUmlGrapher $umlGrapher)
    {
        $this->umlGrapher = $umlGrapher;
        $this->plantUmlEntityParser = $plantUmlEntityParser;
    }

    public function indexAction(): Response
    {
        $markup = $this->plantUmlEntityParser->getMarkup();
        $svg = $this->umlGrapher->getSVG($markup);
        return new Response(
            "<html><body>$svg</body></html>"
        );
    }
}
