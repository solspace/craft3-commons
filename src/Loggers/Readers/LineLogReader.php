<?php

namespace Solspace\Commons\Loggers\Readers;

use Solspace\Commons\Loggers\Parsers\LogParserInterface;

class LineLogReader extends AbstractLogReader implements \Iterator, \Countable
{
    const DEFAULT_NUMBER_OF_LINES = 15;

    /** @var \SplFileObject */
    protected $file;

    /** @var integer */
    protected $lineCount;

    /** @var LogParserInterface */
    protected $parser;

    /**
     * @param string      $filePath
     * @param string|null $defaultPatternPattern
     */
    public function __construct(string $filePath, string $defaultPatternPattern = null)
    {
        parent::__construct($defaultPatternPattern);

        $this->file = new \SplFileObject($filePath, 'r');

        $i = -1;
        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            $i++;
        }

        $this->lineCount = $i;
        $this->parser    = $this->getDefaultParser();
    }

    /**
     * @param LogParserInterface $parser
     *
     * @return $this
     */
    public function setParser(LogParserInterface $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @param int $numberOfLines
     *
     * @return array
     */
    public function getLastLines(int $numberOfLines = self::DEFAULT_NUMBER_OF_LINES): array
    {
        $targetLine = $this->lineCount - $numberOfLines;
        $lines      = [];

        if ($targetLine <= 1) {
            $targetLine = 1;
        }

        $this->file->seek($targetLine - 1);
        while (!$this->file->eof()) {
            $line = $this->getDefaultParser()->parse($this->file->current());
            if ($line) {
                $lines[] = $line;
            }
            $this->file->next();
        }

        $this->file->rewind();

        $lines = array_reverse($lines);

        return $lines;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->file->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->file->next();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->parser->parse($this->file->current());
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->file->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->lineCount;
    }
}
