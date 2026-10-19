'use strict';

window.loadDependency = async function(src, integrity, type) {
    const asset = document.createElement(type === 'script' ? 'script' : 'style');

    const request  = new Request(src, {integrity: integrity});
    const response = await fetch(request);

    if (!response.ok) {
        throw new Error(`Failed to load ${src}, response status: ${response.status}`);
    }

    asset.textContent = await response.text();

    if(type === 'script') {
        document.body.appendChild(asset);
    } else {
        document.getElementById('main-ace-style').after(asset);
    }
};
