<?php

namespace NS\AceBundle\Filter\Search;

class Select2SearchResult implements Select2SearchResultInterface
{
    private $id;
    private $text;

    public function __construct($id, $text)
    {
        $this->id   = $id;

        if ($text === null) {
	    throw new  \InvalidArgumentException('Unable to build search result with blank text for id: '.$id);
        }

        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'   => $this->id,
            'text' => $this->text,
        ];
    }
}
