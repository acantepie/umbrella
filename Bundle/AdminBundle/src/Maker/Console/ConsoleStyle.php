<?php

namespace Umbrella\AdminBundle\Maker\Console;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ConsoleStyle
 */
class ConsoleStyle extends SymfonyStyle
{
    public function success($message)
    {
        $this->writeln('<fg=green;options=bold,underscore>OK</> ' . $message);
    }

    public function comment($message)
    {
        $this->text($message);
    }

    public function doneSuccess()
    {
        $this->newLine();
        $this->writeln(' <bg=green;fg=white>          </>');
        $this->writeln(' <bg=green;fg=white> Success! </>');
        $this->writeln(' <bg=green;fg=white>          </>');
        $this->newLine();
    }
}
