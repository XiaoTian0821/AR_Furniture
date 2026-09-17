/**
 * Main Application JavaScript
 * Handles 3D viewer, AR functionality, and UI interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    initModelViewer();
    initQRCodes();
    initARStatus();
});

function initModelViewer() {
    const viewers = document.querySelectorAll('model-viewer');
    
    viewers.forEach(viewer => {
        // Handle loading state
        viewer.addEventListener('load', () => {
            viewer.classList.add('loaded');
        });
        
        viewer.addEventListener('error', (e) => {
            console.error('Model viewer error:', e);
            const errorMsg = viewer.parentElement.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.style.display = 'block';
            }
        });
        
        // Handle AR status changes
        viewer.addEventListener('ar-status', (e) => {
            console.log('AR status:', e.detail.status);
            handleARStatus(e.detail.status);
        });
        
        // Prevent auto-rotation during AR interaction
        viewer.addEventListener('camera-orbit', () => {
            viewer.setAttribute('auto-rotate', 'false');
        });
    });
}

function initQRCodes() {
    // QR Code generation using a simple SVG approach or external API
    const qrContainers = document.querySelectorAll('#qrcode');
    
    qrContainers.forEach(container => {
        const url = container.closest('.qr-section')?.dataset?.url;
        if (url) {
            generateQRCode(container, url);
        }
    });
}

function generateQRCode(container, url) {
    // Using QR Server API for QR code generation
    const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(url)}`;
    
    const img = document.createElement('img');
    img.src = qrApiUrl;
    img.alt = 'QR Code';
    img.style.width = '150px';
    img.style.height = '150px';
    img.style.borderRadius = '8px';
    
    container.innerHTML = '';
    container.appendChild(img);
}

function initARStatus() {
    const arStatusMessages = document.querySelectorAll('.ar-status-message');
    
    arStatusMessages.forEach(msg => {
        // Check if device supports AR
        if (isAREnabled()) {
            msg.innerHTML = '<i class="fas fa-check-circle"></i><span>AR is available on your device. Tap the AR button to launch.</span>';
            msg.classList.add('ar-available');
        } else {
            msg.innerHTML = '<i class="fas fa-info-circle"></i><span>AR is supported on compatible mobile devices. Open this page on your phone.</span>';
        }
    });
}

function isAREnabled() {
    const viewer = document.querySelector('model-viewer');
    if (!viewer) return false;
    
    // Check for WebXR support
    if ('xr' in navigator) {
        return true;
    }
    
    // Check for Scene Viewer (Android)
    const isAndroid = /Android/i.test(navigator.userAgent);
    if (isAndroid) {
        return true;
    }
    
    // Check for Quick Look (iOS)
    const isIOS = /iPad|iPhone|iPod/i.test(navigator.userAgent);
    if (isIOS) {
        return true;
    }
    
    return false;
}

function launchAR() {
    const viewer = document.querySelector('model-viewer');
    if (viewer) {
        viewer.activate();
    }
}

function handleARStatus(status) {
    const statusElement = document.querySelector('.ar-status');
    if (!statusElement) return;
    
    switch (status) {
        case 'session-started':
            statusElement.textContent = 'AR session started. Point your camera at a flat surface.';
            break;
        case 'session-ended':
            statusElement.textContent = 'AR session ended. Return to 3D view to continue.';
            break;
        case 'not-supported':
            statusElement.textContent = 'AR is not supported on this device.';
            statusElement.classList.add('error');
            break;
        case 'not-enabled':
            statusElement.textContent = 'AR is not enabled for this model.';
            break;
        default:
            break;
    }
}

// Handle visibility changes for AR recovery
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'visible') {
        const viewer = document.querySelector('model-viewer');
        if (viewer && viewer.getAttribute('ar') === 'true') {
            // Reset AR session if needed
            viewer.pause();
        }
    }
});

// Handle pageshow event for AR recovery
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was restored from bfcache
        const viewer = document.querySelector('model-viewer');
        if (viewer) {
            viewer.visible = true;
        }
    }
});
