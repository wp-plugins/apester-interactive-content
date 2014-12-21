(function(window) {
    'use strict';

    var document = window.document,
        baseUrl = configuration.rendererBaseUrl;

    /**
     * Bootstraps the script and replaces embed elements with interactions iframe
     */
    function boot() {
        // Render all manual interactions.
        var manualEmbeds = findEmbeds({random: false});
        if (manualEmbeds.length > 0) {
            manualEmbeds.forEach(replace);
        }
        else {
            // No manual interactions - render all random interactions.
            findEmbeds({random: true}).forEach(replace);
        }

        // Disable listeners to avoid double boot executions.
        document.removeEventListener('DOMContentLoaded', boot);
        window.removeEventListener('load', boot);
    }

    /**
     * Calls given callback when DOM is ready
     * @param {function} callback
     */
    function onDocumentReady(callback) {
        if (document.readyState === 'complete') {
            // Register event to document on-load
            return callback();
        }

        document.addEventListener('DOMContentLoaded', callback, false);
        window.addEventListener('load', callback, false);
    }

    /**
     * Replaces given element with interaction iframe by element.id
     * @param elm
     */
    function replace(elm) {
        if (elm.dataset.random !== undefined) {
            return elm.parentNode.replaceChild(buildInteractionIframe(elm.dataset.random, true), elm);
        }

        return elm.parentNode.replaceChild(buildInteractionIframe(elm.id), elm);
    }

    /**
     * Builds interaction iframe element.
     * @param {string} id
     * @param {boolean} random
     * @returns {document.Element}
     */
    function buildInteractionIframe(id, random) {
        var iframe = document.createElement('iframe');

        iframe.src = random ? composeInteractionSrc(id, true) : composeInteractionSrc(id, false);
        iframe.class = 'qmerce-interaction';
        iframe.setAttribute('data-interaction-id', id);
        iframe.scrolling = 'no';
        iframe.frameBorder = 0;
        iframe.width = '100%';
        iframe.height = 330;
        iframe.style['max-height'] = '330px';

        if (document.body.clientWidth < 600) {
            iframe.height = 400;
        }

        return iframe;
    }

    /**
     * Composes interaction iframe URL.
     * @param {string} id
     * @param {boolean} random
     * @returns {string}
     */
    function composeInteractionSrc(id, random) {
        if (random) {
            return baseUrl + '/interaction/random/' + id;
        }

        return baseUrl + '/interaction/' + id;
    }

    /**
     * Finds embed elements within the current DOM
     * @returns {*}
     */
    function findEmbeds(filter) {
        if (!filter) {
            return Array.prototype.slice.call(document.getElementsByTagName('interaction'), 0);
        }
        var filetered = Array.prototype.slice.call(document.getElementsByTagName('interaction'), 0);
        for (var index = 0; index < filetered.length; index++) {
            var elm = filetered[index];
            if ((filter.random && elm.dataset.random == undefined) ||
                (!filter.random && elm.dataset.random != undefined)) {
                filetered.splice(index, 1);
            }
        }
        return filetered;
    }

    onDocumentReady(boot);

}(window, undefined));
