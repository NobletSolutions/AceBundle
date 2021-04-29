<?php

namespace NS\AceBundle\Filter\Search;

class Select2SearchResult implements Select2SearchResultInterface
{
    private $id;
    private $text;
    private ?array $extra = null;

    public function __construct($id, $text)
    {
        $args     = func_get_args();
        $this->id = $id;

        if ($text === null) {
            throw new  \InvalidArgumentException('Unable to build search result with blank text for id: ' . $id);
        }

        $this->text = $text;
        $extra      = array_splice($args, 2);


        if (!empty($extra)) {
            $this->extra = $extra;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function jsonSerialize(): array
    {
        $out = [
            'id'   => $this->id,
            'text' => $this->text,
        ];

        if ($this->extra) {
            $out['extra'] = $this->extra;
        }

        return $out;
    }
}
