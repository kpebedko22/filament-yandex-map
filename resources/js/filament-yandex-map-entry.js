import MultiPolygon from './multi-polygon.js';

export default function filamentYandexMapEntry({
                                                   apiKey,
                                                   suggestApiKey,
                                                   state,
                                                   lang,
                                                   zoom,
                                                   center,
                                                   geoObjectProperties,
                                                   geoObjectOptions,
                                                   mode,
                                                   layers = null,
                                                   mapEl,
                                               }) {
    return {
        state,
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
            if (!document.getElementById("filament-yandex-map-entry-js")) {
                const script = document.createElement('script');
                script.id = "filament-yandex-map-entry-js";
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
         * - Initiate geo-object (or layers, if there are any)
         * - Setup $watch('state') to receive state from server
         */
        createMap: function () {
            window.filamentYandexMapsAPILoaded = true;

            ymaps.ready(() => {
                const map = new ymaps.Map(mapEl, {
                    center: center,
                    controls: ['fullscreenControl', 'zoomControl'],
                    zoom: this.zoom
                }, {autoFitToViewport: 'always', yandexMapDisablePoiInteractivity: true});

                if (layers !== null) {
                    this.setupLayers(map);

                    return;
                }

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
         * Setup geo-object.
         *
         * Geo-object can be one of the following types:
         * - placemark (marker)
         * - polyline (line)
         * - polygon
         * - multipolygon (collection of polygons)
         *
         * When geo-object is created and exists:
         * - geo-object is added to map
         * - the map is scaled to the object
         * - geo-object controls are enabled (only if component is not disabled)
         * - geo-object events are enabled (only if component is not disabled)
         */
        setupGeoObject: function (map) {
            const geoObject = this.createGeoObject(this.mode, this.getState(), geoObjectProperties, geoObjectOptions);

            if (geoObject) {
                // Multipolygon is not a ymaps object, its collection of polygons is added to the map.
                map.geoObjects.add(geoObject.collection ?? geoObject);

                this.zoomToGeoObject(map, geoObject);
            }

            return geoObject;
        },

        /**
         * Create geo-object of the given mode, null if the mode is unknown.
         */
        createGeoObject: function (mode, state, properties, options) {
            switch (mode) {
                case 'placemark':
                    return new ymaps.Placemark(
                        state,
                        properties,
                        {
                            ...options,
                            // 'draggable' option must be set to false for proper drawing.
                            ...{draggable: false},
                        }
                    );
                case 'polyline':
                    return new ymaps.Polyline(state, properties, options);
                case 'polygon':
                    return new ymaps.Polygon(state, properties, options);
                case 'multipolygon':
                    return new MultiPolygon(state, properties, options);
            }

            return null;
        },

        /**
         * Setup layers: several geo-objects on one map, each with its own mode,
         * properties and options.
         *
         * The map is scaled to fit all of them. The zoom is used only for a single point,
         * which has no bounds to fit.
         */
        setupLayers: function (map) {
            layers.forEach((layer) => {
                const geoObject = this.createGeoObject(layer.mode, layer.state, layer.properties, layer.options);

                if (geoObject) {
                    map.geoObjects.add(geoObject.collection ?? geoObject);
                }
            });

            const bounds = map.geoObjects.getBounds();

            if (!bounds) {
                return;
            }

            if (bounds[0][0] === bounds[1][0] && bounds[0][1] === bounds[1][1]) {
                map.setCenter(bounds[0], this.zoom);

                return;
            }

            map.setBounds(bounds, {
                zoomMargin: 40,
                checkZoomRange: true,
            });
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
    }
}
