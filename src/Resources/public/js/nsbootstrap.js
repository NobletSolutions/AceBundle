$(document).ready(function () {
    $(this).trigger('nsFormUpdate');

    $('.entity-modal').on('hidden.bs.modal', function (ev) {
        var modal = $(this);
        var vals  = false;

        modal.find(':input').each(function () {
            if ((this.type !== 'checkbox' && $(this).val() !== '') || this.checked)
                vals = true;
        });

        if (vals) {
            $('#' + modal.data('input')).prev('ul.token-input-list').hide().prev('span.input-group-addon').hide();
            $('[data-target="#' + modal.attr('id') + '"] span.fa-plus').removeClass('fa-plus').addClass('fa-pencil');
            $('[data-target="#' + modal.attr('id') + '"] span.entity-modal-status').text('Edit');
            $('[data-target="#' + modal.attr('id') + '"]').removeClass('btn-success').addClass('btn-primary');
        } else {
            $('#' + modal.data('input')).prev('ul.token-input-list').show().prev('span.input-group-addon').show();
            $('[data-target="#' + modal.attr('id') + '"] span.fa-pencil').removeClass('fa-pencil').addClass('fa-plus');
            $('[data-target="#' + modal.attr('id') + '"] span.entity-modal-status').text('Add');
            $('[data-target="#' + modal.attr('id') + '"]').removeClass('btn-primary').addClass('btn-success');
        }
    });

    $('.modal.openOnLoad').modal();

    $('body').append('<div class="modal fade" id="nsAjaxLoadingModal" tabindex="-1" role="dialog" aria-labelledby="nsAjaxLoadingModal"><i class="fa fa-spinner fa-pulse fa-3x fa-fw fa-inverse"></i></div>');

    $(document).on('ns:AjaxFormSend', function (event) {
        $('#nsAjaxLoadingModal').modal('show');
        $tgt = $(event.target);
        if ($tgt.closest('.modal')) {
            $tgt.closest('.modal').modal('hide');
        }
    }).on('ns:AjaxFormComplete', function (event) {
        $('#nsAjaxLoadingModal').modal('hide');
        $tgt = $(event.target);
        if ($tgt.closest('.modal')) {
            $tgt.closest('.modal').modal('show');
        }
    });

    $('.readmore').each(function () {
        $(this).css({'max-height': $(this).data('max-height')});
    });

    $('.readmore-expander').on('click', function () {
        var readme = $(this).prev('.readmore');
        if (!this.expanded) {
            readme.css({'overflow-y': 'visible', 'max-height': 'inherit'});
            this.expanded = true;
            $(this).find('i').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
        } else {
            readme.css({'overflow-y': 'hidden', 'max-height': readme.data('max-height')});
            this.expanded = false;
            $(this).find('i').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
        }
    });
});

function handleAddForm(target) {
    var collection     = $('[data-collection=' + target.data('collectionholder') + ']').first();
    var prototype_name = collection.data('prototype-name');
    if (typeof prototype_name !== "undefined") {
        prototype_name = new RegExp(prototype_name, 'g');
    } else {
        prototype_name = new RegExp('__name__', 'g');
    }

    var index   = collection.data('index');
    var newForm = collection.data('prototype').replace(prototype_name, index);
    collection.append(newForm);
    collection.data('index', index + 1);

    var $form = collection.closest('form');
    if ($form.length > 0 && $form[0].ContextualForm) {
        $form[0].ContextualForm.AddConfigFromPrototype($form, index);
    }

    $(document).trigger('nsFormUpdate').trigger('nsAddForm');
}

$(document).on('click', '.nsAddForm', function (ev) {
    var target = $(ev.currentTarget);

    if (target.is('.nsAddForm')) {
        ev.preventDefault();
        handleAddForm(target)
    }
});

$(document).on('click', '[data-toggle=modal]', function (ev) {
    setTimeout(function () {
        $(document).trigger('nsFormUpdate');
    }, 200);
});

$(document).on('click', '[data-showelement]', function (ev) {
    var target = $(ev.currentTarget);

    $(target.data('showelement')).show();
    $(document).trigger('nsFormUpdate');
});

$(document).on('click', 'div.modal button.modal-clear', function (ev) {
    var target = $(ev.currentTarget);

    target.closest('div.modal').find(':input').val('');

    if (!target.data('dismiss'))
        ev.preventDefault();
});

$(document).on('nsFormUpdate shown.bs.tab shown.bs.modal shown.bs.collapse sonata.add_element ajaxComplete shown.ace.widget contextFormUpdate', function (ev) {
    bindNsAjaxEvents();

    $('.date-picker').each(function (i, el) {
        if ($(el).is(':visible')) {
            if (el.nsFieldActive !== true) {
                el.nsFieldActive = true;
                $(el).datepicker({autoclose: true}).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            }
        }
    });

    $('.date-range').each(function (i, el) {
        if ($(el).is(':visible')) {
            if (el.nsFieldActive !== true) {
                el.nsFieldActive = true;
                $(el).daterangepicker({autoclose: true}).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            }
        }
    });

    $('input[type=file]:not([multiple])').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            var $el          = $(el);
            $el.ns_ace_file_input({
                                      no_file: $el.data('no-file-message') ? $el.data('no-file-message') : "Drop file here, or click \'Choose\'",
                                      // no_file: $el.data('no-file-message') ? $el.data('no-file-message') : "Please select a file",
                                      btn_choose: $el.data('choose-message') ? $el.data('choose-message') : 'Choose',
                                      btn_change: $el.data('change-message') ? $el.data('change-message') : 'Change',
                                      droppable:  true,
                                      onchange:   null,
                                      thumbnail:  false //| true | large
                                      //whitelist:'gif|png|jpg|jpeg'
                                      //blacklist:'exe|php'
                                      //onchange:''
                                      //
                                  });
        }
    });

    $('div.nsFileUpload div.nsUploader').each(function (i, el) {
        if (el.nsFieldActive !== true) {
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
                                        'uploadUrl':                 $(el).data('uploadUrl'),
                                        'viewUrl':                   $(el).data('uploadUrl'),
                                        'el':                        $(el),
                                        'existingFiles':             [],
                                        'delaySubmitWhileUploading': '.edit-form'
                                    });
        }
    });

    $('input.nsAutocompleter').each(function (i, el) {
        var $el = $(this);
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            var options      = $el.data('options');

            if ($el.val()) {
                try {
                    options.prePopulate = JSON.parse($el.val());
                } catch (e) {
                    console.warn("Unable to parse tokenInput JSON error: " + e);
                    var jsonStr = '[{"id":"' + $el.val() + '","name":"' + $el.val() + '"}]';
                    try {
                        options.prePopulate = JSON.parse(jsonStr);
                    } catch (subE) {
                        console.warn("Unable to parse manually crafted tokenInput JSON error: " + subE);
                    }
                }
            }

            var queryUrl = $el.data('autocompleteurl');

            if ($el.data('autocomplete-secondary-field')) {
                var sec  = $el.data('autocomplete-secondary-field');
                var $tgt = $('#' + $el.attr('id').replace(sec.s, sec.r));

                if ($tgt.length) {
                    var delim = ($el.data('autocompleteurl').indexOf('?') >= 0 ? '&' : '?');
                    queryUrl  = $el.data('autocompleteurl') + delim + 'secondary-field=' + $tgt.val();

                    $tgt.change(function () {
                        console.log($el.attr('id') + ' UPDATING VALUE: ' + $tgt.val() + ' vs ' + $(this).val());
                        $el.tokenInput('setOptions', {'url': $el.data('autocompleteurl') + delim + 'secondary-field=' + $(this).val()});
                    });
                }
            }

            options.onReady = function () {
                $el.tokenInput('setOptions', {'url': queryUrl});
            };

            if ($el.data('tokenvalue')) {
                try {
                    options.tokenValue = eval($el.data('tokenvalue'));
                } catch (err) {
                    options.tokenValue = $el.data('tokenvalue');
                }
            }

            if ($el.data('resultsformatter')) {
                options.resultsFormatter = eval($el.data('resultsformatter'));
            }

            if ($el.data('tokenformatter')) {
                options.tokenFormatter = eval($el.data('tokenformatter'));
            }

            var url = $el.data('autocompleteurl');

            //is the url a JS defined function
            if (typeof window[url] !== "undefined") {
                $el.tokenInput(window[url], options);
            } else {
                $el.tokenInput(url, options);
            }
        }
    });

    $('input.nsTag').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            var params       = {
                caseInsensitive:     $(el).data('case-insensitive'),
                allowDuplicates:     $(el).data('case-allow-duplicates'),
                autocompleteOnComma: $(el).data('autocomplete-on-comma')
            };

            if ($(el).data('source'))
                params.source = $(el).data('source');

            $(el).tag(params);
        }
    });


    $('input.nsSpinner').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $(el).ace_spinner($(el).data('options'));
        }
    });

    $('input.nsMasked').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $.extend($.mask.definitions, $(el).data('definitions'));

            $(el).mask($(el).data('mask'), {placeholder: $(el).data('placeholder')});
            $(el).parents('div.form-group').children('label').append(' <small class="text-info">' + $(el).data('mask') + '</small>');
        }
    });

    $('input.nsKnob').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $(el).knob();
        }
    });

    $('input.time-picker').each(function (i, el) {
        if ($(el).is(':visible')) {
            if (el.nsFieldActive !== true) {
                el.nsFieldActive = true;
                $(el).timepicker({
                                     minuteStep:   1,
                                     showSeconds:  ($(this).data('showSeconds') === 'true'),
                                     showMeridian: ($(this).data('showMeridian') === 'true'),
                                     defaultTime:  false,
                                     icons:        {
                                         up:   'fa fa-chevron-up',
                                         down: 'fa fa-chevron-down'
                                     }
                                 }).on('focus', function () {
                    $(el).timepicker('showWidget');
                }).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            }
        }
    });

    $('.chosen-select').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $(el).chosen({allow_single_deselect: true, search_contains: ($(el).data('search-contains') ? true : false)});
        }
    });

    $('a.filter_legend').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $(el).click(function (event) {
                var icon = $(this).find('i');
                icon.toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');
                $(this).find('span').html((icon.hasClass('icon-chevron-up') ? 'Simple' : 'Advanced'));
                $(this).parents('.widget-box').find('div.filter_container .sonata-filter-option').toggle();
            });
        }
    });

    $('div.filter_container .sonata-filter-option').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;
            $(el).toggle();
        }
    });

    $('[data-rel=tooltip]').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            $(el).tooltip({container: 'body'});
        }
    });

    $('[data-rel=popover]').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            $(el).popover({container: 'body'});
        }
    });

    $('.ns-confirm').each(function (i, el) {
        //if($(el).is(':visible')) // Why are we doing this? I can't imagine why we would want to restrict this to visible elements.
        //{
        if (el.nsFieldActive !== true) {
            var msg = 'Are you sure you wish to continue?';

            if ($(el).data('confirm-message')) {
                msg = $(el).data('confirm-message');
            }

            el.nsFieldActive = true;

            if ($(el).is('form')) {
                $(el).submit(function () {
                    return confirm(msg);
                });
            } else {
                $(el).click(function () {
                    return confirm(msg);
                });
            }
        }
        //}
    });
    $('.nsSelect2').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;

            let url    = $(this).data('url');
            let config = {debug: true};
            if (url) {
                config.ajax = {
                    url:        url,
                    delay: $(this).data('ajax-delay') ?? 250,
                    method:     $(this).data('method').toUpperCase() ?? 'GET',
                }
            }

            let modal = $(el).closest('.modal');

            if(modal.length) //Select2 has issues if it's within a modal
            {
                config.dropdownParent = modal;
            }

            let initCallback = $(this).data('init-callback');

            if(window[initCallback])
            {
                window[initCallback](this, config);
            }

            $(this).select2(config);
        }
    });
});

var bindNsAjaxEvents = function () {
    $('.ajaxUpdater').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            var $updater = $(el);
            $updater.click(function (event) {
                var isConfirmed = true;
                if ($updater.data('confirm')) {
                    isConfirmed = confirm($updater.data('confirm'));
                }

                if (!isConfirmed) {
                    return false;
                }

                event.preventDefault();
                $($updater).trigger('ns:AjaxFormSend');

                $.ajax($updater.attr('href'), {
                    success: function (responsedata, status, jqxhr) {
                        var $update = $($updater.data('update'));
                        $update.trigger('ns:AjaxFormComplete');
                        $update.html(responsedata);
                    }
                });

                return false;
            });

            el.nsFieldActive = true;
        }
    });

    $('.ajaxForm').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            var $form = $(el);
            $form.submit(function (event) {
                event.preventDefault();

                var success  = $form.data('success');
                var error    = $form.data('error');
                var complete = $form.data('complete');
                var formData = new FormData($form[0]);

                $($form).trigger('ns:AjaxFormSend');

                $.ajax($form.attr('action'), {
                    method:      $form.attr('method'),
                    data:        formData,
                    processData: false,
                    contentType: false,
                    success:     function (responsedata, textStatus, jqXHR) {
                        var $update = $($form.data('update'));
                        var $tgt    = $(document);

                        if ($update.length)//update is optional
                        {
                            $tgt = $update;
                            $update.html(responsedata);
                        }

                        if (success && window[success]) {
                            window[success]($form, responsedata, textStatus, jqXHR);
                        }

                        $tgt.trigger('ns:AjaxFormComplete');
                    },
                    error:       function (jqXHR, textStatus, errorThrown) {
                        if (error && window[error]) {
                            window[error]($form, jqXHR, textStatus, errorThrown);
                        }
                    },
                    complete:    function (jqXHR, textStatus) {
                        if (complete && window[complete]) {
                            window[complete]($form, jqXHR, textStatus);
                        }
                    }
                });

                return false;
            });

            el.nsFieldActive = true;
        }
    });
};


/**
 This is a local override of the Ace file uploader code to enable drag-and-drop
 */
(function ($, undefined) {
    var multiplible   = 'multiple' in document.createElement('INPUT');
    var hasFileList   = 'FileList' in window;//file list enabled in modern browsers
    var hasFileReader = 'FileReader' in window;
    var hasFile       = 'File' in window;

    var Ace_File_Input   = function (element, settings) {
        var self = this;

        var attrib_values = ace.helper.getAttrSettings(element, $.fn.ace_file_input.defaults);
        this.settings     = $.extend({}, $.fn.ace_file_input.defaults, settings, attrib_values);

        this.$element  = $(element);
        this.element   = element;
        this.disabled  = false;
        this.can_reset = true;


        this.$element
            .off('change.ace_inner_call')
            .on('change.ace_inner_call', function (e, ace_inner_call) {
                if (self.disabled) return;

                if (ace_inner_call === true) return;//this change event is called from above drop event and extra checkings are taken care of there
                return handle_on_change.call(self);
                //if(ret === false) e.preventDefault();
            });

        var parent_label = this.$element.closest('label').css({'display': 'block'})
        var tagName      = parent_label.length == 0 ? 'label' : 'span';//if not inside a "LABEL" tag, use "LABEL" tag, otherwise use "SPAN"
        this.$element.wrap('<' + tagName + ' class="ace-file-input" />');

        this.apply_settings();
        this.reset_input_field();//for firefox as it keeps selected file after refresh
    }
    Ace_File_Input.error = {
        'FILE_LOAD_FAILED':  1,
        'IMAGE_LOAD_FAILED': 2,
        'THUMBNAIL_FAILED':  3
    };


    Ace_File_Input.prototype.apply_settings = function () {
        var self = this;

        this.multi      = this.$element.attr('multiple') && multiplible;
        this.well_style = this.settings.style == 'well';

        if (this.well_style) this.$element.parent().addClass('ace-file-multiple');
        else this.$element.parent().removeClass('ace-file-multiple');


        this.$element.parent().find(':not(input[type=file])').remove();//remove all except our input, good for when changing settings
        this.$element.after('<span class="ace-file-container" data-title="' + this.settings.btn_choose + '"><span class="ace-file-name" data-title="' + this.settings.no_file + '">' + (this.settings.no_icon ? '<i class="' + ace.vars['icon'] + this.settings.no_icon + '"></i>' : '') + '</span></span>');
        this.$label     = this.$element.next();
        this.$container = this.$element.closest('.ace-file-input');

        var remove_btn = !!this.settings.icon_remove;
        if (remove_btn) {
            var btn =
                    $('<a class="remove" href="#"><i class="' + ace.vars['icon'] + this.settings.icon_remove + '"></i></a>')
                        .appendTo(this.$element.parent());

            btn.on(ace.click_event, function (e) {
                e.preventDefault();
                if (!self.can_reset) return false;

                var ret = true;
                if (self.settings.before_remove) ret = self.settings.before_remove.call(self.element);
                if (!ret) return false;

                var r = self.reset_input();
                return false;
            });
        }


        if (this.settings.droppable && hasFileList) {
            enable_drop_functionality.call(this);
        }
    }

    Ace_File_Input.prototype.show_file_list = function ($files, inner_call) {
        var files = typeof $files === "undefined" ? this.$element.data('ace_input_files') : $files;
        if (!files || files.length == 0) return;

        //////////////////////////////////////////////////////////////////

        if (this.well_style) {
            this.$label.find('.ace-file-name').remove();
            if (!this.settings.btn_change) this.$label.addClass('hide-placeholder');
        }
        this.$label.attr('data-title', this.settings.btn_change).addClass('selected');

        for (var i = 0; i < files.length; i++) {
            var filename = '', format = false;
            if (typeof files[i] === "string") filename = files[i];
            else if (hasFile && files[i] instanceof File) filename = $.trim(files[i].name);
            else if (files[i] instanceof Object && files[i].hasOwnProperty('name')) {
                //format & name specified by user (pre-displaying name, etc)
                filename = files[i].name;
                if (files[i].hasOwnProperty('type')) format = files[i].type;
                if (!files[i].hasOwnProperty('path')) files[i].path = files[i].name;
            } else continue;

            var index = filename.lastIndexOf("\\") + 1;
            if (index == 0) index = filename.lastIndexOf("/") + 1;
            filename = filename.substr(index);

            if (format == false) {
                if ((/\.(jpe?g|png|gif|svg|bmp|tiff?)$/i).test(filename)) {
                    format = 'image';
                } else if ((/\.(mpe?g|flv|mov|avi|swf|mp4|mkv|webm|wmv|3gp)$/i).test(filename)) {
                    format = 'video';
                } else if ((/\.(mp3|ogg|wav|wma|amr|aac)$/i).test(filename)) {
                    format = 'audio';
                } else format = 'file';
            }

            var fileIcons = {
                'file':  'fa fa-file',
                'image': 'fa fa-picture-o file-image',
                'video': 'fa fa-film file-video',
                'audio': 'fa fa-music file-audio'
            };
            var fileIcon  = fileIcons[format];


            if (!this.well_style) this.$label.find('.ace-file-name').attr({'data-title': filename}).find(ace.vars['.icon']).attr('class', ace.vars['icon'] + fileIcon);
            else {
                this.$label.append('<span class="ace-file-name" data-title="' + filename + '"><i class="' + ace.vars['icon'] + fileIcon + '"></i></span>');
                var type        = (inner_call === true && hasFile && files[i] instanceof File) ? $.trim(files[i].type) : '';
                var can_preview = hasFileReader && this.settings.thumbnail
                                  &&
                                  ((type.length > 0 && type.match('image')) || (type.length == 0 && format == 'image'))//the second one is for older Android's default browser which gives an empty text for file.type
                if (can_preview) {
                    var self = this;
                    $.when(preview_image.call(this, files[i])).fail(function (result) {
                        //called on failure to load preview
                        if (self.settings.preview_error) self.settings.preview_error.call(self, filename, result.code);
                    })
                }
            }
        }

        return true;
    }

    Ace_File_Input.prototype.reset_input = function () {
        this.reset_input_ui();
        this.reset_input_field();
    }

    Ace_File_Input.prototype.reset_input_ui    = function () {
        this.$label.attr({'data-title': this.settings.btn_choose, 'class': 'ace-file-container'})
            .find('.ace-file-name:first').attr({'data-title': this.settings.no_file, 'class': 'ace-file-name'})
            .find(ace.vars['.icon']).attr('class', ace.vars['icon'] + this.settings.no_icon)
            .prev('img').remove();
        if (!this.settings.no_icon) this.$label.find(ace.vars['.icon']).remove();

        this.$label.find('.ace-file-name').not(':first').remove();

        this.reset_input_data();

        //if(ace.vars['old_ie']) ace.helper.redraw(this.$container[0]);
    }
    Ace_File_Input.prototype.reset_input_field = function () {
        //http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery/13351234#13351234
        this.$element.wrap('<form>').parent().get(0).reset();
        this.$element.unwrap();

        //strangely when reset is called on this temporary inner form
        //only **IE9/10** trigger 'reset' on the outer form as well
        //and as we have mentioned to reset input on outer form reset
        //it causes infinite recusrsion by coming back to reset_input_field
        //thus calling reset again and again and again
        //so because when "reset" button of outer form is hit, file input is automatically reset
        //we just reset_input_ui to avoid recursion
    }
    Ace_File_Input.prototype.reset_input_data  = function () {
        if (this.$element.data('ace_input_files')) {
            this.$element.removeData('ace_input_files');
            this.$element.removeData('ace_input_method');
        }
    }

    Ace_File_Input.prototype.enable_reset = function (can_reset) {
        this.can_reset = can_reset;
    }

    Ace_File_Input.prototype.disable = function () {
        this.disabled = true;
        this.$element.attr('disabled', 'disabled').addClass('disabled');
    }
    Ace_File_Input.prototype.enable  = function () {
        this.disabled = false;
        this.$element.removeAttr('disabled').removeClass('disabled');
    }

    Ace_File_Input.prototype.files  = function () {
        return $(this).data('ace_input_files') || null;
    }
    Ace_File_Input.prototype.method = function () {
        return $(this).data('ace_input_method') || '';
    }

    Ace_File_Input.prototype.update_settings = function (new_settings) {
        this.settings = $.extend({}, this.settings, new_settings);
        this.apply_settings();
    }

    Ace_File_Input.prototype.loading = function (is_loading) {
        if (is_loading === false) {
            this.$container.find('.ace-file-overlay').remove();
            this.element.removeAttribute('readonly');
        } else {
            var inside = typeof is_loading === 'string' ? is_loading : '<i class="overlay-content fa fa-spin fa-spinner orange2 fa-2x"></i>';
            var loader = this.$container.find('.ace-file-overlay');
            if (loader.length == 0) {
                loader = $('<div class="ace-file-overlay"></div>').appendTo(this.$container);
                loader.on('click tap', function (e) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    return false;
                });

                this.element.setAttribute('readonly', 'true');//for IE
            }
            loader.empty().append(inside);
        }
    }


    var enable_drop_functionality = function () {
        var self = this;

        var dropbox = this.$element.parent();
        dropbox
            .off('dragenter')
            .on('dragenter', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
            .off('dragover')
            .on('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
            .off('drop')
            .on('drop', function (e) {
                console.log(this, e);
                e.preventDefault();
                e.stopPropagation();

                if (self.disabled) return;

                var dt        = e.originalEvent.dataTransfer;
                var file_list = dt.files;
                if (!self.multi && file_list.length > 1) {//single file upload, but dragged multiple files
                    console.log('multi?');
                    var tmpfiles = [];
                    tmpfiles.push(file_list[0]);
                    file_list = tmpfiles;//keep only first file
                }


                file_list = processFiles.call(self, file_list, true);//true means files have been selected, not dropped
                console.log(file_list);
                if (file_list === false) return false;

                let input = this.querySelector('input');
                console.log(input);
                input.files = file_list;

                self.$element.data('ace_input_method', 'drop');
                self.$element.data('ace_input_files', file_list);//save files data to be used later by user

                self.show_file_list(file_list, true);

                self.$element.triggerHandler('change', [true]);//true means ace_inner_call
                return true;
            });
    }


    var handle_on_change = function () {
        console.log('handle on change');
        var file_list = this.element.files || [this.element.value];/** make it an array */

        file_list = processFiles.call(this, file_list, false);//false means files have been selected, not dropped
        if (file_list === false) return false;

        this.$element.data('ace_input_method', 'select');
        this.$element.data('ace_input_files', file_list);

        this.show_file_list(file_list, true);

        return true;
    }


    var preview_image = function (file) {
        var self  = this;
        var $span = self.$label.find('.ace-file-name:last');//it should be out of onload, otherwise all onloads may target the same span because of delays

        var deferred = new $.Deferred;

        var getImage  = function (src, $file) {
            $span.prepend("<img class='middle' style='display:none;' />");
            var img = $span.find('img:last').get(0);

            $(img).one('load', function () {
                imgLoaded.call(null, img, $file);
            }).one('error', function () {
                imgFailed.call(null, img);
            });

            img.src = src;
        }
        var imgLoaded = function (img, $file) {
            //if image loaded successfully

            var size = self.settings['previewSize'];

            if (!size) {
                if (self.settings['previewWidth'] || self.settings['previewHeight']) {
                    size = {previewWidth: self.settings['previewWidth'], previewHeight: self.settings['previewHeight']}
                } else {
                    size = 50;
                    if (self.settings.thumbnail == 'large') size = 150;
                }
            }
            if (self.settings.thumbnail == 'fit') size = $span.width();
            else if (typeof size == 'number') size = parseInt(Math.min(size, $span.width()));


            var thumb = get_thumbnail(img, size/**, file.type*/);
            if (thumb == null) {
                //if making thumbnail fails
                $(this).remove();
                deferred.reject({code: Ace_File_Input.error['THUMBNAIL_FAILED']});
                return;
            }


            var showPreview = true;
            //add width/height info to "file" and trigger preview finished event for each image!
            if ($file && $file instanceof File) {
                $file.width  = thumb.width;
                $file.height = thumb.height;
                self.$element.trigger('file.preview.ace', {'file': $file});

                var event
                self.$element.trigger(event = new $.Event('file.preview.ace'), {'file': $file});
                if (event.isDefaultPrevented()) showPreview = false;
            }


            if (showPreview) {
                var w = thumb.previewWidth, h = thumb.previewHeight;
                if (self.settings.thumbnail == 'small') {
                    w = h = parseInt(Math.max(w, h))
                } else $span.addClass('large');

                $(img).css({'background-image': 'url(' + thumb.src + ')', width: w, height: h})
                      .data('thumb', thumb.src)
                      .attr({src: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg=='})
                      .show()
            }

            ///////////////////
            deferred.resolve();
        }
        var imgFailed = function (img) {
            //for example when a file has image extenstion, but format is something else
            $span.find('img').remove();
            deferred.reject({code: Ace_File_Input.error['IMAGE_LOAD_FAILED']});
        }

        if (hasFile && file instanceof File) {
            var reader     = new FileReader();
            reader.onload  = function (e) {
                getImage(e.target.result, file);
            }
            reader.onerror = function (e) {
                deferred.reject({code: Ace_File_Input.error['FILE_LOAD_FAILED']});
            }
            reader.readAsDataURL(file);
        } else {
            if (file instanceof Object && file.hasOwnProperty('path')) {
                getImage(file.path, null);//file is a file name (path) --- this is used to pre-show user-selected image
            }
        }

        return deferred.promise();
    }

    var get_thumbnail = function (img, size, type) {
        var imgWidth = img.width, imgHeight = img.height;

        //**IE10** is not giving correct width using img.width so we use $(img).width()
        imgWidth  = imgWidth > 0 ? imgWidth : $(img).width()
        imgHeight = imgHeight > 0 ? imgHeight : $(img).height()

        var previewSize = false, previewHeight = false, previewWidth = false;
        if (typeof size == 'number') previewSize = size;
        else if (size instanceof Object) {
            if (size['previewWidth'] && !size['previewHeight']) previewWidth = size['previewWidth'];
            else if (size['previewHeight'] && !size['previewWidth']) previewHeight = size['previewHeight'];
            else if (size['previewWidth'] && size['previewHeight']) {
                previewWidth  = size['previewWidth'];
                previewHeight = size['previewHeight'];
            }
        }

        if (previewSize) {
            if (imgWidth > imgHeight) {
                previewWidth  = previewSize;
                previewHeight = parseInt(imgHeight / imgWidth * previewWidth);
            } else {
                previewHeight = previewSize;
                previewWidth  = parseInt(imgWidth / imgHeight * previewHeight);
            }
        } else {
            if (!previewHeight && previewWidth) {
                previewHeight = parseInt(imgHeight / imgWidth * previewWidth);
            } else if (previewHeight && !previewWidth) {
                previewWidth = parseInt(imgWidth / imgHeight * previewHeight);
            }
        }


        var dataURL
        try {
            var canvas    = document.createElement('canvas');
            canvas.width  = previewWidth;
            canvas.height = previewHeight;
            var context   = canvas.getContext('2d');
            context.drawImage(img, 0, 0, imgWidth, imgHeight, 0, 0, previewWidth, previewHeight);
            dataURL = canvas.toDataURL(/*type == 'image/jpeg' ? type : 'image/png', 10*/)
        } catch (e) {
            dataURL = null;
        }
        if (!dataURL) return null;


        //there was only one image that failed in firefox completely randomly! so let's double check things
        if (!(/^data\:image\/(png|jpe?g|gif);base64,[0-9A-Za-z\+\/\=]+$/.test(dataURL))) dataURL = null;
        if (!dataURL) return null;


        return {src: dataURL, previewWidth: previewWidth, previewHeight: previewHeight, width: imgWidth, height: imgHeight};
    }


    var processFiles = function (file_list, dropped) {
        var ret = checkFileList.call(this, file_list, dropped);
        if (ret === -1) {
            this.reset_input();
            return false;
        }
        if (!ret || ret.length == 0) {
            if (!this.$element.data('ace_input_files')) this.reset_input();
            //if nothing selected before, reset because of the newly unacceptable (ret=false||length=0) selection
            //otherwise leave the previous selection intact?!!!
            return false;
        }
        if (ret instanceof Array || (hasFileList && ret instanceof FileList)) file_list = ret;


        ret = true;
        if (this.settings.before_change) ret = this.settings.before_change.call(this.element, file_list, dropped);
        if (ret === -1) {
            this.reset_input();
            return false;
        }
        if (!ret || ret.length == 0) {
            if (!this.$element.data('ace_input_files')) this.reset_input();
            return false;
        }

        //inside before_change you can return a modified File Array as result
        if (ret instanceof Array || (hasFileList && ret instanceof FileList)) file_list = ret;

        return file_list;
    }


    var getExtRegex   = function (ext) {
        if (!ext) return null;
        if (typeof ext === 'string') ext = [ext];
        if (ext.length == 0) return null;
        return new RegExp("\.(?:" + ext.join('|') + ")$", "i");
    }
    var getMimeRegex  = function (mime) {
        if (!mime) return null;
        if (typeof mime === 'string') mime = [mime];
        if (mime.length == 0) return null;
        return new RegExp("^(?:" + mime.join('|').replace(/\//g, "\\/") + ")$", "i");
    }
    var checkFileList = function (files, dropped) {
        var allowExt = getExtRegex(this.settings.allowExt);

        var denyExt = getExtRegex(this.settings.denyExt);

        var allowMime = getMimeRegex(this.settings.allowMime);

        var denyMime = getMimeRegex(this.settings.denyMime);

        var maxSize = this.settings.maxSize || false;

        if (!(allowExt || denyExt || allowMime || denyMime || maxSize)) return true;//no checking required


        var safe_files = [];
        var error_list = {}
        for (var f = 0; f < files.length; f++) {
            var file = files[f];

            //file is either a string(file name) or a File object
            var filename = !hasFile ? file : file.name;
            if (allowExt && !allowExt.test(filename)) {
                //extension not matching whitelist, so drop it
                if (!('ext' in error_list)) error_list['ext'] = [];
                error_list['ext'].push(filename);

                continue;
            } else if (denyExt && denyExt.test(filename)) {
                //extension is matching blacklist, so drop it
                if (!('ext' in error_list)) error_list['ext'] = [];
                error_list['ext'].push(filename);

                continue;
            }

            var type;
            if (!hasFile) {
                //in browsers that don't support FileReader API
                safe_files.push(file);
                continue;
            } else if ((type = $.trim(file.type)).length > 0) {
                //there is a mimetype for file so let's check against are rules
                if (allowMime && !allowMime.test(type)) {
                    //mimeType is not matching whitelist, so drop it
                    if (!('mime' in error_list)) error_list['mime'] = [];
                    error_list['mime'].push(filename);
                    continue;
                } else if (denyMime && denyMime.test(type)) {
                    //mimeType is matching blacklist, so drop it
                    if (!('mime' in error_list)) error_list['mime'] = [];
                    error_list['mime'].push(filename);
                    continue;
                }
            }

            if (maxSize && file.size > maxSize) {
                //file size is not acceptable
                if (!('size' in error_list)) error_list['size'] = [];
                error_list['size'].push(filename);
                continue;
            }

            safe_files.push(file)
        }


        if (safe_files.length == files.length) return files;//return original file list if all are valid

        /////////
        var error_count = {'ext': 0, 'mime': 0, 'size': 0}
        if ('ext' in error_list) error_count['ext'] = error_list['ext'].length;
        if ('mime' in error_list) error_count['mime'] = error_list['mime'].length;
        if ('size' in error_list) error_count['size'] = error_list['size'].length;

        var event
        this.$element.trigger(
            event = new $.Event('file.error.ace'),
            {
                'file_count':    files.length,
                'invalid_count': files.length - safe_files.length,
                'error_list':    error_list,
                'error_count':   error_count,
                'dropped':       dropped
            }
        );
        if (event.isDefaultPrevented()) return -1;//it will reset input
        //////////

        return safe_files;//return safe_files
    }


    ///////////////////////////////////////////
    $.fn.ns_aceFileInput = $.fn.ns_ace_file_input = function (option, value) {
        var retval;

        var $set = this.each(function () {
            var $this   = $(this);
            var data    = $this.data('ace_file_input');
            var options = typeof option === 'object' && option;

            if (!data) $this.data('ace_file_input', (data = new Ace_File_Input(this, options)));
            if (typeof option === 'string') retval = data[option](value);
        });

        return (retval === undefined) ? $set : retval;
    };


    $.fn.ns_ace_file_input.defaults = {
        style:       false,
        no_file:     'No File ...',
        no_icon:     'fa fa-upload',
        btn_choose:  'Choose',
        btn_change:  'Change',
        icon_remove: 'fa fa-times',
        droppable:   false,
        thumbnail:   false,//large, fit, small

        allowExt:  null,
        denyExt:   null,
        allowMime: null,
        denyMime:  null,
        maxSize:   false,

        previewSize:   false,
        previewWidth:  false,
        previewHeight: false,

        //callbacks
        before_change: null,
        before_remove: null,
        preview_error: null
    }
})(window.jQuery);

/** /Ace uploader */
