<?php

namespace NS\AceBundle\Calendar\Event;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use NS\AceBundle\Calendar\Model\EventEntity;
use Symfony\Contracts\EventDispatcher\Event;

class CalendarEvent extends Event
{
    public const CONFIGURE = 'calendar.load_events';

    /** @var DateTime */
    private $startDateTime;

    /** @var DateTime */
    private $endDateTime;

    /** @var EventEntity[] */
    private $events;

    private $data;

    public function __construct(DateTime $startDateTime, DateTime $endDateTime, $data = null)
    {
        $this->startDateTime = $startDateTime;
        $this->endDateTime   = $endDateTime;
        $this->events        = new ArrayCollection();
        $this->data          = $data;
    }

    public function getStartDateTime(): DateTime
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTime
    {
        return $this->endDateTime;
    }

    /**
     * @return EventEntity[]
     */
    public function getEvents(): array
    {
        return $this->events->toArray();
    }

    /**
     * @param EventEntity $event
     */
    public function addEvent(EventEntity $event): void
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
