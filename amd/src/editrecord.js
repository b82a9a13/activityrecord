$('#activityrecord_form')[0].action = 'javascript:editrecord()';
function editrecord(){
    const idsArray = [
        'apprentice',
        'reviewdate',
        'standard',
        'eors',
        'coach',
        'morm',
        'coursep',
        'courseep',
        'coursecomment',
        'otjhc',
        'otjhe',
        'otjhcomment',
        'recap',
        'recapimpact',
        'details',
        'detailsmod',
        'impact',
        'mathtoday',
        'mathnext',
        'engtoday',
        'engnext',
        'aln',
        'coachfeed',
        'safeguard',
        'agreedact',
        'apprencom',
        'coachsigndate',
        'nextdate',
        'remotef2f',
        'hands',
        'eandd',
        'iaag'
    ];
    const errorTxt = $('#ar_error')[0];
    errorTxt.style.display = 'none';
    let formData = new FormData();
    const file = $('#file')[0].files[0];
    if(file != null){
        formData.append('file', file);
    }
    $('#td_file')[0].style.background = '';
    idsArray.forEach(function(arr){
        formData.append(arr, $(`#${arr}`)[0].value.replaceAll('&', '($)'));
        $(`#td_${arr}`)[0].style.background = '';
    })
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/editrecord.inc.php', true);
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            if(text['error']){
                errorTxt.innerText = 'Invalid values: ';
                text['error'].forEach(function(item){
                    if(idsArray.includes(item[0]) || item[0] == 'file'){
                        $(`#td_${item[0]}`)[0].style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    }
                });
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    window.location.reload();
                } else {
                    errorTxt.innerText = 'Update Error.'
                    errorTxt.style.display = 'block';
                }
            }
        } else {
            errorTxt.innerText = 'Connection Error.';
            errorTxt.style.display = 'block';
        }
    }
    xhr.send(formData);
}