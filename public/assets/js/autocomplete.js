"use strict";
function getAddressComponent(address_components, key) {
    var value='';
    var postalCodeType = address_components.filter(function(aComp) {
        return aComp.types.some(function(typesItem) {return typesItem === key})
    });
    if (postalCodeType != null && postalCodeType.length > 0) {
        value = postalCodeType[0].long_name
    }
    return value;
}

function onPlaceChange() {
    var inputId = this.inputId;
    var inputElement = $('#'+inputId);
    var place = this.getPlace();

    // Return if there is no geometry
    if (!place.geometry || !place.geometry.location) {
        return;
    }

    var place_name = inputElement.closest('.place_autocomplete_container').find('[name="place"]');
    if (place_name.length) {
        place_name.val(place.formatted_address);
        place_name[0].dispatchEvent(new Event('input'));
    }

    var latitude = inputElement.closest('.place_autocomplete_container').find('[name="lat"]');
    if (latitude.length) {
        latitude.val(place.geometry.location.lat());
        latitude[0].dispatchEvent(new Event('input'));
    }

    var longitude = inputElement.closest('.place_autocomplete_container').find('[name="lng"]');
    if (longitude.length) {
        longitude.val(place.geometry.location.lng());
        longitude[0].dispatchEvent(new Event('input'));
    }

    var formatted_address = inputElement.closest('.place_autocomplete_container').find('[name="address_1"]');
    if (formatted_address.length) {
        formatted_address.val(place.formatted_address);
        formatted_address[0].dispatchEvent(new Event('input'));
    }

    var country = inputElement.closest('.place_autocomplete_container').find('[name="country"]');
    if (country.length) {
        country.val(getAddressComponent(place.address_components, 'country'));
        country[0].dispatchEvent(new Event('input'));
    }

    var state = inputElement.closest('.place_autocomplete_container').find('[name="state"]');
    if (state.length) {
        state.val(getAddressComponent(place.address_components, 'administrative_area_level_1'));
        state[0].dispatchEvent(new Event('input'));
    }

    var city = inputElement.closest('.place_autocomplete_container').find('[name="city"]');
    if (city.length) {
        city.val(getAddressComponent(place.address_components, 'administrative_area_level_2'));
        city[0].dispatchEvent(new Event('input'));
    }

    var postal_code = inputElement.closest('.place_autocomplete_container').find('[name="postal_code"]');
    if (postal_code.length) {
        postal_code.val(getAddressComponent(place.address_components, 'postal_code'));
        postal_code[0].dispatchEvent(new Event('input'));
    }

    if (inputElement.length) {
        inputElement.val(place.formatted_address).attr('data-value', place.formatted_address).blur();
        inputElement[0].dispatchEvent(new Event('input'));
    }
}

function initAutocomplete() {
    var inputs = document.getElementsByClassName('place_autocomplete');
    var autocompletes = [];

    for (var i = 0; i < inputs.length; i++) {
        var options = {
            fields: ["address_components", "formatted_address", "geometry", "name"],
            types: [inputs[i].getAttribute('data-type')],
        };

        var autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
        autocomplete.inputId = inputs[i].id;
        autocomplete.addListener('place_changed', onPlaceChange);
        autocompletes.push(autocomplete);

        // google.maps.event.addDomListener(inputs[i], 'keydown', function(event) {
        //     if (event.keyCode === 13) {
        //         event.preventDefault();
        //     }
        // });

        inputs[i].addEventListener('keydown', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }
}
