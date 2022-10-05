<?php

namespace NS\AceBundle\Calendar\Model;

use DateTime;

class EventEntity implements \JsonSerializable
{
    /** Title/label of the calendar event. */
    protected string $title;

    protected DateTime $startDatetime;

    protected ?DateTime $endDatetime;

    /** Is this an all day event? */
    protected bool $allDay = false;

    /** Unique identifier of this event (optional). */
    protected ?string $id = null;

    /** Relative to current path. */
    protected ?string $url = null;

    /** HTML color code for the bg color of the event label. */
    protected ?string $bgColor = null;

    /** HTML color code for the foreground color of the event label. */
    protected ?string $fgColor = null;

    /** CSS class for the event label */
    protected ?string $cssClass = null;

    /** Non-standard fields */
    protected array $otherFields = [];

    public function __construct(string $title, DateTime $startDatetime, ?DateTime $endDatetime = null, bool $allDay = false)
    {
        $this->title         = $title;
        $this->startDatetime = $startDatetime;
        $this->setAllDay($allDay);

        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }

        $this->endDatetime = $endDatetime;
    }

    public function jsonSerialize(): array
    {
        $event = [];

        if ($this->id !== null) {
            $event['id'] = $this->id;
        }

        $event['title'] = $this->title;
        $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");

        if ($this->url !== null) {
            $event['url'] = $this->url;
        }

        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor']     = $this->bgColor;
        }

        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }

        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }

        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }

        $event['allDay'] = $this->allDay;

        foreach ($this->otherFields as $field => $value) {
            $event[$field] = $value;
        }

        return $event;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setBgColor(?string $color): void
    {
        $this->bgColor = $color;
    }

    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    public function setFgColor(?string $color): void
    {
        $this->fgColor = $color;
    }

    public function getFgColor(): ?string
    {
        return $this->fgColor;
    }

    public function setCssClass(?string $class): void
    {
        $this->cssClass = $class;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setStartDatetime(DateTime $start): void
    {
        $this->startDatetime = $start;
    }

    public function getStartDatetime(): DateTime
    {
        return $this->startDatetime;
    }

    public function setEndDatetime(?DateTime $end): void
    {
        $this->endDatetime = $end;
    }

    public function getEndDatetime(): ?DateTime
    {
        return $this->endDatetime;
    }

    public function setAllDay(bool $allDay): void
    {
        $this->allDay = $allDay;
    }

    public function getAllDay(): bool
    {
        return $this->allDay;
    }

    /**
     * @param mixed $value
     */
    public function addField(string $name, $value): void
    {
        $this->otherFields[$name] = $value;
    }

    /**
     * @return mixed|null
     */
    public function getField(string $name)
    {
        return $this->otherFields[$name] ?? null;
    }

    public function removeField(string $name): void
    {
        if (!array_key_exists($name, $this->otherFields)) {
            return;
        }

        unset($this->otherFields[$name]);
    }

    public function getOtherFields(): array
    {
        return $this->otherFields ?? [];
    }
}
