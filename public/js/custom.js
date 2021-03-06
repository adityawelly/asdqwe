$(document).ajaxStart(function() { Pace.restart(); });
$(document).ajaxComplete(function(event, request, settings){
    if (request.status == 419 || request.status == 401) {
        showNotificationCallback('warning', 'Sesi anda sudah habis silahkan login kembali',{
            onClose: function(){
                location.assign('/login');
            }
        });
    }
});
$.validator.setDefaults({
    ignore: '.select2-input, .select2-focusser',
    highlight: function(element) {
        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function(element){
        $(element).closest('.form-group').removeClass('has-error').removeClass('has-success');
    },
    success: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
        $(element).remove();
    }
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(){
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $('.timepicker').datetimepicker({
        format: 'hh:mm:ss',
    });
    
    $('.selectpicker').select2({
        theme: "bootstrap",
        placeholder: "Pilih Opsi"
    }).on('change', function() {
        $(this).trigger('blur');
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('.nik-mask').mask('00000');
    $('.ktp-mask').mask('0000000000000000');
    $('.phone-mask').mask('0000-0000-00999');
    $('.npwp-mask').mask('00.000.000.0-000.000');
    $('.money-mask').mask('0.000.000.000', {
        reverse: true
    });
    $('.date-sql-mask').mask('0000-00-00',{
        placeholder: 'yyyyy-mm-dd'
    });
    $('.datatable').DataTable();

    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    });

});
function showNotification(type, msg) {
    $.notify({
        icon: 'fa fa-bell',
        title: 'Notifikasi',
        message: msg
    },{
        type: type,
        allow_dismiss: true,
        delay: 0,
        placement: {
            from: (getLocalStorage('notif_position') || 'top'),
            align: (getLocalStorage('notif_align') || 'right')
        },
        // timer: 1000,
        newest_on_top: true,
        z_index: 2000,
    });
}
function showNotificationCallback(type, msg, options){
    $.notify({
        icon: 'fa fa-bell',
        title: 'Notifikasi',
        message: msg
    },$.extend({
      type: type,
    //   timer: 1000,
      newest_on_top: true,
      allow_dismiss: true,
      delay: 0,
      z_index: 2000,
      placement: {
        from: (getLocalStorage('notif_position') || 'top'),
        align: (getLocalStorage('notif_align') || 'right')
      }
    },options));
}
function showErrorNotification(errors) {
    var timer = 1000;

    for (var key in errors) {
        $.notify({
            icon: 'fa fa-bell',
            title: 'Opps...',
            message: errors[key][0]
        },{
            type: 'danger',
            placement: {
                from: (getLocalStorage('notif_position') || 'top'),
                align: (getLocalStorage('notif_align') || 'right')
            },
            timer: timer,
            allow_dismiss: true,
            delay: 0,
            newest_on_top: true,
            z_index: 2000,
        });
        timer += 2000
    }
}
function showSwal(type, title, msg) {
    swal({
        type: type,
        title: title,
        html: msg
    });
}
function setLocalStorage(key, value) {
    localStorage.setItem(key, value);
}
function getLocalStorage(key) {
    return localStorage.getItem(key.toString());
}
function arrayUnique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
}
function redirect(url) {
    return location.assign(url);
}
// Click handler can be added latter, after jQuery is loaded...
$('.toggle-sidebar').click(function(event) {
    event.preventDefault();
    if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
      sessionStorage.setItem('sidebar-toggle-collapsed', '');
    } else {
      sessionStorage.setItem('sidebar-toggle-collapsed', '1');
    }
});
/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ID (Indonesia; Indonesian)
 */
$.extend( $.validator.messages, {
	required: "Kolom ini diperlukan.",
	remote: "Harap benarkan kolom ini.",
	email: "Silakan masukkan format email yang benar.",
	url: "Silakan masukkan format URL yang benar.",
	date: "Silakan masukkan format tanggal yang benar.",
	dateISO: "Silakan masukkan format tanggal(ISO) yang benar.",
	number: "Silakan masukkan angka yang benar.",
	digits: "Harap masukan angka saja.",
	creditcard: "Harap masukkan format kartu kredit yang benar.",
	equalTo: "Harap masukkan nilai yg sama dengan sebelumnya.",
	maxlength: $.validator.format( "Input dibatasi hanya {0} karakter." ),
	minlength: $.validator.format( "Input tidak kurang dari {0} karakter." ),
	rangelength: $.validator.format( "Panjang karakter yg diizinkan antara {0} dan {1} karakter." ),
	range: $.validator.format( "Harap masukkan nilai antara {0} dan {1}." ),
	max: $.validator.format( "Harap masukkan nilai lebih kecil atau sama dengan {0}." ),
	min: $.validator.format( "Harap masukkan nilai lebih besar atau sama dengan {0}." )
});