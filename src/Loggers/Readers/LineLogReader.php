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
        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $i = 0;
        while (!$this->file->eof()) {
            $line = $this->file->current();
            if (!empty($line)) {
                $i++;
            }

            $this->file->next();
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
        $lines = [];
        if (null === $this->file) {
            return $lines;
        }

        $this->file->seek($this->lineCount);
        $currentKey = $this->file->key();
        while ($currentKey >= 0) {
            $line = $this->getDefaultParser()->parse($this->file->current());
            if ($line) {
                $lines[] = $line;
            }

            if (--$currentKey >= 0) {
                $this->file->seek($currentKey);
            }

            if (\count($lines) >= $numberOfLines) {
                break;
            }
        }

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
