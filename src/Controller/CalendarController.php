<?php

namespace NS\AceBundle\Controller;

use DateTime;
use NS\AceBundle\Calendar\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CalendarController extends AbstractController
{
    public function loadCalendarEventsAction(EventDispatcherInterface $eventDispatcher, Request $request)
    {
        $startDatetime = new DateTime($request->get('start'));
        $endDatetime   = new DateTime($request->get('end'));

        $events = $eventDispatcher->dispatch(new CalendarEvent($startDatetime, $endDatetime), CalendarEvent::CONFIGURE)->getEvents();

        return new JsonResponse($events);
    }
}
