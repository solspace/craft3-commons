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

        if (!file_exists($filePath)) {
            $this->file      = null;
            $this->lineCount = 0;

            return;
        }

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
        if (null === $this->file) {
            return [];
        }

        $targetLine = $this->lineCount - $numberOfLines;
        $lines      = [];

        if ($targetLine <= 1) {
            $targetLine = 1;
        }

        $this->file->seek($targetLine);
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
        if (null !== $this->file) {
            $this->file->rewind();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        if (null !== $this->file) {
            $this->file->next();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        if (null !== $this->file) {
            return $this->parser->parse($this->file->current());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        if (null !== $this->file) {
            return $this->file->key();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if (null !== $this->file) {
            return $this->file->valid();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->lineCount;
    }
}
