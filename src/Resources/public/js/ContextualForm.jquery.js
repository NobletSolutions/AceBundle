(function($)
{
    /**
     *
     * @param element string #The container to search for forms within.  Accepts either a DOM element or JQuery selector. Defaults to document.
     * @param globalConfig Object #Basic configuration for the class.  Two properties:
     *          event: The form event to listen for.  Defaults to 'input' so we don't have to defocus the field to get feedback
     *          ignoreClass: Class name of elements to be ignored from the toggling functionality.  Defaults to 'ignore'
     * @param autoinit Boolean #Whether or not to automatically init the form handlers as soon as the class is instantiated.  Defaults to true
     * @constructor
     */
    $.ContextualForm = function(element, globalConfig, autoinit)
    {
        var autoinit = autoinit ? autoinit: true;
        var defaultConfig = {
            'event': 'input',
            'ignoreClass': 'ignore'
        };

        //Just use the document if we don't pass an element
        if(element === undefined)
        {
            element = document;
        }

        this.globalConfig = $.extend(defaultConfig, globalConfig); //Merge the default and provided configs
        this.element   = (element instanceof $) ? element: $(element); //Convert the element arg to a JQuery object if it isn't one
        this.forms     = null;

        if(autoinit)
        {
            this.Init();
        }
    };

    $.ContextualForm.prototype = {
        /**
         * Init the contextual form plugin
         * @constructor
         */
        Init: function()
        {
            var cform   = this; //#JustJavascriptScopeThings
            cform.forms = cform.element.find('form[data-context-config]'); //Find any forms that have a config

            //Parse the config for each form and add it to the DOM element
            cform.forms.each(function()
            {
                this.contextConfig = $(this).data('context-config');
            });
            this.AddListeners();
        },

        /**
         * Loop through the form configs, find which fields we need to add handlers to, and start the process
         *
         * @constructor
         */
        AddListeners: function()
        {
            var cform = this;

            //Get the config back from the DOM element
            cform.forms.each(function()
            {
                var $form  = $(this); //This is the <form> element in this context
                var config = this.contextConfig;

                //Grab each element from the config and add the event listeners for it
                $.each(config, function(index, value){
                    cform.ProcessFormConfig($form, index, value); //'this' refers to the current config item, because loop
                });
            });
        },

        /**
         * Convert the fields defined in the form config to actual Jquery objects, then add the event listeners
         *
         * @param $form Object #The current form element (JQuery object)
         * @param field String #The name of the field to process the config for
         * @param config Object #The config (JSON) for this field
         * @constructor
         */
        ProcessFormConfig: function($form, field, config)
        {
            var cform = this;
            //Get the actual form element
            var $field = $form.find('[name="'+field+'"], [name="'+field+'[]"]');//Checkboxes will append a [] to the name

            //We're doing all of this before the event handler so it only happens once on init.

            //Loop through the config, convert the parameters to actual JQuery objects, and update the config
            $.each(config, function(index, conf)
            {
                var targets = [];
                if(conf.display instanceof Array) {
                    $.each(conf.display, function(i, dis)
                    {
                        targets.push(cform.DisplayConfToSelector($form, dis));
                    });
                } else {
                    targets = [cform.DisplayConfToSelector($form, conf.display)];
                }

                //Make this always an array for ease of comparison
                if(!(conf.values instanceof Array)) {
                    conf.values = [conf.values];
                }

                //It's HTML, so deep down these were always strings, anyway. Make it so.
                var arr = [];
                $.each(conf.values, function(i, v) {
                    arr.push(String(v));
                });

                conf.values = arr;

                conf.display = targets;

            });

            this.AddListener($field, config);

            //Run once on init to account for pre-filled values
            this.Go($field, config, false);
        },

        /**
         * Actually create the event listener
         *
         * @param $field Object #The form field.  JQuery object.
         * @param config
         * @constructor
         */
        AddListener: function($field, config)
        {
            var cform = this;

            var event = $field.is('[type=checkbox], [type=radio], select') ? 'change':cform.globalConfig.event; //Sadly, chrome only supports oninput on text fields

            $field.on(event, function($event)
            {
                cform.Go($(this), config, $event)
            });
        },

        /**
         * Get a Jquery object based on the nature of the arg
         *
         * @param $form Object #The current form
         * @param dis String #The name or ID of the element we want to toggle
         * @returns {*}
         * @constructor
         */
        DisplayConfToSelector: function($form, dis)
        {
            if(dis.indexOf('#') > -1) //If we passed an id selector, just use that ID.
            {
                return $(dis);
            }
            else
            {
                return this.FindWrapper($form, $form.find('[name="'+dis+'"], [name="'+dis+'[]"]')); //Otherwise, find the field by name.
            }
        },

        /**
         * We usually don't want to hide JUST the form field, since there are things like form-group divs that need to be hidden as well.  Find them.
         *
         * @param $form Object #The current form
         * @param $el Object #Form field we're toggling
         * @returns {*}
         * @constructor
         */
        FindWrapper: function($form, $el)
        {
            //Find the parent element to toggle, if appropriate
            var $f = this.FindWrapperForFieldType($form, $el);

            //If that gave us something, return it.  Otherwise, merge the form field and its label into a single collection we can toggle at once
            return $f ? $f : $el.add($form.find($('label[for='+$el.attr('id'))));
        },

        /**
         * Try to intelligently find the appropriate container to toggle based on the form input type
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {boolean}
         * @constructor
         */
        FindWrapperForFieldType: function($form, $field)
        {
            var ret = false;

            if(!ret && $field.is('input[type=checkbox]'))
            {
                ret = this.FindWrapperForCheckbox($form, $field);
            }

            if(!ret && $field.is('input[type=radio]'))
            {
                ret = this.FindWrapperForRadioButton($form, $field);
            }

            if(!ret && $field.is('input, select, textarea'))
            {
                ret =  this.FindWrapperForInput($form, $field);
            }

            return ret;
        },

        /**
         * For standard text inputs, try to find a parent form-group, and we'll hide the whole thing
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         * @constructor
         */
        FindWrapperForInput: function($form, $field)
        {
            var $parent = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if ($parent) {
                return $parent;
            }

            $parent = $field.parent('label').not(this.GetIgnoreSelector());

            if ($parent) {
                return $parent;
            }

            return false;
        },

        /**
         * Checkboxes and radiobuttons have a couple extra layers of parents we need to account for.
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         * @constructor
         */
        FindWrapperForCheckbox: function($form, $field)
        {
            var $group = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if($group) {
                return $group;
            }

            var $parent = $field.closest('.checkbox').not(this.GetIgnoreSelector());

            if($parent) {
                return $parent;
            }

            return false;
        },

        /**
         * Checkboxes and radiobuttons have a couple extra layers of parents we need to account for.
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         * @constructor
         */
        FindWrapperForRadioButton: function($form, $field)
        {
            var $group = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if($group)
            {
                return $group;
            }

            var $parent = $field.closest('.radio').not(this.GetIgnoreSelector());

            if($parent)
            {
                return $parent;
            }

            return false;
        },

        /**
         * Convert the ignore arg to a Jquery selector so we don't have to concat the . every time
         *
         * @returns {string}
         * @constructor
         */
        GetIgnoreSelector: function()
        {
            return '.'+this.globalConfig.ignoreClass;
        },

        /**
         * We need to do some pre-processing for fields like checkboxes to get the correct value
         *
         * @param $field Object #The current field
         * @param match Array #The values we want to match the field against
         * @returns {boolean}
         * @constructor
         */
        MatchFieldValue: function($field, match)
        {
            var vals = [];
            var $fields;
            if($field.is('[type=checkbox]') || $field.is('[type=radio]'))
            {
                $fields = $field.filter(':checked');
                $fields.each(function()
                {
                    vals.push(String($(this).val()));
                });
            }
            else if($field.is('select[multiple]'))
            {
                $fields = $field.find('option:selected');
                $fields.each(function()
                {
                    vals.push(String($(this).val()));
                });
            }
            else
            {
                vals.push(String($field.val()));
            }

            var intersect = ns_array_intersect([vals, match]); //Some form fields return multiple values, so we have to intersect those with the ones we're looking for

            return intersect.length > 0;
        },

        /**
         * Fire the show/hide event
         *
         * @param $field Object #The form field having the event
         * @param config Object #The config for this form field
         * @constructor
         */
        Go: function($field, config)
        {
            var cform = this;
            var show  = [];

            //There are potentially multiple configs for this field, for different sets of child fields dependent on different form values.
            $.each(config, function(index, conf)
            {
                //Loop through each "child" field that is controlled by this "parent" field, in this config
                $.each(conf.display, function(index, disField)
                {
                    disField.hide();//Reset everything
                    //If the parent field value matches the value in the config, display the child fields
                    if(cform.MatchFieldValue($field, conf.values))
                    {
                        show.push(disField);
                    }
                });
            });

            //We do this after the loop, because if we do it within, the disField.hide() call could hide a field we just showed in the previous loop
            $.each(show, function(index, $field)
            {
                $field.show().trigger('nsFormUpdate');
            });
        }
    }
}(jQuery));

/**
 * Calculate the intersection between two or more arrays
 *
 * @param arrays
 */
var ns_array_intersect = function(arrays)
{
    return arrays.shift().filter(function(v) {
        return arrays.every(function(a) {
            return a.indexOf(v) !== -1;
        });
    });
};
