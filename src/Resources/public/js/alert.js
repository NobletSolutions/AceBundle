"use strict";

export default {
    get name() {
        return 'Alert';
    },
    dependenciesLoaded: false,
    script: {
        src:       'https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js',
        integrity: 'sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0='
    },
    stylesheet: {
        src: 'https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css',
        integrity: 'sha256-SCRy3fXoOamBaidKByHs9iJVLYJ65R/v6ycZNN4JhmE='
    },
    async run() {
        await loadDependency(this.script.src, this.script.integrity, 'script');
        await loadDependency(this.stylesheet.src, this.stylesheet.integrity, 'style');

        window.fancyAlert = function(msg, type, title, buttonText) {
            if (typeof Swal === 'undefined') {
                throw new Error('Sweetalert is not installed');
            }

            msg        = msg || '';
            type       = type || 'info';
            buttonText = buttonText || 'OK';

            Swal.fire({
                title:             title,
                text:              msg,
                type:              type,
                icon:              type !== 'danger' ? type : 'error', //the icon for danger is actually 'error'
                buttonsStyling:    false,
                confirmButtonText: buttonText,
                customClass:       {
                    confirmButton: 'btn btn-'+type,
                },
            });
        };

        window.fancyConfirm = async function(msg, type, title, confirmButtonText, cancelButtonText) {
            if (typeof Swal === 'undefined') {
                throw new Error('Sweetalert is not installed');
            }

            msg  = msg || '';
            let buttonType = type = type || 'question';

            if(type === 'question') {
                buttonType = 'default';
            }

            confirmButtonText = confirmButtonText || 'OK';
            cancelButtonText  = cancelButtonText || 'Cancel';

            return await Swal.fire({
                title:          title,
                text:           msg,
                type:           type,
                icon:           type !== 'danger' ? type : 'error', //the icon for danger is actually 'error'
                buttonsStyling: false,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                showCancelButton: true,
                customClass:    {
                    cancelButton:  'btn btn-white',
                    confirmButton: 'btn btn-'+buttonType,
                }
            }).then(result => result.isConfirmed);
        };
    },
};
