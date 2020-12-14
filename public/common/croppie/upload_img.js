/*
 fileName:
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute

let uploadCrop;
$(document).ready(function () {
    uploadCrop = $("#upload-origin").croppie({
        enableExif: true,
        viewport: {
            width: 200,
            height: 200,
            type: 'rectangle'
        },
        boundary: {
            width: 210,
            height: 325
        }
    });
});

$('#upload-crop').on('change', function () {
    let reader = new FileReader();
    let type = this.files[0]['type'];
    if (type !== 'image/png' && type !== 'image/jpeg') {
        alert("The image format is wrong, the request is jpg, jpeg, png format.");
        return;
    }
    reader.onload = function (e) {
        uploadCrop.croppie('bind', {
            url: e.target.result
        }).then(function () {
            refreshTargetImg();
        });
    };
    reader.readAsDataURL(this.files[0]);
});

$('#upload-origin').on('mouseup touchend', function () {
    refreshTargetImg();
});
function uploadCropImg() {
    // refreshTargetImg();
}
function refreshTargetImg() {
    uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        $("#profile_avatar").attr("src", resp);
    });
}
