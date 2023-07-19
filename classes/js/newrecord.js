document.getElementById('activityrecord_form').action = 'javascript:newrecord()';
function newrecord(){
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
        'nextdate',
        'remotef2f'
    ];
    const errorTxt = document.getElementById('ar_error');
    errorTxt.style.display = 'none';
    let formData = new FormData();
    formData.append('file', document.getElementById('file').files[0]);
    document.getElementById('td_file').style.background = '';
    idsArray.forEach(function(arr){
        formData.append(arr, document.getElementById(arr).value.replaceAll('&','($)'));
        document.getElementById("td_"+arr).style.background = '';
    });
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/newrecord.inc.php', true);
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            if(text['error']){
                errorTxt.innerText = 'Invalid values: ';
                text['error'].forEach(function(item){
                    if(idsArray.includes(item[0])){
                        document.getElementById("td_"+item[0]).style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    } else if(item[0] == 'file'){
                        document.getElementById("td_"+item[0]).style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    }
                });
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    window.location.reload();
                } else {
                    errorTxt.innerText = 'Creation Error.'
                    errorTxt.style.display = 'block';
                }
            }
        } else {
            errorTxt.innerText = 'Connection Error.';
            errorTxt.style.display = 'block';
        }
    }
    xhr.send(formData);
};