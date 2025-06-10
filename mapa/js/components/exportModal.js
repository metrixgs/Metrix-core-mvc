import { Modal } from '../modal.js';  // ✅ Correct relative path from js/components to js/


export function openExportDialog({ type = 'geojson', onExport }) {
    const modal = new Modal({
        title: `Exportar ${type === 'csv' ? 'CSV' : 'Polígono'}`,
        content: `
            <div>
                <label>Nombre del archivo:</label>
                <input type="text" id="export-filename" placeholder="ej: datos_exportados.${type}" />

                <label style="margin-top:10px;">Cliente:</label>
                <input type="text" id="export-customer" placeholder="Nombre del cliente" />

                <div style="margin-top: 20px; text-align: right;">
                    <button id="confirm-export">Exportar</button>
                </div>
            </div>
        `
    });

    modal.open();

    document.getElementById('confirm-export').addEventListener('click', () => {
        const filename = document.getElementById('export-filename').value.trim();
        const customer = document.getElementById('export-customer').value.trim();
        if (!filename) {
            alert('Debe ingresar un nombre de archivo.');
            return;
        }

        modal.close();
        onExport({ filename, customer });
    });
}
