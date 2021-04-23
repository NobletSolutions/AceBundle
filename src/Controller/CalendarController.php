<?php

namespace NS\AceBundle\Controller;

use DateTime;
use Exception;
use NS\AceBundle\Calendar\Event\CalendarEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CalendarController
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function loadCalendarEventsAction(Request $request): JsonResponse
    {
        try {
            $startDatetime = new DateTime($request->get('start'));
            $endDatetime   = new DateTime($request->get('end'));
            $data = $request->query->get('options', null);
        } catch (Exception $exception) {
            return new JsonResponse(['message' => 'Invalid date requests'], Response::HTTP_BAD_REQUEST);
        }

        $events = $this->eventDispatcher->dispatch(new CalendarEvent($startDatetime, $endDatetime, $data), CalendarEvent::CONFIGURE)->getEvents();

        return new JsonResponse($events);
    }
}
