export default function filamentYandexMapField({
                                                   apiKey,
                                                   suggestApiKey,
                                                   lang,
                                                   zoom,
                                                   center,
                                                   geoObjectProperties,
                                                   geoObjectOptions,
                                                   isDisabled,
                                                   mode,
                                                   deleteBtnParameters,
                                                   drawBtnParameters,
                                                   editBtnParameters,
                                                   statePath,
                                                   setStateUsing,
                                                   getStateUsing,
                                                   mapEl,
                                                   state,
                                               }) {
    return {
        state,
        statePath,
        isDisabled,
        zoom,
        mode,

        /**
         * Entrypoint
         */
        init: function () {
            this.loadScript();
        },

        /**
         * Load JS-script
         *
         * If there are multiple components on page
         * then the script tag should be added on page only once.
         * When first component will be completely loaded
         * then other components can create map.
         *
         * So, in "if" condition we load script for the first component,
         * in "else" condition we create map for other components using
         * window.filamentYandexMapsAPILoaded global variable.
         */
        loadScript: function () {
            if (!document.getElementById("filament-yandex-map-js")) {
                const script = document.createElement('script');
                script.id = "filament-yandex-map-js";
                script.src = 'https://api-maps.yandex.ru/2.1/?lang=' + lang + '&apikey=' + apiKey + '&suggest_apikey=' + suggestApiKey;
                script.onload = () => {
                    this.createMap();
                };
                document.head.appendChild(script);
            } else {
                const waitForGlobal = function (key, callback) {
                    if (window[key]) {
                        callback();
                    } else {
                        setTimeout(function () {
                            waitForGlobal(key, callback);
                        }, 100);
                    }
                };

                waitForGlobal(
                    'filamentYandexMapsAPILoaded',
                    function () {
                        this.createMap();
                    }.bind(this)
                );
            }
        },

        /**
         * Always set window.filamentYandexMapsAPILoaded global variable to true
         * after JS-script loaded.
         *
         * And create map for current component:
         * - Initiate map with controls
         * - Initiate geo-object
         * - Setup $watch('state') to receive state from server
         */
        createMap: function () {
            window.filamentYandexMapsAPILoaded = true;

            ymaps.ready(() => {
                let inputSearch = new ymaps.control.SearchControl({
                    options: {
                        size: 'large',
                        provider: 'yandex#search'
                    }
                });

                const map = new ymaps.Map(mapEl, {
                    center: center,
                    controls: ['fullscreenControl', 'zoomControl', inputSearch],
                    zoom: this.zoom
                }, {yandexMapDisablePoiInteractivity: true});

                let geoObject = this.setupGeoObject(map);

                this.$watch('state', (e) => {
                    if (this.state === undefined || !geoObject) {
                        return;
                    }

                    function arrayEquals(a, b) {
                        return Array.isArray(a) &&
                            Array.isArray(b) &&
                            a.length === b.length &&
                            a.every((val, index) => {
                                if (Array.isArray(val) && Array.isArray(b[index])) {
                                    return arrayEquals(val, b[index]);
                                }

                                return val === b[index];
                            });
                    }

                    if (!arrayEquals(this.state, geoObject.geometry.getCoordinates())) {
                        geoObject.geometry.setCoordinates(this.getState());
                    }
                });
            });
        },

        /**
         * Get current formatted state.
         *
         * If geo-object is a placemark then empty state must be 'null'.
         * For other types of geo-objects empty state must be an empty array.
         */
        getState: function () {
            let emptyState = this.mode === 'placemark'
                ? null
                : [];

            return this.state === null
                ? emptyState
                : this.state;
        },

        /**
         * Update state after geo-object coordinates changed.
         *
         * If geo-object deleted, then state must be 'null' for server.
         */
        setState: function (state) {
            state = Array.isArray(state) && state.length
                ? state
                : null;

            this.state = state;

            setStateUsing(this.statePath, this.state);
        },

        /**
         * Setup geo-object.
         *
         * Geo-object can be one of the following types:
         * - placemark (marker)
         * - polyline (line)
         * - polygon
         *
         * When geo-object is created and exists:
         * - geo-object is added to map
         * - the map is scaled to the object
         * - geo-object controls are enabled (only if component is not disabled)
         * - geo-object events are enabled (only if component is not disabled)
         */
        setupGeoObject: function (map) {
            let geoObject = null,
                isEditing = true,
                isDrawing = true;

            switch (this.mode) {
                case 'placemark':
                    geoObject = new ymaps.Placemark(
                        this.getState(),
                        geoObjectProperties,
                        {
                            ...geoObjectOptions,
                            // 'draggable' option must be set to false for proper drawing.
                            ...{draggable: false},
                        }
                    );
                    isEditing = false;
                    break;
                case 'polyline':
                    geoObject = new ymaps.Polyline(
                        this.getState(),
                        geoObjectProperties,
                        geoObjectOptions
                    );
                    break;
                case 'polygon':
                    geoObject = new ymaps.Polygon(
                        this.getState(),
                        geoObjectProperties,
                        geoObjectOptions
                    );
                    break;
            }

            if (geoObject) {
                map.geoObjects.add(geoObject);

                this.zoomToGeoObject(map, geoObject);

                if (!this.isDisabled) {
                    this.enableGeoObjectControls(map, geoObject, isEditing, isDrawing);
                    this.enableGeoObjectEvents(geoObject);
                }
            }

            return geoObject;
        },

        /**
         * Zoom to geo-object using its bounds.
         */
        zoomToGeoObject: function (map, geoObject) {
            let bounds = geoObject.geometry.getBounds();

            if (bounds) {
                map.setBounds(bounds, {
                    zoomMargin: 100,
                    checkZoomRange: true,
                }).then(function () {
                    map.setZoom(this.zoom);
                }, function (err) {
                    console.log('Error while bounding: ' + err);
                }, this);
            }
        },

        /**
         * Enable map controls for editing, drawing and deleting geo-object.
         */
        enableGeoObjectControls: function (
            map,
            geoObject,
            isEditing = true,
            isDrawing = true
        ) {
            let deleteBtn = this.setupDeleteBtn(geoObject);
            let editBtn = this.setupEditBtn(isEditing, geoObject);
            let drawBtn = this.setupDrawBtn(isDrawing, geoObject);

            editBtn.events.add('deselect', () => {
                geoObject.editor.stopEditing();
                drawBtn.deselect();
            });
            drawBtn.events.add('select', () => {
                geoObject.editor.startDrawing();
                editBtn.select();
            });

            map.controls.add(editBtn);
            map.controls.add(drawBtn);
            map.controls.add(deleteBtn);
        },

        setupDeleteBtn: function (geoObject) {
            let btn = new ymaps.control.Button(deleteBtnParameters);

            btn.events.add('click', () => {
                geoObject.geometry.setCoordinates([]);

                // Little trick for placemark
                geoObject.options.set('visible', false);
                geoObject.options.set('visible', true);
            });

            return btn;
        },

        setupEditBtn: function (isEditing, geoObject) {
            let btn = new ymaps.control.Button({
                data: editBtnParameters['data'],
                options: {
                    ...editBtnParameters['options'],
                    ...{visible: isEditing}
                }
            });

            btn.events.add('select', () => {
                geoObject.editor.startEditing();
            });

            return btn;
        },

        setupDrawBtn: function (isDrawing, geoObject) {
            let btn = new ymaps.control.Button({
                data: drawBtnParameters['data'],
                options: {
                    ...drawBtnParameters['options'],
                    ...{visible: isDrawing}
                }
            });

            btn.events.add('deselect', () => {
                geoObject.editor.stopDrawing();
            });

            return btn;
        },

        /**
         * Enabling geo-object events:
         * - geometrychange - when geo-object geometry is changed, it is needed to update state
         */
        enableGeoObjectEvents: function (geoObject) {
            geoObject.events.add('geometrychange', (e) => {
                this.setState(geoObject.geometry.getCoordinates());
            });
        }
    }
}
