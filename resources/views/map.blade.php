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

    <!-- Modal Polyline -->
    <div class="modal fade" id="CreatePolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Polyline Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polylines.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill yours">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Desc</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polyline" name="geom_polyline" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polyline" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.
                            createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Points -->
    <div class="modal fade" id="CreatePointsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Marker Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill yours">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Desc</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_points" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_points" name="geom_points" rows="3"></textarea>
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
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Polygon -->
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Polygon Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygons.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill yours">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Desc</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry (WKT)</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3" readonly></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.
                            createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
    // === Setup peta seperti biasa ===
    var map = L.map('map').setView([-8.843260402577958, 115.1584501239187], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        draw: {
            position: 'topleft',
            polyline: true,
            polygon: true,
            rectangle: true,
            circle: false,
            marker: true,
            circlemarker: false
        },
        edit: false
    });

    map.addControl(drawControl);

    map.on('draw:created', function (e) {
        var type = e.layerType,
            layer = e.layer;

        var drawnJSONObject = layer.toGeoJSON();
        var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

        if (type === 'polyline') {
            $('#geom_polyline').val(objectGeometry);
            $('#CreatePolylineModal').modal('show');
        } else if (type === 'polygon' || type === 'rectangle') {
            $('#geom_polygon').val(objectGeometry);
            $('#CreatePolygonModal').modal('show');
        } else if (type === 'marker') {
            $('#geom_points').val(objectGeometry);
            $('#CreatePointsModal').modal('show');
        }

        drawnItems.addLayer(layer);
    });

    function createPopup(feature, type) {
        let baseUrl = '{{ asset('storage/images/') }}/';
        let popupContent = `
            Nama: ${feature.properties.name}<br>
            Deskripsi: ${feature.properties.description}<br>
            ${type === 'polyline' ? `Panjang: ${(feature.properties.length_m || 0).toFixed(2)} m<br>` : ''}
            ${type === 'polygon' ? `Luas: ${parseFloat(feature.properties.area_ha).toFixed(2)} Ha<br>` : ''}
            Dibuat: ${feature.properties.created_at}<br>
            <img src='${baseUrl}${feature.properties.image}' width='250'><br>
            <div class='row mt-4'>
                <div class='col-6 text-center'>
                    <a href='${routeUrl(type, 'edit', feature.properties.id)}' class='btn btn-warning btn-sm'>
                        <i class='fa-solid fa-pen-to-square'></i>
                    </a>
                </div>
                <div class='col-6 text-center'>
                    <form method='POST' action='${routeUrl(type, 'destroy', feature.properties.id)}'>
                        @csrf @method('DELETE')
                        <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm("Yakin mau dihapus ?")'>
                            <i class='fa-solid fa-trash'></i>
                        </button>
                    </form>
                </div><br>
                Oleh: ${feature.properties.user_created}<br>
            </div>`;
        return popupContent;
    }

    function routeUrl(type, action, id) {
        const routes = {
            points: {
                edit: '{{ route('points.edit', ':id') }}',
                destroy: '{{ route('points.destroy', ':id') }}'
            },
            polylines: {
                edit: '{{ route('polylines.edit', ':id') }}',
                destroy: '{{ route('polylines.destroy', ':id') }}'
            },
            polygons: {
                edit: '{{ route('polygons.edit', ':id') }}',
                destroy: '{{ route('polygons.destroy', ':id') }}'
            }
        };
        return routes[type][action].replace(':id', id);
    }

    function createLayer(type, url) {
        let layer = L.geoJson(null, {
            onEachFeature: function (feature, layer) {
                const popupContent = createPopup(feature, type);
                layer.on({
                    click: function () { layer.bindPopup(popupContent).openPopup(); },
                    mouseover: function () { layer.bindTooltip(feature.properties.name).openTooltip(); }
                });
            }
        });

        $.getJSON(url, function (data) {
            layer.addData(data);
            map.addLayer(layer);
        });
    }

    createLayer('points', "{{ route('api.points') }}");
    createLayer('polylines', "{{ route('api.polylines') }}");
    createLayer('polygons', "{{ route('api.polygons') }}");
</script>


@endsection
