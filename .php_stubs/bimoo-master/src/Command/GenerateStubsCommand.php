<?php

namespace MAChitgarha\Bimoo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Webmozart\PathUtil\Path;

class GenerateStubsCommand extends Command
{
    private const NAME = "generate-stubs";
    private const DESCRIPTION = "Generates the stubs.php file.";

    private const ARG_MOODLE_ROOT_NAME = "moodle-root-dir";
    private const ARG_MOODLE_ROOT_DESCRIPTION =
        "Moodle root directory, either the repository or the installed.";

    private const STUBS_GENERATOR_EXECUTABLE_PATH =
        __DIR__ . "/../../vendor/bin/generate-stubs";
    private const PATH_LIST_FILE_PATH =
        __DIR__ . "/../../data/path-list.json";
    private const OUTPUT_FILE_PATH =
        __DIR__ . "/../../stubs.php";

    protected function configure()
    {
        // Metadata
        $this
            ->setName(static::NAME)
            ->setDescription(static::DESCRIPTION)
        ;

        // Arguments
        $this
            ->addArgument(
                static::ARG_MOODLE_ROOT_NAME,
                InputArgument::REQUIRED,
                static::ARG_MOODLE_ROOT_DESCRIPTION,
            )
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $moodleRootDir = $input->getArgument(static::ARG_MOODLE_ROOT_NAME);

        $sources = $this->generateGlobPatternMatches(
            $this->appendPathPrefix(
                $this->getSourcePaths(),
                $moodleRootDir
            )
        );

        return $this->callPhpStubsGenerator($output, $sources);
    }

    /**
     * Returns the source paths the stubs will be generated from.
     * @return string[]
     */
    private static function getSourcePaths(): array
    {
        $file = new \SplFileObject(self::PATH_LIST_FILE_PATH, "r");
        $fileContents = $file->fread($file->getSize());

        return \json_decode($fileContents);
    }

    /**
     * @return string[]
     */
    private static function appendPathPrefix(
        array $paths,
        string $prefix
    ): array {
        foreach ($paths as &$path) {
            $path = Path::join($prefix, $path);
        }
        return $paths;
    }

    /**
     * @return string[]
     */
    private static function generateGlobPatternMatches(array $paths): array
    {
        $resultPaths = [];

        foreach ($paths as $path) {
            $resultPaths = [
                ...$resultPaths,
                ...\glob($path),
            ];
        }

        return $resultPaths;
    }

    private function callPhpStubsGenerator(
        OutputInterface $output,
        array $sources
    ): int {
        $input = new ArrayInput([
            "sources" => $sources,
            "--out" => self::OUTPUT_FILE_PATH,
            // Overwrite the existing file
            "--force" => true,
        ]);

        return (new \StubsGenerator\GenerateStubsCommand())->run(
            $input,
            $output,
        );
    }
}
