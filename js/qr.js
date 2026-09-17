/**
 * QR Code Generation Module
 * Generates QR codes for desktop-to-mobile handoff
 */

class QRCodeGenerator {
    constructor(container, url, options = {}) {
        this.container = container;
        this.url = url;
        this.size = options.size || 150;
        this.errorCorrection = options.errorCorrection || 'M';
        this.margin = options.margin || 4;
        this.init();
    }
    
    init() {
        this.generate();
    }
    
    generate() {
        // Use a lightweight QR code library or API
        this.generateFromAPI();
    }
    
    generateFromAPI() {
        const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${this.size}x${this.size}&data=${encodeURIComponent(this.url)}&ecc=${this.errorCorrection}`;
        
        const img = document.createElement('img');
        img.src = apiUrl;
        img.alt = 'QR Code';
        img.width = this.size;
        img.height = this.size;
        img.style.borderRadius = '8px';
        img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
        
        this.container.innerHTML = '';
        this.container.appendChild(img);
    }
    
    // Alternative: Generate using canvas (no external API)
    generateCanvas() {
        const canvas = document.createElement('canvas');
        canvas.width = this.size + this.margin * 2;
        canvas.height = this.size + this.margin * 2;
        const ctx = canvas.getContext('2d');
        
        // Simple QR code generation would require a library
        // For now, fall back to API method
        this.generateFromAPI();
    }
}

// Initialize QR codes on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.qr-code-container').forEach(container => {
        const url = container.dataset.url || window.location.href;
        new QRCodeGenerator(container, url);
    });
});

// Export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = QRCodeGenerator;
}
