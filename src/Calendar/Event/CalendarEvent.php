<?php declare(strict_types=1);

namespace NS\AceBundle\Calendar\Event;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use NS\AceBundle\Calendar\Model\EventEntity;
use Symfony\Contracts\EventDispatcher\Event;

class CalendarEvent extends Event
{
    public const
        CONFIGURE = 'calendar.load_events';

    private DateTime $startDateTime;

    private DateTime $endDateTime;

    /** @var Collection<integer, EventEntity> */
    private Collection $events;

    /** @var mixed|null */
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
