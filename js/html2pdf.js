$(document).ready(function () {
    var form = $('.form-table'),
        cache_width = form.width(),
        a4 = [1240, 1754]; //A4 form in px (150dpi)

    $('#getPDF').on('click', function () {
        $('body').scrollTop(0);
        createPDF();
    }); 

    function createPDF() {
        getCanvas().then(function (canvas) {
            var
                img = canvas.toDataURL('image/jpeg', 1.0),
                doc = new jsPDF({
                    unit: 'px',
                    format: 'a4',
                    orientation: 'landscape'
                });
                currentDate = new Date();
                srtDate = currentDate.getDate() + '.' + (currentDate.getMonth()+1) + '.' + currentDate.getFullYear();
            doc.setFontSize(10);    
            doc.text(20, 30, 'Nord Experten 4-Augen Gespräche' + ' ' + srtDate);
            doc.addImage(img, 'WEBP', 20, 40, 590, 380);
            doc.save('NE 4-Augen Gespräche.pdf');
            form.width(cache_width);
        });
    }

    function getCanvas() {
        form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
        return html2canvas(form, {
            imageTimeout: 2000,
            removeContainer: true
        });
    }
});