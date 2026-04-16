<div x-data="pdfSigner()">
    <div class="row">
        <div class="col-md-9" wire:ignore>
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

                    {{-- ── Cabecera: conteo de firmas ── --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold small text-uppercase text-muted">
                            Firmas: <span x-text="signatureCount"></span> / {{ $signatory->required_signatures }}
                        </span>
                        <button x-on:click="deleteAll()" class="btn btn-tool text-danger" title="Eliminar todo"
                            :disabled="signatureCount == 0">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <button x-on:click="addSignature()" class="btn btn-outline-primary btn-sm w-100 mb-1"
                        :disabled="signatureCount >= REQUIRED_SIGNATURES || !signatureLoaded">
                        <i class="fas fa-signature mr-1"></i>
                        <span x-show="signatureLoaded">Agregar firma</span>
                        <span x-show="!signatureLoaded"><i class="fas fa-spinner fa-spin mr-1"></i> Cargando...</span>
                    </button>

                    {{-- ── Eliminar selección ── --}}
                    <template x-if="hasSelection">
                        <div>
                            <hr class="my-2">
                            <button x-on:click="deleteSelected()" class="btn btn-outline-danger btn-sm w-100 mb-2">
                                <i class="fas fa-trash mr-1"></i> Eliminar selección
                            </button>
                        </div>
                    </template>

                    {{-- ── Progreso visual ── --}}
                    <div class="mt-2">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                :style="`width: ${Math.min((signatureCount / REQUIRED_SIGNATURES) * 100, 100)}%`">
                            </div>
                        </div>
                        <p class="small text-muted mt-1 mb-0"
                            x-text="signatureCount >= REQUIRED_SIGNATURES
                                ? 'Firmas completas. Puedes guardar.'
                                : `Faltan ${REQUIRED_SIGNATURES - signatureCount} firma(s) por colocar.`">
                        </p>
                    </div>

                    @if ($message = $signatory->signatureRequest->message)
                        <div class="mt-2">
                            <span class="font-weight-bold small text-uppercase text-muted">
                                Mensaje
                            </span>
                            <div>
                                <small class="text-muted">
                                    {{ $message }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <button class="btn btn-outline-success btn-sm btn-block mb-2" x-on:click="savePDF()"
                        :disabled="signatureCount < REQUIRED_SIGNATURES || saving">
                        <span x-show="!saving"><i class="fas fa-file-signature mr-1"></i> Firmar documento</span>
                        <span x-show="saving"><i class="fas fa-spinner fa-spin mr-1"></i> Procesando...</span>
                    </button>

                    <button class="btn btn-outline-danger btn-sm btn-block mb-1" wire:click='reject'
                        wire:swal-confirm="¿Estás seguro de que deseas rechazar la firma de este documento?"
                        :disabled="saving">
                        <i class="fas fa-times mr-1"></i> Rechazar firma
                    </button>

                    <a class="btn btn-outline-secondary btn-sm btn-block"
                        href="{{ route('files.signatures.show', [$signatory->signatureRequest->userFile->id, $signatory->signatureRequest->id]) }}">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

    <script>
        window.pdfProcessState = {
            isBusy: false,
        };

        window.addEventListener("beforeunload", function(event) {
            if (window.pdfProcessState?.isBusy) {
                event.preventDefault();
                event.returnValue = "";
            }
        });
    </script>

    <script type="module">
        import * as pdfjsLib from 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs';

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.worker.min.mjs';

        const FILE_URL = @json($signatory->signatureRequest->userFile->getUrl());
        const SIGNATURE_URL = @json(auth()->user()->getSignatureUrlRamdon());
        const REQUIRED_SIGNATURES = @json($signatory->required_signatures);

        const SCALE_STEP = 0.1;
        const SCALE_MIN = 0.5;
        const SCALE_MAX = 4.0;
        const SCALE_DEF = 1;

        // Propiedades personalizadas de Fabric que deben persistir al serializar
        const FABRIC_CUSTOM_PROPS = ['signatureImage'];

        let _pdf = null;
        let _fabricCanvas = null;
        let _signatureImg = null; // fabric.Image lista para clonar

        // pageNum (1-based) → array de objetos serializados
        const _pageObjects = new Map();

        // ─── Helpers ────────────────────────────────────────────────────────────────

        function savePageObjects(pageNum) {
            _pageObjects.set(
                pageNum,
                _fabricCanvas.getObjects().map(o => o.toObject(FABRIC_CUSTOM_PROPS))
            );
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

        /**
         * Para cada página con objetos, renderiza SÓLO las firmas
         * en un canvas offscreen a alta resolución y devuelve los bytes PNG.
         */
        const OVERLAY_SCALE = 2;

        async function renderSignaturesForPage(pageNum, objects) {
            const pdfJsPage = await _pdf.getPage(pageNum);
            const viewport = pdfJsPage.getViewport({
                scale: OVERLAY_SCALE
            });

            const offscreenEl = document.createElement('canvas');
            offscreenEl.width = viewport.width;
            offscreenEl.height = viewport.height;

            const fc = new fabric.StaticCanvas(offscreenEl, {
                width: viewport.width,
                height: viewport.height,
                backgroundColor: null,
                enableRetinaScaling: false,
            });
            fc.setZoom(OVERLAY_SCALE);

            await new Promise(resolve => {
                fabric.util.enlivenObjects(objects, revived => {
                    revived.forEach(o => fc.add(o));
                    fc.renderAll();
                    resolve();
                });
            });

            // Exportar a PNG transparente
            const dataUrl = fc.toDataURL({
                format: 'png',
                multiplier: 1
            });
            fc.dispose();

            const base64 = dataUrl.split(',')[1];
            return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
        }

        // ─── Alpine component ────────────────────────────────────────────────────────
        window.pdfSigner = () => ({
            currentPage: 1,
            totalPages: 0,
            scale: SCALE_DEF,
            rendering: false,
            saving: false,
            signatureCount: 0,
            hasSelection: false,
            signatureLoaded: false,
            REQUIRED_SIGNATURES,
            SCALE_MIN,
            SCALE_MAX,

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

                _fabricCanvas.on('selection:created', () => {
                    this.hasSelection = true;
                });
                _fabricCanvas.on('selection:updated', () => {
                    this.hasSelection = true;
                });
                _fabricCanvas.on('selection:cleared', () => {
                    this.hasSelection = false;
                });

                // Cargar imagen de firma
                fabric.Image.fromURL(SIGNATURE_URL, img => {
                    _signatureImg = img;
                    this.signatureLoaded = true;
                }, {
                    crossOrigin: 'anonymous'
                });

                _pdf = await pdfjsLib.getDocument(FILE_URL).promise;
                this.totalPages = _pdf.numPages;
                await this.render();
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

            // ── Herramientas de firma ────────────────────────────────────────────────
            addSignature() {
                if (!_fabricCanvas || !_signatureImg) return;
                if (this.signatureCount >= REQUIRED_SIGNATURES) return;

                // Clonar para poder añadir múltiples instancias
                _signatureImg.clone(clone => {
                    // Escalar para que no supere 200px de ancho en espacio escala-1
                    const maxW = 200;
                    if (clone.width > maxW) {
                        clone.scaleToWidth(maxW);
                    }

                    clone.set({
                        left: (_fabricCanvas.width / this.scale) / 2 - (clone.getScaledWidth() / 2),
                        top: (_fabricCanvas.height / this.scale) / 2 - (clone.getScaledHeight() /
                            2),
                        hasControls: true,
                        hasBorders: true,
                    });

                    _fabricCanvas.add(clone);
                    _fabricCanvas.setActiveObject(clone);
                    _fabricCanvas.renderAll();
                    this.signatureCount++;
                    this.hasSelection = true;
                });
            },

            deleteSelected() {
                if (!_fabricCanvas) return;
                const active = _fabricCanvas.getActiveObjects();
                if (!active.length) return;
                this.signatureCount -= active.length;
                active.forEach(o => _fabricCanvas.remove(o));
                _fabricCanvas.discardActiveObject();
                _fabricCanvas.renderAll();
                this.hasSelection = false;
            },

            deleteAll() {
                if (!_fabricCanvas) return;
                _fabricCanvas.clear();
                _pageObjects.clear();
                this.signatureCount = 0;
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
                window.pdfProcessState.isBusy = true;

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

                        // Renderizar SÓLO las firmas (fondo transparente)
                        const pngBytes = await renderSignaturesForPage(pageNum, objects);
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

                    const file = new File([pdfBytes], 'documento_firmado.pdf', {
                        type: 'application/pdf',
                    });

                    this.$wire.upload(
                        'file',
                        file,
                        () => {
                            this.$wire.call('sign');
                            this.saving = false;
                            window.pdfProcessState.isBusy = false;
                        },
                        (err) => {
                            console.error('Error al subir el archivo:', err);
                            alert('No se pudo subir el archivo al servidor.');
                            this.saving = false;
                            window.pdfProcessState.isBusy = false;
                        },
                        (e) => {
                            console.log('Progreso:', e.detail?.progress ?? '—');
                        },
                    );

                } catch (err) {
                    console.error('Error al generar el PDF firmado:', err);
                    alert('Ocurrió un error al generar el PDF. Revisa la consola para más detalles.');
                    this.saving = false;
                    window.pdfProcessState.isBusy = false;
                }
            },
        });
    </script>
@endpush
