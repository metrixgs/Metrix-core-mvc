 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Reporte</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
        }

        .reporte-box {
            max-width: 420px;
            margin: 50px auto;
            padding: 24px;
            background-color: white;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .reporte-box h2 {
            font-size: 20px;
            margin-bottom: 16px;
            font-weight: bold;
            color: #111;
        }

        .reporte-box p {
            font-size: 15px;
            color: #444;
            margin: 12px 0;
        }

        .bold {
            font-weight: bold;
            color: #222;
        }

        .buttons {
            margin-top: 24px;
        }

        .btn {
            padding: 10px 16px;
            margin: 8px 4px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-back {
            background-color: #ccc;
            color: #000;
        }

        .btn-back:hover {
            background-color: #bbb;
        }

        .btn-download-pdf {
            background-color: #007bff;
            color: #fff;
        }

        .btn-download-pdf:hover {
            background-color: #0056b3;
        }

        .btn-download-png {
            background-color: #28a745;
            color: #fff;
        }

        .btn-download-png:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<div class="reporte-box" id="reporte">
    <h2><span class="bold">Reporte:</span> <?= esc($ticket['identificador'] ?? 'N/A') ?></h2>

    <p><span class="bold">Fecha:</span><br>
        <?= date('d de F del Y, Hora: h:i a', strtotime($ticket['fecha_creacion'])) ?></p>

    <p><span class="bold">Dirección:</span><br>
        <?= esc($ticket['direccion_completa'] ?? 'No especificada') ?></p>

    <p><span class="bold">Asunto:</span><br>
        <?= esc($ticket['titulo'] ?? 'Sin asunto') ?></p>

    <p><span class="bold">Nombre del operador:</span><br>
        <?= esc($ticket['nombre_usuario'] ?? 'Desconocido') ?></p>

    <p><span class="bold">Nombre del ciudadano(a):</span><br>
        <?= esc($ticket['nombre_cliente'] ?? 'Desconocido') ?></p>

    <p><span class="bold">Número de reporte:</span><br>
        #<?= esc($ticket['id']) ?></p>

    <a href="<?= base_url('tickets/listadosQr') ?>"> <button class="btn btn-back">← Regresar</button>
        </a>
        <button class="btn btn-download-pdf" onclick="descargarPDF()">📄 Descargar PDF</button>
        <button class="btn btn-download-png" onclick="descargarPNG()">🖼️ Descargar PNG</button>
    </div>
</div>

<!-- jsPDF + html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    async function descargarPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const element = document.getElementById('reporte');

        await html2canvas(element).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const imgProps = doc.getImageProperties(imgData);
            const pdfWidth = doc.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            doc.save('reporte-<?= esc($ticket['identificador']) ?>.pdf');
        });
    }

    function descargarPNG() {
        const element = document.getElementById('reporte');
        html2canvas(element).then(canvas => {
            const link = document.createElement('a');
            link.download = 'reporte-<?= esc($ticket['identificador']) ?>.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>

</body>
</html>
