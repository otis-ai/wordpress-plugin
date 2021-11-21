import { iframeUrl } from '../constants/otisaiConfig';

function getIframeHeight() {
    const sideMenuHeight = document.getElementById('adminmenuwrap').offsetHeight;
    const adminBarHeight =
        document.getElementById('wpadminbar').offsetHeight || 0;
    if (window.innerHeight < sideMenuHeight) {
        return sideMenuHeight;
    } else {
        return window.innerHeight - adminBarHeight;
    }
}
  
function addIframeResizeEvent(iframe) {
    let animationFrame;
    window.addEventListener(
        'resize',
        () => {
        if (animationFrame) {
            cancelAnimationFrame(animationFrame);
        }
        animationFrame = requestAnimationFrame(() => {
            iframe.style.minHeight = `${getIframeHeight()}px`;
        });
        },
        true
    );
}
  
function createIframeElement(iframeSrc) {
    const iframe = document.createElement('iframe');
    iframe.id = 'otisai-iframe';
    iframe.src = iframeSrc;
    iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    iframe.style.minHeight = `${getIframeHeight()}px`;
    addIframeResizeEvent(iframe);
    return iframe;
}
  
export function createIframe() {
    const iframe = createIframeElement(iframeUrl);
    document.getElementById('otisai-iframe-container').appendChild(iframe);
}
