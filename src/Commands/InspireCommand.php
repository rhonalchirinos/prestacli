<?php

namespace Presta\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InspireCommand extends Command
{

    const PHRASES = [
        'We cannot solve problems with the kind of thinking we employed when we came up with them. — Albert Einstein',
        'Learn as if you will live forever, live like you will die tomorrow. — Mahatma Gandhi',
        'Stay away from those people who try to disparage your ambitions. Small minds will always do that, but great minds will give you a feeling that you can become great too. — Mark Twain',
        'When you give joy to other people, you get more joy in return. You should give a good thought to happiness that you can give out.— Eleanor Roosevelt',
        'When you change your thoughts, remember to also change your world.—Norman Vincent Peale',
        'It is only when we take chances, when our lives improve. The initial and the most difficult risk that we need to take is to become honest. —Walter Anderson',
        'Nature has given us all the pieces required to achieve exceptional wellness and health, but has left it to us to put these pieces together.—Diane McLaren',
    ];

    public function __construct()
    {
        parent::__construct('inspire');
        $this->setDescription('I inspire you!!!');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return int 0 if everything went fine, or an exit code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $phrase = self::PHRASES[rand(0, count(self::PHRASES) - 1)];
        $output->writeln(PHP_EOL . "<info>\t$phrase </info>" . PHP_EOL);
        return 0;
    }
}
