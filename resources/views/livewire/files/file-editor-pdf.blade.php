<div x-data="pdfViewer()">
    <div class="row">
        <div class="col-md-9 col-sm-8" wire:ignore>
            <div class="card">
                <div class="card-header py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="btn-group mr-2" role="group">
                            <button x-on:click="prevPage()" :disabled="currentPage <= 1 || rendering"
                                class="btn btn-sm btn-outline-secondary" title="Página anterior">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button x-on:click="nextPage()" :disabled="currentPage >= totalPages || rendering"
                                class="btn btn-sm btn-outline-secondary" title="Página siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <span class="small text-muted">
                            Página <strong x-text="currentPage"></strong> de <strong x-text="totalPages"></strong>
                        </span>
                    </div>

                    <div class="d-flex align-items-center ml-auto">
                        <span class="small text-muted" x-text="`${Math.round(scale * 100)}%`"></span>
                        <div class="btn-group ml-2" role="group">
                            <button x-on:click="zoomOut()" :disabled="scale <= SCALE_MIN || rendering"
                                class="btn btn-sm btn-outline-secondary" title="Reducir">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button x-on:click="zoomReset()" :disabled="rendering"
                                class="btn btn-sm btn-outline-secondary" title="Restablecer zoom">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            <button x-on:click="zoomIn()" :disabled="scale >= SCALE_MAX || rendering"
                                class="btn btn-sm btn-outline-secondary" title="Ampliar">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body overflow-auto bg-light" style="max-height: 74vh;">
                    <div x-ref="pdfContainer" class="shadow"
                        style="position: relative; display: table; margin: 0 auto;">
                        <canvas x-ref="viewer" class="d-block"></canvas>
                        <canvas x-ref="editor"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body py-2">

                    {{-- ── Cabecera: conteo + acciones globales ── --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold small text-uppercase text-muted">
                            Objetos: <span x-text="objectCount"></span>
                        </span>
                        <button x-on:click="deleteAll()" class="btn btn-tool text-danger" title="Eliminar todo"
                            :disabled="objectCount == 0">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <button x-on:click="addTextBox()" class="btn btn-outline-primary btn-sm w-100 mb-1">
                        <i class="fas fa-font mr-1"></i> Agregar texto
                    </button>

                    {{-- ── Panel de estilo (solo si hay selección) ── --}}
                    <template x-if="hasSelection">
                        <div>
                            <hr class="my-2">

                            <button x-on:click="deleteSelected()" class="btn btn-outline-danger btn-sm w-100 mb-2">
                                <i class="fas fa-trash mr-1"></i> Eliminar selección
                            </button>

                            <div class="font-weight-bold small text-uppercase text-muted mb-1">Tipografía</div>

                            <div class="input-group input-group-sm mb-2">
                                <select class="custom-select" x-model="style.fontFamily"
                                    x-on:change="applyStyle('fontFamily', style.fontFamily)">
                                    <template x-for="f in fontFamilies" :key="f">
                                        <option :value="f" x-text="f"></option>
                                    </template>
                                </select>
                                <select class="custom-select" style="max-width:72px;" x-model.number="style.fontSize"
                                    x-on:change="applyStyle('fontSize', style.fontSize)">
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="14">14</option>
                                    <option value="16">16</option>
                                    <option value="18">18</option>
                                    <option value="20">20</option>
                                    <option value="24">24</option>
                                    <option value="28">28</option>
                                    <option value="32">32</option>
                                    <option value="36">36</option>
                                    <option value="42">42</option>
                                    <option value="48">48</option>
                                    <option value="60">60</option>
                                    <option value="72">72</option>
                                    <option value="96">96</option>
                                </select>
                            </div>

                            <div class="btn-group btn-group-sm w-100 mb-1" role="group">
                                <button type="button"
                                    :class="style.fontWeight === 'bold' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="toggleBold()" title="Negrita">
                                    <i class="fas fa-fw fa-bold"></i>
                                </button>
                                <button type="button"
                                    :class="style.fontStyle === 'italic' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="toggleItalic()" title="Cursiva">
                                    <i class="fas fa-fw fa-italic"></i>
                                </button>
                                <button type="button"
                                    :class="style.underline ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="toggleUnderline()" title="Subrayado">
                                    <i class="fas fa-fw fa-underline"></i>
                                </button>
                                <button type="button"
                                    :class="style.linethrough ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="toggleLinethrough()" title="Tachado">
                                    <i class="fas fa-fw fa-strikethrough"></i>
                                </button>
                            </div>

                            <div class="btn-group btn-group-sm w-100 mb-2" role="group">
                                <button type="button"
                                    :class="style.textAlign === 'left' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="applyStyle('textAlign', 'left'); style.textAlign = 'left'"
                                    title="Izquierda">
                                    <i class="fas fa-fw fa-align-left"></i>
                                </button>
                                <button type="button"
                                    :class="style.textAlign === 'center' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="applyStyle('textAlign', 'center'); style.textAlign = 'center'"
                                    title="Centro">
                                    <i class="fas fa-fw fa-align-center"></i>
                                </button>
                                <button type="button"
                                    :class="style.textAlign === 'right' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="applyStyle('textAlign', 'right'); style.textAlign = 'right'"
                                    title="Derecha">
                                    <i class="fas fa-fw fa-align-right"></i>
                                </button>
                                <button type="button"
                                    :class="style.textAlign === 'justify' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                                    x-on:click="applyStyle('textAlign', 'justify'); style.textAlign = 'justify'"
                                    title="Justificado">
                                    <i class="fas fa-fw fa-align-justify"></i>
                                </button>
                            </div>

                            <div class="font-weight-bold small text-uppercase text-muted mb-2">Color</div>

                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-fw fa-font"></i>
                                                </span>
                                            </div>
                                            <input type="color" class="form-control p-0 cursor-pointer"
                                                x-model="style.fill" x-on:input="applyStyle('fill', style.fill)">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-fw fa-fill-drip"></i>
                                                </span>
                                            </div>
                                            <input type="color" class="form-control p-0 cursor-pointer"
                                                x-model="style.backgroundColor" x-on:input="applyBgColor()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-fw fa-circle-half-stroke"></i>
                                        </span>
                                    </div>
                                    <div class="form-control d-flex align-items-center p-0 px-2">
                                        <input type="range" class="custom-range flex-fill" min="0"
                                            max="1" step="0.05" x-model.number="style.bgOpacity"
                                            x-on:input="applyBgColor()">
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text small"
                                            style="min-width:3rem; justify-content:center;"
                                            x-text="Math.round(style.bgOpacity * 100) + '%'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

                <div class="card-footer">
                    <button class="btn btn-outline-success btn-sm btn-block" x-on:click="savePDF()"
                        :disabled="objectCount == 0 || saving">
                        <span x-show="!saving"><i class="fas fa-download mr-1"></i> Guardar PDF</span>
                        <span x-show="saving"><i class="fas fa-spinner fa-spin mr-1"></i> Generando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

    <script type="module">
        import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs';

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.worker.min.mjs';

        const FILE_URL = @json($userFile->getUrl());
        const SCALE_STEP = 0.1;
        const SCALE_MIN = 0.5;
        const SCALE_MAX = 4.0;
        const SCALE_DEF = 1;

        // Propiedades personalizadas de Fabric que deben persistir al serializar
        const FABRIC_CUSTOM_PROPS = [
            'fontFamily', 'fontSize', 'fill', 'backgroundColor',
            'fontWeight', 'fontStyle', 'underline', 'linethrough', 'textAlign',
        ];

        let _pdf = null;
        let _fabricCanvas = null;

        const _pageObjects = new Map(); // pageNum (1-based) → array de objetos serializados

        // ─── Helpers ────────────────────────────────────────────────────────────────

        function savePageObjects(pageNum) {
            _pageObjects.set(pageNum, _fabricCanvas.getObjects().map(o => o.toObject(FABRIC_CUSTOM_PROPS)));
        }

        function loadPageObjects(pageNum) {
            return new Promise(resolve => {
                _fabricCanvas.clear();
                const saved = _pageObjects.get(pageNum) || [];
                if (!saved.length) {
                    _fabricCanvas.renderAll();
                    return resolve();
                }
                fabric.util.enlivenObjects(saved, objects => {
                    objects.forEach(o => _fabricCanvas.add(o));
                    _fabricCanvas.renderAll();
                    resolve();
                });
            });
        }

        function syncFabricToViewport(w, h, scale) {
            _fabricCanvas.setDimensions({
                width: w,
                height: h
            });
            _fabricCanvas.setZoom(scale);
        }

        function hexToRgba(hex, opacity) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgba(${r},${g},${b},${opacity})`;
        }

        function rgbaToHex(color) {
            if (!color) return '#ffff00';
            if (color.startsWith('#')) return color.slice(0, 7);
            const m = color.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
            if (!m) return '#ffff00';
            return '#' + [m[1], m[2], m[3]].map(n => (+n).toString(16).padStart(2, '0')).join('');
        }

        function rgbaToOpacity(color) {
            if (!color || color.startsWith('#')) return 0.6;
            const m = color.match(/rgba\(\d+,\s*\d+,\s*\d+,\s*([\d.]+)\)/);
            return m ? parseFloat(m[1]) : 1;
        }

        /**
         * Resolución de renderizado del overlay.
         * A escala 1 pdfjs produce ~72 dpi. Con OVERLAY_SCALE = 4 obtenemos ~288 dpi,
         * suficiente para texto nítido en cualquier impresora o pantalla retina.
         * Subir más de 4 aumenta mucho el tamaño del archivo sin ganancia visible.
         */
        const OVERLAY_SCALE = 2;

        /**
         * Renderiza los objetos Fabric de una página sobre un canvas offscreen
         * a alta resolución y devuelve un PNG como Uint8Array.
         *
         * Estrategia:
         *   - Se obtiene el viewport de pdfjs a OVERLAY_SCALE para conocer
         *     el tamaño en píxeles del canvas offscreen.
         *   - Se aplica el mismo zoom a Fabric para que las coordenadas guardadas
         *     (que están en espacio de escala 1) se escalen proporcionalmente.
         *   - pdf-lib incrusta el PNG y lo comprime al tamaño en puntos de la página,
         *     conservando toda la resolución extra como densidad de píxeles.
         *
         * @param {number}   pageNum - Número de página (1-based)
         * @param {object[]} objects - Array de objetos serializados de Fabric
         * @returns {Promise<Uint8Array>}
         */
        async function renderOverlayForPage(pageNum, objects) {
            // 1. Viewport a alta resolución para fijar el tamaño del canvas offscreen
            const pdfJsPage = await _pdf.getPage(pageNum);
            const viewport = pdfJsPage.getViewport({
                scale: OVERLAY_SCALE
            });

            // 2. Canvas offscreen
            const offscreenEl = document.createElement('canvas');
            offscreenEl.width = viewport.width;
            offscreenEl.height = viewport.height;

            // 3. StaticCanvas de Fabric con zoom = OVERLAY_SCALE
            //    Las coordenadas de los objetos son en espacio escala-1,
            //    el zoom las amplía al mismo factor que el viewport de pdfjs.
            const fc = new fabric.StaticCanvas(offscreenEl, {
                width: viewport.width,
                height: viewport.height,
                backgroundColor: null,
                enableRetinaScaling: false,
            });
            fc.setZoom(OVERLAY_SCALE);

            // 4. Revivir y añadir los objetos
            await new Promise(resolve => {
                fabric.util.enlivenObjects(objects, revived => {
                    revived.forEach(o => fc.add(o));
                    fc.renderAll();
                    resolve();
                });
            });

            // 5. Exportar a PNG → Uint8Array (multiplier=1: el canvas ya está a alta res)
            const dataUrl = fc.toDataURL({
                format: 'png',
                multiplier: 1
            });
            fc.dispose();

            const base64 = dataUrl.split(',')[1];
            return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
        }

        // ─── Alpine component ────────────────────────────────────────────────────────
        window.pdfViewer = () => ({
            currentPage: 1,
            totalPages: 0,
            scale: SCALE_DEF,
            rendering: false,
            saving: false,
            objectCount: 0,
            hasSelection: false,
            SCALE_MIN,
            SCALE_MAX,

            fontFamilies: ['Arial', 'Georgia', 'Courier New', 'Times New Roman', 'Verdana', 'Helvetica'],

            style: {
                fontFamily: 'Arial',
                fontSize: 14,
                fill: '#1a1a1a',
                backgroundColor: '#ffff00',
                bgOpacity: 0.6,
                fontWeight: 'normal',
                fontStyle: 'normal',
                underline: false,
                linethrough: false,
                textAlign: 'left',
            },

            async init() {
                _fabricCanvas = new fabric.Canvas(this.$refs.editor, {
                    selection: true,
                    preserveObjectStacking: true,
                });

                const wrapper = _fabricCanvas.wrapperEl;
                Object.assign(wrapper.style, {
                    position: 'absolute',
                    top: '0',
                    left: '0'
                });

                _fabricCanvas.on('selection:created', e => this._syncStyle(e.selected[0]));
                _fabricCanvas.on('selection:updated', e => this._syncStyle(e.selected[0]));
                _fabricCanvas.on('selection:cleared', () => {
                    this.hasSelection = false;
                });
                _fabricCanvas.on('object:modified', e => this._syncStyle(e.target));

                _pdf = await pdfjsLib.getDocument(FILE_URL).promise;
                this.totalPages = _pdf.numPages;
                await this.render();
            },

            _syncStyle(obj) {
                if (!obj || obj.type !== 'i-text') {
                    this.hasSelection = false;
                    return;
                }
                this.hasSelection = true;
                this.style.fontFamily = obj.fontFamily || 'Arial';
                this.style.fontSize = obj.fontSize || 14;
                this.style.fill = obj.fill || '#1a1a1a';
                this.style.fontWeight = obj.fontWeight || 'normal';
                this.style.fontStyle = obj.fontStyle || 'normal';
                this.style.underline = !!obj.underline;
                this.style.linethrough = !!obj.linethrough;
                this.style.textAlign = obj.textAlign || 'left';
                const bg = obj.backgroundColor || 'rgba(255,255,0,0.6)';
                this.style.backgroundColor = rgbaToHex(bg);
                this.style.bgOpacity = rgbaToOpacity(bg);
            },

            applyStyle(prop, value) {
                const obj = _fabricCanvas.getActiveObject();
                if (!obj || obj.type !== 'i-text') return;
                obj.set(prop, value);
                _fabricCanvas.renderAll();
            },

            toggleBold() {
                this.style.fontWeight = this.style.fontWeight === 'bold' ? 'normal' : 'bold';
                this.applyStyle('fontWeight', this.style.fontWeight);
            },
            toggleItalic() {
                this.style.fontStyle = this.style.fontStyle === 'italic' ? 'normal' : 'italic';
                this.applyStyle('fontStyle', this.style.fontStyle);
            },
            toggleUnderline() {
                this.style.underline = !this.style.underline;
                this.applyStyle('underline', this.style.underline);
            },
            toggleLinethrough() {
                this.style.linethrough = !this.style.linethrough;
                this.applyStyle('linethrough', this.style.linethrough);
            },

            applyBgColor() {
                const rgba = hexToRgba(this.style.backgroundColor, this.style.bgOpacity);
                this.applyStyle('backgroundColor', rgba);
            },

            // ── Renderizado ──────────────────────────────────────────────────────────
            async render() {
                if (this.rendering) return;
                this.rendering = true;
                try {
                    const page = await _pdf.getPage(this.currentPage);
                    const viewport = page.getViewport({
                        scale: this.scale
                    });
                    const cv = this.$refs.viewer;
                    cv.height = viewport.height;
                    cv.width = viewport.width;
                    await page.render({
                        canvasContext: cv.getContext('2d'),
                        viewport
                    }).promise;
                    syncFabricToViewport(viewport.width, viewport.height, this.scale);
                    _fabricCanvas.renderAll();
                } finally {
                    this.rendering = false;
                }
            },

            // ── Navegación ───────────────────────────────────────────────────────────
            async prevPage() {
                if (this.currentPage <= 1 || this.rendering) return;
                savePageObjects(this.currentPage);
                this.currentPage--;
                await this.render();
                await loadPageObjects(this.currentPage);
            },
            async nextPage() {
                if (this.currentPage >= this.totalPages || this.rendering) return;
                savePageObjects(this.currentPage);
                this.currentPage++;
                await this.render();
                await loadPageObjects(this.currentPage);
            },

            // ── Zoom ─────────────────────────────────────────────────────────────────
            async zoomIn() {
                if (this.scale >= SCALE_MAX || this.rendering) return;
                savePageObjects(this.currentPage);
                this.scale = +(Math.min(SCALE_MAX, this.scale + SCALE_STEP).toFixed(1));
                await this.render();
                await loadPageObjects(this.currentPage);
            },
            async zoomOut() {
                if (this.scale <= SCALE_MIN || this.rendering) return;
                savePageObjects(this.currentPage);
                this.scale = +(Math.max(SCALE_MIN, this.scale - SCALE_STEP).toFixed(1));
                await this.render();
                await loadPageObjects(this.currentPage);
            },
            async zoomReset() {
                if (this.rendering) return;
                savePageObjects(this.currentPage);
                this.scale = SCALE_DEF;
                await this.render();
                await loadPageObjects(this.currentPage);
            },

            // ── Herramientas de edición ──────────────────────────────────────────────
            addTextBox() {
                if (!_fabricCanvas) return;
                const baseX = (_fabricCanvas.width / this.scale) / 2 - 80;
                const baseY = (_fabricCanvas.height / this.scale) / 4;
                const itext = new fabric.IText('Texto aquí', {
                    left: baseX,
                    top: baseY,
                    fontSize: 14,
                    fill: '#1a1a1a',
                    fontFamily: 'Arial',
                    backgroundColor: 'rgba(255,255,0,0.6)',
                });
                _fabricCanvas.add(itext);
                _fabricCanvas.setActiveObject(itext);
                itext.selectAll();
                _fabricCanvas.renderAll();
                this.objectCount++;
                this._syncStyle(itext);
            },

            deleteSelected() {
                if (!_fabricCanvas) return;
                const active = _fabricCanvas.getActiveObjects();
                if (!active.length) return;
                this.objectCount -= active.length;
                active.forEach(o => _fabricCanvas.remove(o));
                _fabricCanvas.discardActiveObject();
                _fabricCanvas.renderAll();
                this.hasSelection = false;
            },

            deleteAll() {
                if (!_fabricCanvas) return;
                _fabricCanvas.clear();
                this.objectCount = 0;
                this.hasSelection = false;
            },

            // ── Guardar PDF ──────────────────────────────────────────────────────────
            async savePDF() {
                if (this.saving) return;

                savePageObjects(this.currentPage);

                const pagesWithObjects = [..._pageObjects.entries()]
                    .filter(([, objs]) => objs && objs.length > 0);

                if (!pagesWithObjects.length) return;

                this.saving = true;
                try {
                    const originalBytes = await fetch(FILE_URL).then(r => r.arrayBuffer());

                    const {
                        PDFDocument
                    } = PDFLib;
                    const pdfDoc = await PDFDocument.load(originalBytes);
                    const pdfPages = pdfDoc.getPages();

                    for (const [pageNum, objects] of pagesWithObjects) {
                        const pdfPage = pdfPages[pageNum - 1];
                        if (!pdfPage) continue;

                        const pngBytes = await renderOverlayForPage(pageNum, objects);
                        const pngImage = await pdfDoc.embedPng(pngBytes);
                        const {
                            width,
                            height
                        } = pdfPage.getSize();
                        pdfPage.drawImage(pngImage, {
                            x: 0,
                            y: 0,
                            width,
                            height
                        });
                    }

                    const pdfBytes = await pdfDoc.save();

                    // ── Subir a Livewire ─────────────────────────────────────────────────
                    const file = new File([pdfBytes], 'documento_editado.pdf', {
                        type: 'application/pdf',
                    });

                    this.$wire.upload(
                        'file', // propiedad pública en el componente
                        file,
                        () => this.$wire.call('save'), // éxito: llama al método save()
                        (err) => {
                            console.error('Error al subir el archivo:', err);
                            alert('No se pudo subir el archivo al servidor.');
                        },
                        (e) => {
                            // progreso opcional: e.detail.progress (0-100)
                            console.log('Progreso:', e.detail?.progress ?? '—');
                        },
                    );

                } catch (err) {
                    console.error('Error al generar el PDF:', err);
                    alert('Ocurrió un error al generar el PDF. Revisa la consola para más detalles.');
                    this.saving = false; // solo se resetea aquí; en el camino feliz lo hace save()
                }
            },
        });
    </script>
@endpush
