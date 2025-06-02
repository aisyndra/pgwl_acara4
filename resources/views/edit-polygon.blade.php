@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Edit Polygon -->
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="editPolygonLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPolygonLabel">Edit Polygon Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygons.update', $id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill yours">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.
                            createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="400">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script>
        var map = L.map('map').setView([-8.843260402577958, 115.1584501239187], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });

        map.addControl(drawControl);

        map.on('draw:edited', function(e) {
            e.layers.eachLayer(function(layer){

            var drawnJSONObject = layer.toGeoJSON();
            console.log(drawnJSONObject);

            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
            console.log(objectGeometry);

            // layer properties
                var properties = drawnJSONObject.properties; // untuk mencegah undefined
                console.log(properties);

            drawnItems.addLayer(layer);

            //menampilkan data ke dalam modal
                $('#name').val(properties.name);
                $('#description').val(properties.description);
                $('#geom_polygon').val(objectGeometry);
                $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" + (properties
                    .image));

                //menampilkan modal edit
                $('#CreatePolygonModal').modal('show');
            });
        });

        /* GeoJSON Polygons */
        var polygonLayer = L.geoJson(null, {
            onEachFeature: function(feature, layer) {

                  //memasukkan layer point ke dalam drawnItems
                drawnItems.addLayer(layer);

            var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);


                layer.on({
                    click: function(e) {
                        //menampilkan data ke dalam modal
                        $('#name').val(feature.properties.name);
                        $('#description').val(feature.properties.description);
                        $('#geom_polygon').val(objectGeometry);
                        $('#preview-image-polygon').attr('src', "{{ asset('storage/images') }}/" + (
                            feature.properties.image));

                        //menampilkan modal edit
                        $('#CreatePolygonModal').modal('show');
                    },
                });
            },
        });

        $.getJSON("{{ route('api.polygon', $id) }}", function(data) {
            polygonLayer.addData(data);
            map.addLayer(polygonLayer);
            map.fitBounds(polygonLayer.getBounds());
        });
    </script>
@endsection
