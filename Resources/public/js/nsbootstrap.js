$(document).ready(function() {
    $(this).trigger('nsFormUpdate');

    $('.entity-modal').on('hidden.bs.modal', function(ev)
    {
        var modal = $(this);
        var vals  = false;

        modal.find(':input').each(function()
        {
            if((this.type !== 'checkbox' && $(this).val() !== '') || this.checked)
                vals = true;
        });

        if(vals)
        {
            $('#'+modal.data('input')).prev('ul.token-input-list').hide().prev('span.input-group-addon').hide();
            $('[data-target="#'+modal.attr('id')+'"] span.fa-plus').removeClass('fa-plus').addClass('fa-pencil');
            $('[data-target="#'+modal.attr('id')+'"] span.entity-modal-status').text('Edit');
            $('[data-target="#'+modal.attr('id')+'"]').removeClass('btn-success').addClass('btn-primary');
        }
        else
        {
            $('#'+modal.data('input')).prev('ul.token-input-list').show().prev('span.input-group-addon').show();
            $('[data-target="#'+modal.attr('id')+'"] span.fa-pencil').removeClass('fa-pencil').addClass('fa-plus');
            $('[data-target="#'+modal.attr('id')+'"] span.entity-modal-status').text('Add');
            $('[data-target="#'+modal.attr('id')+'"]').removeClass('btn-primary').addClass('btn-success');
        }
    });

    $('.modal.openOnLoad').modal();

    $('body').append('<div class="modal fade" id="nsAjaxLoadingModal" tabindex="-1" role="dialog" aria-labelledby="nsAjaxLoadingModal"><i class="fa fa-spinner fa-pulse fa-3x fa-fw fa-inverse"></i></div>');

    $(document).bind('ns:AjaxFormSend', function (event) {
        $('#nsAjaxLoadingModal').modal('show');
        $tgt = $(event.target);
        if($tgt.closest('.modal'))
        {
            $tgt.closest('.modal').modal('hide');
        }
    }).bind('ns:AjaxFormComplete', function (event) {
        $('#nsAjaxLoadingModal').modal('hide');
        $tgt = $(event.target);
        if($tgt.closest('.modal'))
        {
            $tgt.closest('.modal').modal('show');
        }
    });
});

$(document).click(function(ev)
{
    var target = $(ev.target);

    if(target.is('.nsAddForm'))
    {
        ev.preventDefault();
        var collection = $('[data-collection=' + target.data('collectionholder') + ']').first();
        var index      = collection.data('index');
        var newForm    = collection.data('prototype').replace(/__name__/g, index);
        collection.append(newForm);
        $(document).trigger('nsFormUpdate');
    }

    if(target.is('[data-toggle=modal]'))
    {
        setTimeout(function()
        {
            $(document).trigger('nsFormUpdate');
        }, 200);
    }

    if(target.is('[data-showelement]'))
    {
        $(target.data('showelement')).show();
        $(document).trigger('nsFormUpdate');
    }

    if(target.is('div.modal button.modal-clear'))
    {
        target.closest('div.modal').find(':input').val('');

        if(!target.data('dismiss'))
            ev.preventDefault();
    }
});

$(document).on('nsFormUpdate shown.bs.tab shown.bs.collapse sonata.add_element ajaxComplete', function(ev)
{
    bindNsAjaxEvents();

    $('.date-picker').each(function(i, el)
    {
        if($(el).is(':visible'))
        {
            if(el.nsFieldActive !== true)
            {
                el.nsFieldActive = true;
                $(el).datepicker({autoclose:true}).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
            }
        }
    });

    $('.date-range').each(function(i, el)
    {
        if($(el).is(':visible'))
        {
            if(el.nsFieldActive !== true)
            {
                el.nsFieldActive = true;
                $(el).daterangepicker({autoclose:true}).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
            }
        }
    });

    $('input[type=file]:not([multiple])').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).ace_file_input({
                no_file:'No File ...',
                btn_choose:'Choose',
                btn_change:'Change',
                droppable:false,
                onchange:null,
                thumbnail:false //| true | large
                //whitelist:'gif|png|jpg|jpeg'
                //blacklist:'exe|php'
                //onchange:''
                //
            });
        }
    });

    $('div.nsFileUpload div.nsUploader').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).after('\
                <div class="ace-file-input">\
                    <label class="file-label" data-title="Browse">\
                        <span class="file-name" data-title="Drop file here or click &quot;Browse&quot;...">\
                            <i class="icon-upload-alt"></i>\
                        </span>\
                    </label>\
                </div>');
            new PunkAveFileUploader({
                'uploadUrl': $(el).data('uploadUrl'),
                'viewUrl': $(el).data('uploadUrl'),
                'el': $(el),
                'existingFiles': [],
                'delaySubmitWhileUploading': '.edit-form'
            });
        }
    });

    $('input.nsAutocompleter').each(function(i, el)
    {
        $el = $(el);
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            var options = $el.data('options');

            if($el.val())
                options.prePopulate = JSON.parse($el.val());

            var queryUrl = $el.data('autocompleteurl');

            if($el.data('autocomplete-secondary-field'))
            {
                var sec = $el.data('autocomplete-secondary-field');
                var $tgt = $('#'+$el.attr('id').replace(sec.s, sec.r));

                if($tgt)
                {
                    var delim = ($el.data('autocompleteurl').indexOf('?') >= 0 ? '&' : '?');
                    queryUrl = $el.data('autocompleteurl')+delim+'secondary-field='+$tgt.val();

                    $tgt.change(function()
                    {
                        $el.tokenInput('setOptions', {'url':$el.data('autocompleteurl')+delim+'secondary-field='+$tgt.val()});
                    });
                }
            }

            options.onReady = function()
            {
                $el.tokenInput('setOptions', {'url':queryUrl});
            };

            if($el.data('resultsformatter'))
            {
                options.resultsFormatter=eval($el.data('resultsformatter'));
            }

            if($el.data('tokenformatter'))
            {
                options.tokenFormatter=eval($el.data('tokenformatter'));
            }

            $el.tokenInput($el.data('autocompleteurl'), options);
        }
    });

    $('input.nsTag').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            var params = {caseInsensitive:$(el).data('case-insensitive'), allowDuplicates:$(el).data('case-allow-duplicates'), autocompleteOnComma:$(el).data('autocomplete-on-comma')};

            if($(el).data('source'))
                params.source = $(el).data('source');

            $(el).tag(params);
        }
    });


    $('input.nsSpinner').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).ace_spinner($(el).data('options'));
        }
    });

    $('input.nsMasked').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $.extend($.mask.definitions, $(el).data('definitions'));

            $(el).mask($(el).data('mask'), {placeholder:$(el).data('placeholder')});
            $(el).parents('div.form-group').children('label').append(' <small class="text-info">'+$(el).data('mask')+'</small>');
        }
    });

    $('input.nsKnob').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).knob();
        }
    });

    $('input.time-picker').each(function(i, el)
    {
        if($(el).is(':visible'))
        {
            if(el.nsFieldActive !== true)
            {
                el.nsFieldActive = true;
                $(el).timepicker({
                    minuteStep: 1,
                    showSeconds: ($(this).data('showSeconds') === 'true'),
                    showMeridian: ($(this).data('showMeridian') === 'true'),
                    defaultTime: false,
                    icons: {
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down'
                    }
                }).on('focus', function() {
                    $(el).timepicker('showWidget');
                }).next().on(ace.click_event, function() {
                    $(this).prev().focus();
                });
            }
        }
    });

    $('.chosen-select').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).chosen({allow_single_deselect:true, search_contains:($(el).data('search-contains') ? true : false)});
        }
    });

    $('a.filter_legend').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).click(function(event)
            {
                var icon = $(this).find('i');
                icon.toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');
                $(this).find('span').html((icon.hasClass('icon-chevron-up')?'Simple':'Advanced'));
                $(this).parents('.widget-box').find('div.filter_container .sonata-filter-option').toggle();
            });
        }
    });

    $('div.filter_container .sonata-filter-option').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            $(el).toggle();
        }
    });

    $('input[data-context-child], select[data-context-child], textarea[data-context-child], div[data-context-child] input').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            el.nsFieldActive = true;
            //Add event handlers
//            el.contextState = 'active';
            $(el).change(function(event)
            {
                toggleContextFields(el);
            });

            //Call on init for pre-filled fields
            toggleContextFields(el);
        }
    });

    function toggleContextFields(el)
    {
        //Initialize
        el.values       = [];
        var value = $(el).val();

        //I... don't remember why I did this :S
        if(el.tagName.toLowerCase() === 'input' && (el.type.toLowerCase() === 'radio' || el.type.toLowerCase() === 'checkbox') && !el.checked)
            value = 0;

        //If we're dealing with a multiselect/multiple checkboxes, store the currently selected values in an array
        if(!$(el).data('context-child'))
        {
            $(el).parent('[data-context-child]').find('input:checked').each(function(ii, ipt)
            {
                el.values.push($(ipt).val().toString());
            });
        }
        else
            el.values = [value];

        //Find all the fields that are watching the parent/container for multiselects/checkboxes
        $('[data-context-parent='+($(el).data('context-child')?$(el).data('context-child'):$(el).parent('[data-context-child]').data('context-child'))+'][data-context-value]').each(function(i, input)
        {

            input.parent = el;
            var f       = $(input).data('context-value');
            var fields  = typeof f === 'object' ? f.join().split(',') : [f.toString()]; //hacky way to get around variable typing in indexOf; find a better way to do this.
            var label   = $('label[for='+input.id+']');
            var element = $(input).parent().hasClass('input-group') ? $(input).parent() : input;

            var result = false;

            //Find out if any of the values we're watching for appear in our array of selected values
            for(var a = 0; a < fields.length; a++)
            {
                if($.inArray(fields[a], el.values) > -1)
                {
                    result = true;
                    break;
                }
            }

            if(result)
            {
                if(input.parent.contextState === undefined || input.parent.contextState === 'active')
                {
                    $(element).show();
                    label.show();
                    input.contextState = 'active';
                }
                else
                {
                    $(element).hide();
                    label.hide();
                    input.contextState = 'inactive';
                }
            }
            else
            {
                $(element).hide();
                label.hide();
                input.contextState = 'inactive';
            }


            $(document).trigger('nsFormUpdate');
            toggleContextFields(input);
        });
    }

    $('[data-rel=tooltip]').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            $(el).tooltip({container:'body'});
        }
    });

    $('[data-rel=popover]').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            $(el).popover({container:'body'});
        }
    });

    $('.ns-confirm').each(function(i, el)
    {
        //if($(el).is(':visible')) // Why are we doing this? I can't imagine why we would want to restrict this to visible elements.
        //{
        if(el.nsFieldActive !== true)
        {
            var msg = 'Are you sure you wish to continue?';

            if($(el).data('confirm-message'))
            {
                msg = $(el).data('confirm-message');
            }

            el.nsFieldActive = true;

            if($(el).is('form'))
            {
                $(el).submit(function()
                {
                    return confirm(msg);
                });
            }
            else
            {
                $(el).click(function()
                {
                    return confirm(msg);
                });
            }
        }
        //}
    });
});

var bindNsAjaxEvents = function () {
    $('.ajaxUpdater').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            var $updater = $(el);
            $updater.one('click', function (event)
            {
                event.preventDefault();
                $($updater).trigger('ns:AjaxFormSend');

                $.ajax($updater.attr('href'), {
                    success: function (responsedata, status, jqxhr)
                    {
                        var $update = $($updater.data('update'));
                        $update.html(responsedata);
                        $update.trigger('ns:AjaxFormComplete');
                    }
                });

                return false;
            });

            el.nsFieldActive = true;
        }
    });

    $('.ajaxForm').each(function(i, el)
    {
        if(el.nsFieldActive !== true)
        {
            var $form = $(el);
            $form.one('submit', function (event)
            {
                console.log('there');
                event.preventDefault();
                var formData = new FormData($form[0]);
                $($form).trigger('ns:AjaxFormSend');
                $.ajax($form.attr('action'), {
                    method: $form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (responsedata, status, jqxhr)
                    {
                        $update = $($form.data('update'));
                        $update.html(responsedata);
                        $update.trigger('ns:AjaxFormComplete');
                    }
                });

                return false;
            });

            el.nsFieldActive = true;
        }
    });
};


