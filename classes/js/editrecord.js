document.getElementById('activityrecord_form').action = 'javascript:editrecord()';
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
        'remotef2f'
    ];
    const errorTxt = document.getElementById('ar_error');
    errorTxt.style.display = 'none';
    let formData = new FormData();
    const file = document.getElementById('file').files[0];
    if(file != null){
        formData.append('file', file);
    }
    document.getElementById('td_file').style.background = '';
    idsArray.forEach(function(arr){
        formData.append(arr, document.getElementById(arr).value.replaceAll('&', '($)'));
        document.getElementById('td_'+arr).style.background = '';
    })
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/editrecord.inc.php', true);
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            console.log(text);
            if(text['error']){
                errorTxt.innerText = 'Invalid values: ';
                text['error'].forEach(function(item){
                    if(idsArray.includes(item[0]) || item[0] == 'file'){
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
    console.log(document.getElementById('nextdate').value);
    xhr.send(formData);
}