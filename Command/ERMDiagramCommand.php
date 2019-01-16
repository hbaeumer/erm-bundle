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


namespace Hbaeumer\ErmBundle\Command;

use Hbaeumer\ErmBundle\Grapher\PlantUmlGrapher;
use Hbaeumer\ErmBundle\Parser\PlantUmlEntityParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ERMDiagramCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'doctrine:erm:output';

    /**
     * @var PlantUmlEntityParser
     */
    private $parser;

    /**
     * @var PlantUmlGrapher
     */
    private $grapher;

    public function __construct(PlantUmlEntityParser $parser, PlantUmlGrapher $grapher)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->grapher = $grapher;
    }

    protected function configure(): void
    {
        $this->addOption('svg', 's', InputOption::VALUE_OPTIONAL, 'svg path');
        $this->addOption('markup', 'm', InputOption::VALUE_NONE, 'markup output');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $markup = $this->parser->getMarkup();
        if ($input->getOption('markup')) {
            $output->writeln($markup);
        }
        if ($input->getOption('svg')) {
            file_put_contents($input->getOption('svg'), $this->grapher->getSVG($markup));
        }
    }
}
