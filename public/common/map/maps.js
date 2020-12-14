// Default infoBox Rating Type
var infoBox_ratingType = 'star-rating';

(function ($) {
    "use strict";

    function mainMap() {

        // Locations
        // ----------------------------------------------- //
        var ib = new InfoBox();

        // Infobox Output
        function locationData(locationURL, locationImg, locationTitle, locationAddress, locationRating, locationRatingCounter) {
            return ('' +
                '<a href="' + locationURL + '" class="listing-img-container">' +
                '<div class="infoBox-close"><i class="fa fa-times"></i></div>' +
                '<img src="' + locationImg + '" alt="">' +

                '<div class="listing-item-content">' +
                '<h3>' + locationTitle + '</h3>' +
                '<span>' + locationAddress + '</span>' +
                '</div>' +
                '</a>' +

                '<div class="listing-content">' +
                '<div class="listing-title">' +
                // '<div class="' + infoBox_ratingType + '" data-rating="' + locationRating + '"><div class="rating-counter">(' + locationRatingCounter + ' reviews)</div></div>' +
                '</div>' +
                '</div>')
        }

        var testval = $('#businessdata').text();
        var bInfo = JSON.parse(testval);
        var locations = new Array();
        for (var ind = 0; ind < bInfo.length; ind++) {
            locations.push([locationData('/restaurants/' + bInfo[ind]['id'],
                bInfo[ind]['image'], bInfo[ind]['title'],
                bInfo[ind]['postcode'] + ' ' + bInfo[ind]['address'] + ' ' + bInfo[ind]['city'],
                bInfo[ind]['rating'], '5'),
                bInfo[ind]['lat'], bInfo[ind]['long'], ind, '<h4><strong>' + bInfo[ind]['title'].substr(1, 2) + '</strong></h4>']);
        }
        //   console.log(locations);
        //   console.log(bInfo[3]);
        // var  b_first = bInfo[1]['id'];
        // Locations
        // var locations = [
        //   [ locationData('/business/info/'+bInfo[0]['id'],'images/listing-item-01.jpg',"Tom's Restaurant",'964 School Street, New York', '3.5', '12'), -33.76, 150.99, 1, '<h4><strong>'+'B1'+'</strong></h4>'],
        //   [ locationData('/business/info/'+bInfo[0]['id'],'images/listing-item-02.jpg','Sticky Band','Bishop Avenue, New York', '5.0', '23'), -33.87, 150.76,          2, '<h4><strong>'+'B2'+'</strong></h4>'],
        //   [ locationData('/business/info/'+bInfo[0]['id'],'images/listing-item-03.jpg','Hotel Govendor','778 Country Street, New York', '2.0', '17'), -33.5, 150.94,   3, '<h4><strong>'+'B3'+'</strong></h4>' ],
        //   [ locationData('/business/info/'+bInfo[0]['id'],'images/listing-item-04.jpg','Burger House','2726 Shinn Street, New York', '5.0', '31'), -33.97, 150.99,     4, '<h4><strong>'+'B4'+'</strong></h4>' ],
        // ];
        //   console.log(locations);
        // Chosen Rating Type
        google.maps.event.addListener(ib, 'domready', function () {
            if (infoBox_ratingType = 'numerical-rating') {
                numericalRating('.infoBox .' + infoBox_ratingType + '');
            }
            if (infoBox_ratingType = 'star-rating') {
                starRating('.infoBox .' + infoBox_ratingType + '');
            }
        });


        // Map Attributes
        // ----------------------------------------------- //

        var mapZoomAttr = $('#map').attr('data-map-zoom');
        var mapScrollAttr = $('#map').attr('data-map-scroll');

        if (typeof mapZoomAttr !== typeof undefined && mapZoomAttr !== false) {
            var zoomLevel = parseInt(mapZoomAttr);
        } else {
            var zoomLevel = 5;
        }

        if (typeof mapScrollAttr !== typeof undefined && mapScrollAttr !== false) {
            var scrollEnabled = parseInt(mapScrollAttr);
        } else {
            var scrollEnabled = false;
        }

        // Main Map
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoomLevel,
            scrollwheel: scrollEnabled,
            center: new google.maps.LatLng(bInfo[0]['lat'] + 0.01, bInfo[0]['long']),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            panControl: false,
            navigationControl: false,
            streetViewControl: false,
            gestureHandling: 'cooperative',
            disableDefaultUI: true,

            // Google Map Style
            styles: [{"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#747474"}, {"lightness": "23"}]}, {
                "featureType": "poi.attraction",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#f38eb0"}]
            }, {"featureType": "poi.government", "elementType": "geometry.fill", "stylers": [{"color": "#ced7db"}]}, {
                "featureType": "poi.medical",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffa5a8"}]
            }, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#c7e5c8"}]}, {
                "featureType": "poi.place_of_worship",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#d6cbc7"}]
            }, {"featureType": "poi.school", "elementType": "geometry.fill", "stylers": [{"color": "#c4c9e8"}]}, {
                "featureType": "poi.sports_complex",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#b1eaf1"}]
            }, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": "100"}]}, {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [{"visibility": "off"}, {"lightness": "100"}]
            }, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffd4a5"}]}, {
                "featureType": "road.arterial",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffe9d2"}]
            }, {"featureType": "road.local", "elementType": "all", "stylers": [{"visibility": "simplified"}]}, {
                "featureType": "road.local",
                "elementType": "geometry.fill",
                "stylers": [{"weight": "3.00"}]
            }, {"featureType": "road.local", "elementType": "geometry.stroke", "stylers": [{"weight": "0.30"}]}, {
                "featureType": "road.local",
                "elementType": "labels.text",
                "stylers": [{"visibility": "on"}]
            }, {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#747474"}, {"lightness": "36"}]}, {
                "featureType": "road.local",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#e9e5dc"}, {"lightness": "30"}]
            }, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"visibility": "on"}, {"lightness": "100"}]}, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"color": "#d2e7f7"}]
            }]

        });


        // Marker highlighting when hovering listing item
        $('.listing-item-container').on('mouseover', function () {

            var listingAttr = $(this).data('marker-id');

            if (listingAttr !== undefined) {
                var listing_id = $(this).data('marker-id') - 1;
                var marker_div = allMarkers[listing_id].div;

                $(marker_div).addClass('clicked');

                $(this).on('mouseout', function () {
                    if ($(marker_div).is(":not(.infoBox-opened)")) {
                        $(marker_div).removeClass('clicked');
                    }
                });
            }

        });


        // Infobox
        // ----------------------------------------------- //

        var boxText = document.createElement("div");
        boxText.className = 'map-box';

        var currentInfobox;

        var boxOptions = {
            content: boxText,
            disableAutoPan: false,
            alignBottom: true,
            maxWidth: 0,
            pixelOffset: new google.maps.Size(-134, -55),
            zIndex: null,
            boxStyle: {
                width: "200px",
                transform: "translateX(20px)"
            },
            closeBoxMargin: "0",
            closeBoxURL: "",
            infoBoxClearance: new google.maps.Size(25, 25),
            isHidden: false,
            pane: "floatPane",
            enableEventPropagation: false,
        };


        var markerCluster, overlay, i;
        var allMarkers = [];

        var clusterStyles = [
            {
                textColor: 'white',
                url: '',
                height: 50,
                width: 50
            }
        ];


        var markerIco;
        for (i = 0; i < locations.length; i++) {

            markerIco = locations[i][4];

            var overlaypositions = new google.maps.LatLng(locations[i][1], locations[i][2]),

                overlay = new CustomMarker(
                    overlaypositions,
                    map,
                    {
                        marker_id: i
                    },
                    markerIco
                );

            allMarkers.push(overlay);

            google.maps.event.addDomListener(overlay, 'click', (function (overlay, i) {

                return function () {
                    ib.setOptions(boxOptions);
                    boxText.innerHTML = locations[i][0];
                    ib.open(map, overlay);

                    currentInfobox = locations[i][3];
                    // var latLng = new google.maps.LatLng(locations[i][1], locations[i][2]);
                    // map.panTo(latLng);
                    // map.panBy(0,-90);


                    google.maps.event.addListener(ib, 'domready', function () {
                        $('.infoBox-close').click(function (e) {
                            e.preventDefault();
                            ib.close();
                            $('.map-marker-container').removeClass('clicked infoBox-opened');
                        });

                    });

                }
            })(overlay, i));

        }


        // Marker Clusterer Init
        // ----------------------------------------------- //

        var options = {
            imagePath: 'images/',
            styles: clusterStyles,
            minClusterSize: 2
        };

        markerCluster = new MarkerClusterer(map, allMarkers, options);

        google.maps.event.addDomListener(window, "resize", function () {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
        });


        // Custom User Interface Elements
        // ----------------------------------------------- //

        // Custom Zoom-In and Zoom-Out Buttons
        // var zoomControlDiv = document.createElement('div');
        // var zoomControl = new ZoomControl(zoomControlDiv, map);

        function ZoomControl(controlDiv, map) {

            zoomControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(zoomControlDiv);
            // Creating divs & styles for custom zoom control
            controlDiv.style.padding = '5px';
            controlDiv.className = "zoomControlWrapper";

            // Set CSS for the control wrapper
            var controlWrapper = document.createElement('div');
            controlDiv.appendChild(controlWrapper);

            // Set CSS for the zoomIn
            var zoomInButton = document.createElement('div');
            zoomInButton.className = "custom-zoom-in";
            controlWrapper.appendChild(zoomInButton);

            // Set CSS for the zoomOut
            var zoomOutButton = document.createElement('div');
            zoomOutButton.className = "custom-zoom-out";
            controlWrapper.appendChild(zoomOutButton);

            // Setup the click event listener - zoomIn
            google.maps.event.addDomListener(zoomInButton, 'click', function () {
                map.setZoom(map.getZoom() + 1);
            });

            // Setup the click event listener - zoomOut
            google.maps.event.addDomListener(zoomOutButton, 'click', function () {
                map.setZoom(map.getZoom() - 1);
            });

        }


        // Scroll enabling button
        var scrollEnabling = $('#scrollEnabling');

        $(scrollEnabling).click(function (e) {
            e.preventDefault();
            $(this).toggleClass("enabled");

            if ($(this).is(".enabled")) {
                map.setOptions({'scrollwheel': true});
            } else {
                map.setOptions({'scrollwheel': false});
            }
        })


        // Geo Location Button
        $("#geoLocation, .input-with-icon.location a").click(function (e) {
            e.preventDefault();
            geolocate();
        });

        function geolocate() {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    map.setCenter(pos);
                    map.setZoom(12);
                });
            }
        }

    }


    // Map Init
    var map = document.getElementById('map');
    if (typeof (map) != 'undefined' && map != null) {
        google.maps.event.addDomListener(window, 'load', mainMap);
        google.maps.event.addDomListener(window, 'resize', mainMap);
    }


    // ---------------- Main Map / End ---------------- //


    // Single Listing Map
    // ----------------------------------------------- //

    function singleListingMap() {

        var myLatlng = new google.maps.LatLng({lng: $('#singleListingMap').data('longitude'), lat: $('#singleListingMap').data('latitude'),});

        var single_map = new google.maps.Map(document.getElementById('singleListingMap'), {
            zoom: 15,
            center: myLatlng,
            scrollwheel: false,
            zoomControl: false,
            mapTypeControl: false,
            scaleControl: false,
            panControl: false,
            navigationControl: false,
            streetViewControl: false,
            styles: [{"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#747474"}, {"lightness": "23"}]}, {
                "featureType": "poi.attraction",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#f38eb0"}]
            }, {"featureType": "poi.government", "elementType": "geometry.fill", "stylers": [{"color": "#ced7db"}]}, {
                "featureType": "poi.medical",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffa5a8"}]
            }, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#c7e5c8"}]}, {
                "featureType": "poi.place_of_worship",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#d6cbc7"}]
            }, {"featureType": "poi.school", "elementType": "geometry.fill", "stylers": [{"color": "#c4c9e8"}]}, {
                "featureType": "poi.sports_complex",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#b1eaf1"}]
            }, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": "100"}]}, {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [{"visibility": "off"}, {"lightness": "100"}]
            }, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffd4a5"}]}, {
                "featureType": "road.arterial",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffe9d2"}]
            }, {"featureType": "road.local", "elementType": "all", "stylers": [{"visibility": "simplified"}]}, {
                "featureType": "road.local",
                "elementType": "geometry.fill",
                "stylers": [{"weight": "3.00"}]
            }, {"featureType": "road.local", "elementType": "geometry.stroke", "stylers": [{"weight": "0.30"}]}, {
                "featureType": "road.local",
                "elementType": "labels.text",
                "stylers": [{"visibility": "on"}]
            }, {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#747474"}, {"lightness": "36"}]}, {
                "featureType": "road.local",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#e9e5dc"}, {"lightness": "30"}]
            }, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"visibility": "on"}, {"lightness": "100"}]}, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"color": "#d2e7f7"}]
            }]
        });

        // Steet View Button
        $('#streetView').click(function (e) {
            e.preventDefault();
            single_map.getStreetView().setOptions({visible: true, position: myLatlng});
            // $(this).css('display', 'none')
        });


        // Custom zoom buttons
        var zoomControlDiv = document.createElement('div');
        var zoomControl = new ZoomControl(zoomControlDiv, single_map);

        function ZoomControl(controlDiv, single_map) {

            zoomControlDiv.index = 1;
            single_map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(zoomControlDiv);

            controlDiv.style.padding = '5px';

            var controlWrapper = document.createElement('div');
            controlDiv.appendChild(controlWrapper);

            var zoomInButton = document.createElement('div');
            zoomInButton.className = "custom-zoom-in";
            controlWrapper.appendChild(zoomInButton);

            var zoomOutButton = document.createElement('div');
            zoomOutButton.className = "custom-zoom-out";
            controlWrapper.appendChild(zoomOutButton);

            google.maps.event.addDomListener(zoomInButton, 'click', function () {
                single_map.setZoom(single_map.getZoom() + 1);
            });

            google.maps.event.addDomListener(zoomOutButton, 'click', function () {
                single_map.setZoom(single_map.getZoom() - 1);
            });

        }


        // Marker
        //var singleMapIco =  "<i class='"+$('#singleListingMap').data('map-icon')+"'></i>";
        var singleMapIco = "<h4><strong>" + $('#singleListingMap').data('map-icon') + "</strong></h4>";

        new CustomMarker(
            myLatlng,
            single_map,
            {
                marker_id: '1'
            },
            singleMapIco
        );


    }

    // Single Listing Map Init
    var single_map = document.getElementById('singleListingMap');
    if (typeof (single_map) != 'undefined' && single_map != null) {
        google.maps.event.addDomListener(window, 'load', singleListingMap);
        google.maps.event.addDomListener(window, 'resize', singleListingMap);
    }

    // -------------- Single Listing Map / End -------------- //


    // Custom Map Marker
    // ----------------------------------------------- //

    function CustomMarker(latlng, map, args, markerIco) {
        this.latlng = latlng;
        this.args = args;
        this.markerIco = markerIco;
        this.setMap(map);
    }

    CustomMarker.prototype = new google.maps.OverlayView();

    CustomMarker.prototype.draw = function () {

        var self = this;

        var div = this.div;

        if (!div) {

            div = this.div = document.createElement('div');
            div.className = 'map-marker-container';

            div.innerHTML = '<div class="marker-container">' +
                '<div class="marker-card">' +
                '<div class="front face">' + self.markerIco + '</div>' +
                '<div class="back face">' + self.markerIco + '</div>' +
                '<div class="marker-arrow"></div>' +
                '</div>' +
                '</div>'


            // Clicked marker highlight
            google.maps.event.addDomListener(div, "click", function (event) {
                $('.map-marker-container').removeClass('clicked infoBox-opened');
                google.maps.event.trigger(self, "click");
                $(this).addClass('clicked infoBox-opened');
            });


            if (typeof (self.args.marker_id) !== 'undefined') {
                div.dataset.marker_id = self.args.marker_id;
            }

            var panes = this.getPanes();
            panes.overlayImage.appendChild(div);
        }

        var point = this.getProjection().fromLatLngToDivPixel(this.latlng);

        if (point) {
            div.style.left = (point.x) + 'px';
            div.style.top = (point.y) + 'px';
        }
    };

    CustomMarker.prototype.remove = function () {
        if (this.div) {
            this.div.parentNode.removeChild(this.div);
            this.div = null;
            $(this).removeClass('clicked');
        }
    };

    CustomMarker.prototype.getPosition = function () {
        return this.latlng;
    };

    // -------------- Custom Map Marker / End -------------- //


})(this.jQuery);
