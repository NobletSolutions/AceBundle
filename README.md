[![Build Status](https://travis-ci.org/NobletSolutions/AceBundle.svg?branch=master)](https://travis-ci.org/NobletSolutions/AceBundle)

#Features

### FullCalendar

Add the routing configuration
```yml
#app/config/routing.yml
ns_ace:
  resource: "@NSAceBundle/Resources/config/routing.yml"
```

Include the following on the page you want to use the calendar


```twig
{% block javascripts %}
    {{ parent() }}
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js"></script>
{% endblock %}

{% block stylesheets %}
    {{ parent() }}
    <link rel="stylesheet" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css" />
    <link rel="stylesheet" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.print.css" />
{% endblock %}

{% block inlinescripts %}
    <script type="text/javascript">
        $(document).ready(function(){
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#calendar-holder').fullCalendar({
                header: {
                    left: 'prev, next',
                    center: 'title',
                    right: 'month, basicWeek, basicDay,'
                },
                lazyFetching: true,
                timeFormat: {
                    // for agendaWeek and agendaDay
                    agenda: 'h:mmt',    // 5:00 - 6:30

                    // for all other views
                    '': 'h:mmt'         // 7p
                },
                eventSources: [
                    {
                        url: '{{path('ace_calendar_loader')}}',
                        type: 'POST',
                        // A way to add custom filters to your event listeners
                        data: {
                        },
                        error: function() {
                            //alert('There was an error while fetching Google Calendar!');
                        }
                    }
                ]
            });
        });
    </script>
{% endblock %}

{% block body %}
... 
<div id="calendar-holder"></div>
...
{% endblock %}
```
