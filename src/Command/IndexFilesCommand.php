<?php
/**
 * Created by PhpStorm.
 * User: magonxesp
 * Date: 07/04/2019
 * Time: 00:11
 */

namespace App\Command;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;


class IndexFilesCommand extends Command {

    protected static $defaultName = 'app:index';
    private $entityManager;
    private $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    protected function configure() {
        $this->setDescription('Indexa los html de frikileaks en la base de datos.');
        $this->setHelp('Este comando indexa ficheros en la base de datos.');
    }

    private function getHTMLTitle(\DOMDocument $html) {
        $elements = $html->getElementsByTagName('title');
        $title = $elements->item(0)->nodeValue;
        $title = explode('Fikileaks - ', $title)[1];
        return $title;
    }

    private function isValidForSummary($string) {
        $invalidStrings = [
            'Autor(es):',
            'Redirige a:'
        ];

        if (in_array(trim($string), $invalidStrings)) {
            return false;
        } else {
            return true;
        }
    }

    private function getHTMLSummary(\DOMDocument $html) {
        $elements = $html->getElementsByTagName('p');
        $summary = '';
        $index = 1;

        while (($element = $elements->item($index)) != null) {
            $value = $element->textContent;
            $valueLenght = strlen($value);

            if (!$this->isValidForSummary($value)) {
                $index++;
                continue;
            }

            if ($valueLenght < 255) {
                $summary .= str_replace("\n", ' ', $value);

                if (strlen($summary) >= 255) {
                    $summary = substr($summary, 0, 252);
                    $summary .= '...';
                    break;
                }
            }

            $index++;
        }

        return $summary;
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output) {
        $frikileaksDir = $this->kernel->getProjectDir() . '/public/assets/frikileaks';

        if (!is_dir($frikileaksDir)) {
            throw new Exception('No se ha encontrado frikileaks en assets!');
        }

        $output->writeln('Generando lista de ficheros...');

        $frikileaksFiles = scandir($frikileaksDir);
        $indexed = 0;

        $output->writeln('Indexando ficheros...');

        foreach ($frikileaksFiles as $frikileaksFile) {
            if (preg_match('/^(.+)\.html$/', $frikileaksFile)
                && is_file($frikileaksDir . '/' . $frikileaksFile))
            {
                $html = new \DOMDocument();
                libxml_use_internal_errors(true);
                $isHTMLLoad = $html->loadHTMLFile($frikileaksDir . '/' . $frikileaksFile);

                if ($isHTMLLoad) {
                    $file = new File();
                    $file->setName($frikileaksFile);
                    $file->setTitle($this->getHTMLTitle($html));
                    $file->setSummary($this->getHTMLSummary($html));
                    $file->setPath($frikileaksDir . '/' . $frikileaksFile);
                    $file->setUrl('/contenido/' . urlencode($file->getName()));

                    $this->entityManager->persist($file);
                    $this->entityManager->flush();
                    $output->writeln($file->getName());
                    $indexed++;
                }
            }
        }

        $output->writeln('Se han indexado ' . $indexed . ' archivos de frikileaks!');
    }

}