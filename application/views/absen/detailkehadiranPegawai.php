<main class="main-content position-relative border-radius-lg" style="margin-left: 270px">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.8/dist/esri-leaflet.js" integrity="sha512-E0DKVahIg0p1UHR2Kf9NX7x7TUewJb30mxkxEm2qOYTVJObgsAGpEol9F6iK6oefCbkJiA4/i6fnTHzM6H1kEA==" crossorigin=""></script>

    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.3/dist/esri-leaflet-geocoder.css" integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g==" crossorigin="">
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.3/dist/esri-leaflet-geocoder.js" integrity="sha512-mwRt9Y/qhSlNH3VWCNNHrCwquLLU+dTbmMxVud/GcnbXfOKJ35sznUmt3yM39cMlHR2sHbV9ymIpIMDpKg4kKw==" crossorigin=""></script>
    <style>
        #map {
            height: 300px;
        }

        .popupCustom .leaflet-popup-tip,
        .popupCustom .leaflet-popup-content-wrapper {
            background: #5E72E4;
            color: white;
            font-size: 15px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row" style="margin-bottom: 50px">
            <a href="<?php echo site_url('absensi/dataKehadiran') ?>" class="btn btn-danger">Back</a>
            <?php foreach ($user as $users) { ?>
                <?php $tanggal = date('Y-m-d', strtotime($users->tgl_absen)); ?>
                <div class="col">


                    <div class="card p-5">
                        <h5>Detail Presensi:</h5>


                        <p>Nama Pegawai: <?= $users->nama_lengkap ?></p>
                        <p>Username: <?= $users->username ?></p>
                        <?php
                        setlocale(LC_ALL, 'id-ID', 'id_ID');
                        ?>
                        <p>Tanggal Presensi: <?= tanggal_indo($tanggal, true) ?></p>
                        <?php if ($users->keterangan_absen == 'Sakit') { ?>
                            <p>Waktu Datang: <span class="badge badge-pill bg-gradient-danger">Sakit</span></p>
                        <?php } elseif ($users->keterangan_absen == 'Cuti') { ?>
                            <p>Waktu Datang: <span class="badge badge-pill bg-gradient-warning">Cuti</span></p>

                        <?php } else { ?>
                            <p>Waktu Datang: <?= $users->jam_absen ?></p>
                        <?php } ?>
                        <?php if ($users->keterangan_absen == 'Sakit') { ?>
                            <p>Waktu Pulang: <span class="badge badge-pill bg-gradient-danger">Sakit</span></p>
                        <?php } elseif ($users->keterangan_absen == 'Cuti') { ?>
                            <p>Waktu Pulang: <span class="badge badge-pill bg-gradient-warning">Cuti</span></p>
                        <?php } else { ?>
                            <?php if ($users->jam_absen_pulang == '00:00:00') { ?>
                                <p>Waktu Pulang: <span class="badge badge-pill bg-gradient-danger">Belum Presensi Pulang</span></p>
                            <?php } else { ?>
                                <p>Waktu Pulang: <?= $users->jam_absen_pulang ?></p>
                        <?php }
                        } ?>
                        <p>Keterangan Presensi: <?= $users->keterangan_absen ?></p>


                    </div>
                </div>


                 <div class="col-12 col-md-6">
                    <div class="card p-0">
                        <h5 class="p-4">Foto Selfie:</h5>
                    </div></div>







                <div class="col-12 col-md-6">
                    <div class="card p-0">
                        <h5 class="p-4">Maps Presensi:</h5>

                        <div id="map"> </div>
                        <script>
                            setLocation = [<?= $users->lat_absen ?>, <?= $users->long_absen ?>]
                            var map = L.map('map').setView([<?= $users->lat_absen ?>, <?= $users->long_absen ?>], 13);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            var office = L.icon({
                                iconUrl: '<?= base_url('upload/office.png') ?>',
                                iconSize: [70], // size of the icon
                            });

                            var customOptions = {
                                'maxWidth': '150',
                                'width': '100',
                                'className': 'popupCustom'
                            }
                            var customOptions2 = {
                                'maxWidth': '300',
                                'width': '100',
                                'className': 'popupCustom'
                            }

                            var geocodeService = L.esri.Geocoding.geocodeService({
                                apikey: 'AAPK1e6d5c2dfd0648f8bc8379c6d8af7696XVzE0quYtPh_8bOmEz-grWLy69WPhunJ32NkS-ULj8OFOJ6J0WvmP8mfvlVgdmIg' // replace with your api key - https://developers.arcgis.com
                            });

                            var greenIcon = L.icon({
                                iconUrl: '<?= base_url('upload/marker.png') ?>',

                                iconSize: [50], // size of the icon
                            });
                            geocodeService.reverse().latlng(setLocation).run(function(error, result) {
                                if (error) {
                                    return;
                                }

                                L.marker(result.latlng, {
                                    icon: greenIcon
                                }).addTo(map).bindPopup(result.address.Match_addr, customOptions).openPopup();
                            });
                         
                        </script>


                    </div>
                </div>

        </div>
    </div>
</main>
<?php } ?>