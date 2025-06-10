import { uploadConfig } from '../config/uploadConfig.js';
import { discreteModal } from './DiscreteModal.js';

export class UploadDropdown {
    constructor() {
        this.init();
    }

    init() {
        this.createUploadButton();
        this.setupEventListeners();
    }

    createUploadButton() {
        const uploadDropdown = document.createElement('div');
        uploadDropdown.className = 'upload-dropdown';
        uploadDropdown.innerHTML = `
            <button class="control-btn" title="Upload Files">
                <i class="bi bi-upload"></i>
            </button>
            <div class="upload-dropdown-content">
                <a class="upload-dropdown-item" data-type="csv">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Upload CSV
                </a>
                <a class="upload-dropdown-item" data-type="geojson">
                    <i class="bi bi-geo-alt"></i>
                    Upload GeoJSON
                </a>
            </div>
        `;

        // Insert after the points export button
        const pointsExportBtn = document.getElementById('exportPoints');
        if (pointsExportBtn && pointsExportBtn.parentNode) {
            pointsExportBtn.parentNode.insertBefore(uploadDropdown, pointsExportBtn.nextSibling);
        }
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            const dropdown = e.target.closest('.upload-dropdown');
            const allDropdowns = document.querySelectorAll('.upload-dropdown');
            
            allDropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });

            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        });

        document.querySelectorAll('.upload-dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const type = e.currentTarget.dataset.type;
                const url = type === 'csv' ? 
                    uploadConfig.csvUploadUrl : 
                    uploadConfig.geojsonUploadUrl;

                discreteModal.open();
                discreteModal.setContent(`
                    <iframe 
                        src="${url}" 
                        style="width:100%; height:100%; border:none;"
                    ></iframe>
                `);
            });
        });
    }
}