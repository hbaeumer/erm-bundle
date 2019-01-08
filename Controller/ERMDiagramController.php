<?php declare(strict_types=1);

/**
 * MIT License
 *
 * Copyright (c)  Heiner Baeumer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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

    /**
     * ERMDiagramController constructor.
     * @param PlantUmlGrapher $umlGrapher
     * @param PlantUmlEntityParser $plantUmlEntityParser
     */
    public function __construct(PlantUmlGrapher $umlGrapher, PlantUmlEntityParser $plantUmlEntityParser)
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
