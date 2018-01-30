(function (BX) {
    BX.ready(function () {
        var reportBtn = document.getElementById('ajax-report');

        if (reportBtn) {
            reportBtn.onclick = function () {
                var newsId = reportBtn.getAttribute('data-newsid');

                var textElem = document.getElementById('ajax-report-text');

                BX.ajax.loadJSON(
                    reportBtn.getAttribute('href'),
                        {'TYPE': 'AJAX_REPORT', 'ID': newsId},
                    function (data){
                        textElem.innerText = "Ваше мнение учтено, №" + data['ID'];
                    },
                    function (data){
                        textElem.innerText = "Ошибка!";
                    }
                );

                return false;
            }
        }
    });
})(BX);