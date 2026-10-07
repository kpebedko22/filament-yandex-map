/**
 * Multipolygon geo-object.
 *
 * Yandex Maps API has no multipolygon geometry: rings of a single ymaps.Polygon
 * are an outer contour and holes (even-odd fill rule), so separate contours
 * can't be rings of the same polygon. Therefore, multipolygon is a collection
 * of ymaps.Polygon, one polygon per contour.
 *
 * The class implements the part of the ymaps.GeoObject interface which is used
 * by the components (geometry, editor, events, options), so multipolygon is
 * handled like any other geo-object. The only difference is that it is not
 * a ymaps object, so its collection must be added to the map.
 *
 * Coordinates are a list of polygons, each polygon is a list of rings,
 * each ring is a list of [lat, lng]:
 * [
 *     [[[lat, lng], ...]],             // polygon with one ring
 *     [[[lat, lng], ...], [[...]]],    // polygon with a hole
 * ]
 */
export default class MultiPolygon {
    constructor(coordinates, properties, options) {
        this.properties = properties;
        this.polygonOptions = options;
        this.polygons = [];
        this.isEditing = false;

        this.collection = new ymaps.GeoObjectCollection();
        this.events = new ymaps.event.Manager();
        this.options = this.collection.options;

        this.geometry = {
            getCoordinates: () => this.getCoordinates(),
            setCoordinates: (coordinates) => this.setCoordinates(coordinates),
            getBounds: () => this.getBounds(),
        };

        this.editor = {
            startEditing: () => this.startEditing(),
            stopEditing: () => this.stopEditing(),
            startDrawing: () => this.startDrawing(),
            stopDrawing: () => this.stopDrawing(),
        };

        coordinates.forEach((polygonCoordinates) => this.addPolygon(polygonCoordinates));
    }

    /**
     * Coordinates of all polygons. Polygons without points are skipped:
     * it is a contour which is going to be drawn.
     */
    getCoordinates() {
        return this.polygons
            .map((polygon) => polygon.geometry.getCoordinates())
            .filter((rings) => rings.some((ring) => ring.length));
    }

    /**
     * Replace all polygons by the new ones.
     * Like for the ymaps geometry, the change is announced by the 'geometrychange' event.
     */
    setCoordinates(coordinates) {
        this.polygons.forEach((polygon) => this.collection.remove(polygon));
        this.polygons = [];

        coordinates.forEach((polygonCoordinates) => this.addPolygon(polygonCoordinates));

        this.events.fire('geometrychange');
    }

    /**
     * Bounds of all polygons, null if there are no points.
     */
    getBounds() {
        let bounds = null;

        this.polygons.forEach((polygon) => {
            const polygonBounds = polygon.geometry.getBounds();

            if (!polygonBounds) {
                return;
            }

            bounds = bounds
                ? [
                    [Math.min(bounds[0][0], polygonBounds[0][0]), Math.min(bounds[0][1], polygonBounds[0][1])],
                    [Math.max(bounds[1][0], polygonBounds[1][0]), Math.max(bounds[1][1], polygonBounds[1][1])],
                ]
                : polygonBounds;
        });

        return bounds;
    }

    addPolygon(coordinates = []) {
        const polygon = new ymaps.Polygon(coordinates, this.properties, this.polygonOptions);

        polygon.events.add('geometrychange', () => this.events.fire('geometrychange'));

        this.polygons.push(polygon);
        this.collection.add(polygon);

        if (this.isEditing) {
            polygon.editor.startEditing();
        }

        return polygon;
    }

    removePolygon(polygon) {
        this.polygons = this.polygons.filter((item) => item !== polygon);
        this.collection.remove(polygon);
    }

    /**
     * Start editing of all polygons, including the polygons which will be added later.
     */
    startEditing() {
        this.isEditing = true;

        this.polygons.forEach((polygon) => polygon.editor.startEditing());
    }

    stopEditing() {
        this.isEditing = false;

        this.polygons.forEach((polygon) => polygon.editor.stopEditing());
    }

    /**
     * Start drawing of a new contour, the existing contours stay as they are.
     * If the last polygon has no points yet, the same polygon is used again.
     */
    startDrawing() {
        let polygon = this.polygons[this.polygons.length - 1];

        if (!polygon || polygon.geometry.getCoordinates().some((ring) => ring.length)) {
            polygon = this.addPolygon();
        }

        polygon.editor.startDrawing();
    }

    /**
     * Stop drawing, polygon without points is dropped.
     */
    stopDrawing() {
        this.polygons.forEach((polygon) => polygon.editor.stopDrawing());

        this.polygons
            .filter((polygon) => !polygon.geometry.getCoordinates().some((ring) => ring.length))
            .forEach((polygon) => this.removePolygon(polygon));
    }
}
